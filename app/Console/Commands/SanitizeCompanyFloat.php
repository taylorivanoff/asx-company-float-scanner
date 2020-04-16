<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class SanitizeCompanyFloat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanitize:companies:float';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove company floats that contain <span>.';

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
        // remove spans
        $companies = Company::where('float', 'like', "%span%")
            ->get();

        foreach ($companies as $company) {
            $company->float = '';
            $company->save();

            $this->info("Sanitised $company->code");
        }
        // convert float strings to proper integers
    }
}
