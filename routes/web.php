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
Route::group(['middleware' => ['ip_allowed']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('salary/import', 'HomeController@import')->name('salary.import');
    Route::get('/history', 'HomeController@history')->name('history');
    Route::get('/salary/details', 'HomeController@salaryDetails')->name('salary.details');
    Route::get('/user', 'UserController@index')->name('user');
    Route::get('/user/disabled', 'UserController@disabled')->name('user.disabled');
    Route::get('/user/enabled', 'UserController@enabled')->name('user.enabled');
    Route::get('/user/remove_openid', 'UserController@removeOpenid')->name('user.remove.openid');
    Route::get('/user/edit', 'UserController@edit')->name('user.edit');
    Route::post('/user/edit', 'UserController@store')->name('user.store');
    Route::get('/user/change_pwd', 'UserController@changePwd')->name('user.change.pwd');
    Route::post('/user/change_pwd', 'UserController@storePwd')->name('user.store.pwd');
    Route::get('/receipt', 'ReceiptController@index')->name('receipt');
    Route::get('/salary/delete', 'HomeController@salaryDelete')->name('salary.delete');
    Route::post('/salary/delete', 'HomeController@salaryDoDelete')->name('salary.do.delete');
    Route::get('/settings', 'HomeController@settings')->name('settings');
    Route::post('/settings/ip/store', 'HomeController@storeIp')->name('settings.store.ip');
    Route::post('/settings/maintain/store', 'HomeController@storeMaintain')->name('settings.store.maintain');
});

Route::any('/wechat/login', 'WechatController@login');
Route::post('/wechat/bind_user', 'WechatController@bindUser');
Route::any('/wechat/get_salary', 'WechatController@getSalary');
Route::any('/wechat/salary/details', 'WechatController@salaryDetails');
Route::any('/wechat/receipt/add', 'WechatController@addReceipt');
