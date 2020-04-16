<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class ConvertCompanyFloat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:companies:float';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $companies = Company::where('float', '!=', "NULL")
            ->get();
        
        $bar = $this->output->createProgressBar(count($companies));


        foreach ($companies as $company) {
            $letters = [
                'k' => 1000,
                'M' => 1000000,
                'B' => 1000000000,
            ];

            foreach ($letters as $letter => $multiple) {
                if (strpos($company->float, $letter) !== false) {
                    $float = $company->float;
                    str_replace($letter, "", $float);
                    $company->float_integer = (int) $float * $multiple;
                    $company->save();
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
