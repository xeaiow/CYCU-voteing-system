<?php

// Route::get('/', 'VoteController@activity_lists');
// Route::get('/activity/{id}', 'VoteController@activity');
// Route::get('/activity/{id}/info', 'VoteController@activity_content');

Route::get('/activity/get', 'VoteController@get_activity');

Route::get('/finished_activity/get', 'VoteController@get_finished_activity');

Route::get('/activity/info/{id}', 'VoteController@get_activity_info');

Route::get('/groups/get', 'VoteController@get_groups');

Route::get('/groups/info/{id}', 'VoteController@get_groups_info');

Route::get('/groups/top/{id}', 'VoteController@get_groups_top');

Route::get('/group/info/{id}', 'VoteController@get_group_info');

Route::get('/loginitouch', 'SystemController@login_touch');

Route::post('/login/handle', 'SystemController@login_handle');

Route::get('/login/status', 'SystemController@login_status');

Route::post('/login/save', 'SystemController@login_save_handle');

Route::post('/activity/create', 'PineappleController@activity_create');


Route::group(['prefix' => '/', 'middleware' => 'isLoginMiddleware'], function () {

    Route::get('/voting/{id}', 'VoteController@voting_handle');
    Route::get('/logout', 'SystemController@logout_handle');
});

// Route::get('/login', 'SystemController@login');

// Route::get('/ss', function () {
//     return view('hello');
// });


Route::any('{all}', function () {
    return view('welcome');
})->where(['all' => '.*']);
