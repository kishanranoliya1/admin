<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::post('/brahma-muhurta', [Api\MuhuratController::class, 'getBrahmapMuhurta'])->middleware(['throttle:indian-api']);
Route::post('/abhijit-muhurta', [Api\MuhuratController::class, 'getAbhijitMuhurta'])->middleware(['throttle:indian-api']);
Route::post('/godhuli-muhurta', [Api\MuhuratController::class, 'getGodhuliMuhurta'])->middleware(['throttle:indian-api']);
Route::post('/pratah-sandhya', [Api\MuhuratController::class, 'getPratahSandhya'])->middleware(['throttle:indian-api']);
Route::post('/sayahana-sandhya', [Api\MuhuratController::class, 'getSayahanaSandhya'])->middleware(['throttle:indian-api']);
Route::post('/nishita-muhurta', [Api\MuhuratController::class, 'getNishitaMuhurta'])->middleware(['throttle:indian-api']);
Route::post('/vijay-muhurta', [Api\MuhuratController::class, 'getVijayMuhurta'])->middleware(['throttle:indian-api']);
Route::post('/tri-pushkara-yoga', [Api\MuhuratController::class, 'getTriPushkaraYoga'])->middleware(['throttle:indian-api']);
Route::post('/amrit-siddhi-yoga', [Api\MuhuratController::class, 'getAmritSiddhiYoga'])->middleware(['throttle:indian-api']);
Route::post('/amrit-kalam-yoga', [Api\MuhuratController::class, 'getAmritKalamYoga'])->middleware(['throttle:indian-api']);
Route::post('/siddhi-yoga', [Api\MuhuratController::class, 'getSiddhiYoga'])->middleware(['throttle:indian-api']);
Route::post('/chandramasa', [Api\MuhuratController::class, 'getChandramasa'])->middleware(['throttle:indian-api']);
Route::post('/nakshatra', [Api\NakshatraController::class, 'getNakshatra']);
Route::post('/auspicious-time', [Api\MuhuratController::class, 'getAuspiciousTime'])->middleware(['throttle:indian-api']);

Route::post('/tithi', [Api\MuhuratController::class, 'getTithi']);
