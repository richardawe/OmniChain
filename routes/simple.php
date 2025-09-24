<?php

// Ultra-simple routes that don't depend on Laravel features
Route::get('/simple', function () {
    return 'Simple route working!';
});

Route::get('/basic', function () {
    return response()->json(['status' => 'ok', 'time' => time()]);
});
