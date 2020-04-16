<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use KubAT\PhpSimple\HtmlDomParser;

class UpdateCompanyFloat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:companies:float';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update company floats based on Yahoo Finance.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Company::where('updated_at', '>=', Carbon::now()->subWeeks(2))
            ->whereNull('float')
            ->get();

        $bar = $this->output->createProgressBar(count($companies));

        foreach ($companies as $company) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://au.finance.yahoo.com/quote/$company->code/key-statistics");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $dom = HtmlDomParser::str_get_html($response);
            
            if (empty($dom)) {
                $this->error("Unable to get for $company->code");
                $company->float = '';
                $company->save();
                continue;
            }

            $spans = $dom->find('span');

            $float = '';
            foreach ($spans as $span) {
                if ($span->innertext == 'Float') {
                    $float = $span->parent->parent->children[1]->innertext;
                    if (strpos($float, 'span') !== false) {
                        $float = '';
                    }
                } 
            }

            $company->float = $float;
            $company->save();

            $bar->advance();
        }

        $bar->finish();
    }
}
