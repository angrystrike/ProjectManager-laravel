<?php

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/regex', 'RegexController@index');

Route::get('companies/all', 'CompaniesController@all')->name('companies.all');
Route::resource('companies', 'CompaniesController');

Route::get('projects/all', 'ProjectsController@all')->name('projects.all');
Route::get('projects/create/{company_id?}', 'ProjectsController@create');
Route::post('projects/adduser', 'ProjectsController@addUser')->name('projects.adduser');
Route::delete('projects/member/{project_id}/{user_id}', 'ProjectsController@deleteMember')->name('projects.deleteMember');
Route::resource('projects', 'ProjectsController');

Route::resource('roles', 'RolesController');

Route::get('tasks/all', 'TasksController@all')->name('tasks.all');
Route::get('tasks/create/{project_id?}', 'TasksController@create');
Route::post('tasks/adduser', 'TasksController@adduser')->name('tasks.adduser');
Route::resource('tasks', 'TasksController');
Route::resource('users', 'UsersController');
Route::resource('comments', 'CommentsController');

