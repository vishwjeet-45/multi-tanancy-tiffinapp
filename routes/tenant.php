<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    'auth',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->name('tenant.')->group(function () {
    Route::get('/', function () {
        dd(tenant()->toArray());
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });

    Route::resource('users',UserController::class);



});




Route::get('/meals', fn () => inertia('Meals/Index'));

require __DIR__.'/tenant-auth.php';

