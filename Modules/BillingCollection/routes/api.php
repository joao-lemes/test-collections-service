<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\BillingCollection\Http\Controllers\BillingCollectionController;

Route::prefix('collection')->controller(BillingCollectionController::class)->name('collection.')->group(function () {
    Route::post('/', 'storeAction')->name('store');
});
