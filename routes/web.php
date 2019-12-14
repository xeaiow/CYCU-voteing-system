<?php

                        /* Vote */

Route::get('/activity/get', 'VoteController@get_activity');

Route::get('/finished_activity/get', 'VoteController@get_finished_activity');

Route::get('/activity/info/{id}', 'VoteController@get_activity_info');

Route::get('/groups/get', 'VoteController@get_groups');

Route::get('/groups/info/{id}', 'VoteController@get_groups_info');

Route::get('/groups/top/{id}', 'VoteController@get_groups_top');

Route::post('/group/info', 'VoteController@get_group_info');

                        /* System */

Route::post('/api/loginitouch', 'SystemController@login_touch');
Route::post('/api/logindemo', 'SystemController@login_demo');

Route::post('/api/voting', 'SystemController@voting');


                        /* Pineapple */
Route::post('/activity/create', 'PineappleController@activity_create');

Route::post('/image/upload', 'PineappleController@upload_image');

Route::post('/pineapple/login/handle', 'PineappleController@login_handle');

Route::get('/pineapple/login/status', 'PineappleController@login_status');

Route::group(['prefix' => '/', 'middleware' => 'ManagerisLoginMiddleware'], function () {
    
        Route::post('/pineapple/groups/create', 'PineappleController@finished_groups_create');
        Route::post('/pineapple/activity/update/set', 'PineappleController@set_activity');
        Route::post('/pineapple/groups/update/set', 'PineappleController@set_groups');
        Route::get('/pineapple/activity/create/{id}/get', 'PineappleController@get_activity_create');
        Route::get('/pineapple/activity/{id}/get', 'PineappleController@get_activity');
        Route::get('/pineapple/groups/{id}/get', 'PineappleController@get_groups');
        Route::get('/pineapple/activity/groups/{id}/get', 'PineappleController@get_activity_groups');
        Route::get('/pineapple/logout', 'PineappleController@logout_handle');
});

Route::any('{all}', function () {
    return view('welcome');
})->where(['all' => '.*']);
