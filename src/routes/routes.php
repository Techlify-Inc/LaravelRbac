<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function()
{
    Route::resource("roles", "\TechlifyInc\LaravelRbac\Controllers\RoleController");
    Route::patch("roles/{role}/permissions/{permission}/add", "\TechlifyInc\LaravelRbac\Controllers\RoleController@addPermission");
    Route::patch("roles/{role}/permissions/{permission}/remove", "\TechlifyInc\LaravelRbac\Controllers\RoleController@removePermission");

    Route::resource("permissions", "\TechlifyInc\LaravelRbac\Controllers\PermissionController");
});
