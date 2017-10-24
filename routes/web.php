<?php

Route::get('/', 'VoteController@index');

Route::get('/activity', 'VoteController@activity_lists');
Route::get('/activity/{id}', 'VoteController@activity');
Route::get('/activity/{id}/info', 'VoteController@activity_content');

Route::get('/login', 'SystemController@login');

Route::get('/ss', function () {
    return view('hello');
});
