<?php

use App\Notifications\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('/companies', 'CompanyController');
Route::get('/gappers', 'GapUpCompanyController');
Route::get('/momentum', 'MomentumCompanyController');

Route::post('/feedback', function (Request $request) {
    Notification::route('mail', config('feedback.email'))
        ->notify(new Feedback($request->input()));
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
