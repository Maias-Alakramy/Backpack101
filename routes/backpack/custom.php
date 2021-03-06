<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('class-room', 'ClassRoomCrudController');
    Route::crud('student', 'StudentCrudController');
    Route::get('charts/student-per-class', 'Charts\StudentPerClassChartController@response')->name('charts.student-per-class.index');
}); // this should be the absolute last line of this file