<?php

namespace App\Http\Controllers\API;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GapperCompanyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $companies = Company::all()
            ->whereNotNull('float')
            ->where('float', '!=', '')
            ->whereBetween('float_integer', [0, 50000000])
            ->sortBy('float_integer')
        ;

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
                    $ticker->sstime = Carbon::parse($ticker->sstime)->today()->diffForHumans();
                    
                    $matched[] = array_merge((array) $company->float, (array) $company->code_short,(array) $ticker);
                }
            }
        }

        foreach ($matched as $key => &$company) {
            unset($company['sscode'],$company['xindex'],$company['ssarrow'],$company['cellClasses']);

            $price = (float) $company['sslast'];
            if ($price <= 0.5) {
                unset($matched[$key]);                
            }

            $cap = $company['sscap'];
            $vol = $company['ssvol'];

            $letters = [
                'k' => 1000,
                'M' => 1000000,
                'B' => 1000000000,
            ];

            foreach ($letters as $letter => $multiple) {
                if (strpos($cap, $letter) !== false) {
                    str_replace($letter, "", $cap);
                    $cap = (float) $cap * $multiple;
                    if ($cap > 200000000) {
                        unset($matched[$key]);                
                    }
                }
                if (strpos($vol, $letter) !== false) {
                    str_replace($letter, "", $vol);
                    $vol = (float) $vol * $multiple;
                    if ($vol < 100000) {
                        unset($matched[$key]);                
                    }
                }
            }
        }
        unset($company);
        $matched = array_values($matched);

        return response()->json($matched);
    }
}
