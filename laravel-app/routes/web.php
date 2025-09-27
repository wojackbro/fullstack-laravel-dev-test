<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductList;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/view/products", ProductList::class)->name("products.view");
