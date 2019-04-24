<?php

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/regex', 'RegexController@index');

Route::middleware(['auth'])->group(function () {

    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    });

    Route::get('search', 'SearchController@search')->name('search');

    Route::middleware(['admin'])->group( function () {
        Route::get('companies/all', 'CompaniesController@all')->name('admin.companies');
    });
    Route::resource('companies', 'CompaniesController');

    Route::middleware(['admin'])->group( function () {
        Route::get('projects/all', 'ProjectsController@all')->name('admin.projects');
    });
    Route::get('projects/create/{company_id?}', 'ProjectsController@create');
    Route::post('projects/addUser', 'ProjectsController@addUser')->name('projects.addUser');
    Route::delete('projects/member/{project_id}/{user_id}', 'ProjectsController@deleteMember')->name('projects.deleteMember');
    Route::resource('projects', 'ProjectsController');

    Route::middleware(['admin'])->group( function () {
        Route::get('tasks/all', 'TasksController@all')->name('admin.tasks');
    });

    Route::get('tasks/create/{project_id?}', 'TasksController@create');
    Route::post('tasks/addUser', 'TasksController@addUser')->name('tasks.addUser');
    Route::delete('tasks/member/{task_id}/{user_id}', 'TasksController@deleteMember')->name('tasks.deleteMember');
    Route::resource('tasks', 'TasksController');

    Route::middleware(['admin'])->group( function () {
        Route::get('users/all', 'UsersController@all')->name('admin.users');
    });

    Route::resource('users', 'UsersController');

    Route::middleware(['admin'])->group( function () {
        Route::get('comments/all', 'CommentsController@all')->name('admin.comments');
    });
    Route::put('comments/update', 'CommentsController@update')->name('comments.update');
    Route::resource('comments', 'CommentsController');
});

