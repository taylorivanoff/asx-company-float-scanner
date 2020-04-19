<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;
use League\Csv\Statement;

class UpdateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update companies based on ASX nightly updated CSV';

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
        $response = Http::get('https://www.asx.com.au/asx/research/ASXListedCompanies.csv');

        $csv = Reader::createFromString($response->body());
        $csv->setHeaderOffset(2);
        
        $tickers = (new Statement())
            ->offset(1)->process($csv);

        $bar = $this->output->createProgressBar(count($tickers));
        $bar->start();

        foreach ($tickers as $ticker) {
            $company = Company::updateOrCreate([
                'name'     => $ticker['Company name'],
                'code'     => $ticker['ASX code'] . '.AX',
                'industry' => $ticker['GICS industry group'],
            ]);

            $bar->advance();

        }   

        $bar->finish();
    }
}
