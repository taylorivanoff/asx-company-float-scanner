<?php

namespace App\Http\Controllers\API;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MomentumCompanyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data_sources = [
            'gap-up' => 'https://stockbeep.com/table-data/gap-up-stocks?country=au&time-zone=-600&sort-column=gap&sort-order=desc',
            'moving-up' => 'https://stockbeep.com/table-data/stocks-moving-up-now?country=au&time-zone=-600&sort-column=ss5mvol&sort-order=desc',
            'high-volume' => 'https://stockbeep.com/table-data/unusual-volume-stocks?country=au&time-zone=-600&sort-column=ssrvol&sort-order=desc',
            'breakout' => 'https://stockbeep.com/table-data/breakout-stocks?country=au&time-zone=-600&sort-column=sschgp&sort-order=desc',
            'new-high' => 'https://stockbeep.com/table-data/52-week-high-stock-screener?country=au&time-zone=-600&sort-column=sschgp&sort-order=desc',
        ];

        $data = [];
        foreach ($data_sources as $type => $src) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $src);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            $data += $response->data;
        }
 
        $companies = Company::all();

        $matched = [];
        foreach ($data as $src => $ticker) {
            foreach ($companies as $company) {
                // if name and code matches
                if (strpos($company->name, $ticker->ssname) !== false && strip_tags($ticker->sscode) === $company->code_short) {
                    $ticker->sstime = Carbon::parse($ticker->sstime)->today()->diffForHumans();
            
                    $matched[] = array_merge(
                        (array) $company->float,
                        (array) $company->code_short,
                        (array) $ticker
                    );
                }
            }
        }

        return response()->json($matched);
    }
}
