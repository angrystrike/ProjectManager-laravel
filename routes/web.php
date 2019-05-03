<?php

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/regex', 'RegexController@index');

Route::middleware(['auth'])->group(function () {

    Route::delete('cancelFriendRequest', 'UsersController@cancelFriendRequest')->name('users.cancelFriendRequest');
    Route::post('denyFriend', 'UsersController@denyFriend')->name('users.denyFriend');
    Route::delete('deleteFriend', 'UsersController@deleteFriend')->name('users.deleteFriend');
    Route::post('addToFriends', 'UsersController@addToFriends')->name('users.addToFriends');
    Route::post('acceptFriend', 'UsersController@acceptFriend')->name('users.acceptFriend');
    Route::get('friends', 'UsersController@friendListInfo')->name('users.friends');

    Route::put('comments/update', 'CommentsController@update')->name('comments.update');
    Route::resource('comments', 'CommentsController')->only(['store', 'destroy']);

    Route::delete('threads/{thread_id}', 'MessagesController@deleteThread')->name('threads.delete');
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    });
});

Route::group(['middleware' => ['auth', 'verified']], function() {

    Route::resource('companies', 'CompaniesController')->except(['index', 'show']);

    Route::get('projects/create/{company_id?}', 'ProjectsController@create');
    Route::post('projects/addUser', 'ProjectsController@addUser')->name('projects.addUser');
    Route::delete('projects/member/{project_id}/{user_id}', 'ProjectsController@deleteMember')->name('projects.deleteMember');
    Route::resource('projects', 'ProjectsController')->except(['index', 'show']);

    Route::get('tasks/create/{project_id?}', 'TasksController@create');
    Route::post('tasks/addUser', 'TasksController@addUser')->name('tasks.addUser');
    Route::delete('tasks/member/{task_id}/{user_id}', 'TasksController@deleteMember')->name('tasks.deleteMember');
    Route::resource('tasks', 'TasksController')->except(['index', 'show']);
});

Route::group(['middleware' => ['auth', 'verified', 'admin']], function() {
    Route::get('companies/all', 'CompaniesController@all')->name('admin.companies');
    Route::get('projects/all', 'ProjectsController@all')->name('admin.projects');
    Route::get('tasks/all', 'TasksController@all')->name('admin.tasks');
    Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::get('users/all', 'UsersController@all')->name('admin.users');
    Route::get('comments/all', 'CommentsController@all')->name('admin.comments');
});

Route::get('search', 'SearchController@search')->name('search');

Route::get('companies', 'CompaniesController@index')->name('companies.index');
Route::get('companies/{company}', 'CompaniesController@show')->name('companies.show');

Route::get('projects', 'ProjectsController@index')->name('projects.index');
Route::get('projects/{project}', 'ProjectsController@show')->name('projects.show');

Route::get('tasks', 'TasksController@index')->name('tasks.index');
Route::get('tasks/{task}', 'TasksController@show')->name('tasks.show');

Route::get('users/{user}', 'UsersController@show')->name('users.show');


