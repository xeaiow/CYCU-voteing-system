<?php

// Route::get('/', 'VoteController@activity_lists');
// Route::get('/activity/{id}', 'VoteController@activity');
// Route::get('/activity/{id}/info', 'VoteController@activity_content');

Route::get('/activity/get', 'VoteController@get_activity');

Route::get('/activity/info/{id}', 'VoteController@get_activity_info');

Route::get('/groups/get', 'VoteController@get_groups');

Route::get('/groups/info/{id}', 'VoteController@get_groups_info');

Route::get('/group/info/{id}', 'VoteController@get_group_info');

// Route::get('/login', 'SystemController@login');

// Route::get('/ss', function () {
//     return view('hello');
// });


Route::any('{all}', function () {
    return view('welcome');
})->where(['all' => '.*']);
