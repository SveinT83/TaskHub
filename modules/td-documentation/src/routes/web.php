<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdDocumentation\Http\Livewire\DocumentationList;
use tronderdata\TdDocumentation\Http\Livewire\DocumentationView;

Route::middleware(['web'])->group(function () {
    Route::get('/documentation', DocumentationList::class)->name('documentation.index');
    Route::get('/documentation/{id}', DocumentationView::class)->name('documentation.view');
});
