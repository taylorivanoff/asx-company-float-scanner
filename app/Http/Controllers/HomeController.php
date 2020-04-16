<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use KubAT\PhpSimple\HtmlDomParser;
use League\Csv\Reader;
use League\Csv\Statement;
use Spatie\SimpleExcel\SimpleExcelReader;

class HomeController extends Controller
{
    public function index()
    {
    	return view('welcome');
    }
}
