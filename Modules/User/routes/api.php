<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::prefix('user')->controller(UserController::class)->name('user.')->group(function () {
    Route::get('/', 'list')->name('list');
    Route::get('/{id}/collection-value', 'getWithCollectionValue')->name('getWithCollectionValue');
    Route::post('/', 'storeAction')->name('store');
});
