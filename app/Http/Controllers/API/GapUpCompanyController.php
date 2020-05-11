<?php

namespace App\Http\Controllers\API;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use KubAT\PhpSimple\HtmlDomParser;

class GapUpCompanyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $companies = Company::all();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://stockbeep.com/table-data/gap-up-stocks?country=au&time-zone=-600&sort-column=gap&sort-order=desc");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $data = $response->data;

        $matched = [];
        foreach ($data as $ticker) {
            foreach ($companies as $company) {
                if (strpos($company->name, $ticker->ssname) !== false) {
                    if (strip_tags($ticker->sscode) === $company->code_short) {
                        $ticker->sstime = Carbon::parse($ticker->sstime)->today()->diffForHumans();
                    
                        $matched[] = array_merge((array) $company->float, (array) $company->code_short,(array) $ticker);
                    }
                }
            }
        }

        foreach ($matched as $key => $company) {
            $price = (float) $company['sslast'];
            if ($price <= 0.3) {
                unset($matched[$key]);                
            }

            $vol = $company['ssvol'];

            $letters = [
                'k' => 1000,
                'M' => 1000000,
                'B' => 1000000000,
            ];

            foreach ($letters as $letter => $multiple) {
                if (strpos($vol, $letter) !== false) {
                    str_replace($letter, "", $vol);
                    $vol = (float) $vol * $multiple;
                    if ($vol < 100000) {
                        unset($matched[$key]);                
                    }
                }
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.asx.com.au/asx/statistics/announcements.do?by=asxCode&asxCode=".$company[1]."&timeframe=D&period=T");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $dom = HtmlDomParser::str_get_html($response);
            
            if (!empty($dom)) {
                $tds = $dom->find('td');

                foreach ($tds as $td) {
                    if ($td->class == 'pricesens') {
                        $matched[$key]['news'] = 'Y';
                    } 
                }
            }
        }
        $matched = array_values($matched);

        return response()->json($matched);
    }
}
