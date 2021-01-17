<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('salary/import', 'HomeController@import')->name('salary.import');
Route::get('/history', 'HomeController@history')->name('history');
Route::get('/salary/details', 'HomeController@salaryDetails')->name('salary.details');
Route::get('/user', 'UserController@index')->name('user');
Route::get('/user/disabled', 'UserController@disabled')->name('user.disabled');
Route::get('/user/enabled', 'UserController@enabled')->name('user.enabled');

Route::any('/wechat/login', 'WechatController@login');
Route::post('/wechat/bind_user', 'WechatController@bindUser');
Route::any('/wechat/get_salary', 'WechatController@getSalary');
Route::any('/wechat/salary/details', 'WechatController@salaryDetails');
