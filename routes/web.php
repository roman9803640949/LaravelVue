<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//

Route::get('/', [HomeController::class, 'index']) ;

Route::prefix('app')->group(function () {
    Route::prefix('admin')->group(function(){
        Route::apiResource('tags', TagController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::get('roles', [PermissionController::class,'role_list']);
        Route::post('roles', [PermissionController::class,'role_store']);
        Route::get('permissions', [PermissionController::class,'permission_list']);
        Route::post('permissions', [PermissionController::class,'permission_store']);
        Route::post('rolepermissions/{role}', [PermissionController::class,'role_has_permissions']);
        Route::post('assignuserrole/{role}', [PermissionController::class,'assign_user_to_role']);
    });
    Auth::routes();
});


Route::get('{slug}', [HomeController::class, 'index'])->where('slug','.*');




