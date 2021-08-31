<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\EmployeeController;
  
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
  
// Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::group([
    'prefix' => 'v1/admin',
    'as' => 'api.',
    'middleware' => ['auth:api', 'role:Administrator']
], function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
});