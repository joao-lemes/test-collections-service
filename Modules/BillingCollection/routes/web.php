<?php

use Illuminate\Support\Facades\Route;
use Modules\BillingCollection\Http\Controllers\BillingCollectionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('billingcollections', BillingCollectionController::class)->names('billingcollection');
});
