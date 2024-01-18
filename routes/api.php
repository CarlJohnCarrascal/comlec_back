<?php

use App\Http\Controllers\api\LocationController;
use App\Http\Controllers\Api\VotersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("voters", VotersController::class);
Route::get("total-voters", [VotersController::class, 'total_voters']);
Route::post("voters/import", [VotersController::class, 'import']);
Route::post("voters/checkimport", [VotersController::class, 'checkimport']);
Route::put("voters/mark/{voter}", [VotersController::class, 'mark']);
Route::post("voters/mark-selected", [VotersController::class, 'markselected']);
Route::post("voters/bar-chart", [VotersController::class, 'getbarchart']);
Route::get("bar-chart-total", [VotersController::class, 'getbarcharttotal']);


Route::get('cities', [LocationController::class, 'get_cities']);
Route::get('municipalities', [LocationController::class, 'get_municipalities']);
Route::get('barangay', [LocationController::class, 'get_barangays']);
Route::get('houses', [LocationController::class, 'get_houses']);

Route::get('house-member', [VotersController::class, 'get_house_member']);
Route::get('mark-as-head/{voter}', [VotersController::class, 'mark_as_head']);

