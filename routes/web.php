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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::put('updateavail/{id}', 'ItemController@updateavail')->name('updateavail');
Route::put('declineitem/{id}', 'BorroweditemController@declineitem')->name('declineitem');
Route::put('approveitem/{id}', 'BorroweditemController@approveitem')->name('approveitem');
Route::get('pendings', 'BorroweditemController@pendingitems')->name('pendingitems');
Route::put('accountsettings/{id}', 'API\UserController@updateSettings')->name('updateSettings');
Route::get('accountsettings', 'API\UserController@accountsettings')->name('accountsettings');
Route::get('stockin/item/{id}', 'StockinController@itemquantity')->name('itemquantity');
Route::resource('stockin', 'StockinController');
Route::get('borrowitem/userdetails', 'BorroweditemController@userindexdetails')->name('userindexdetails');
Route::get('borrowitem/admindetails', 'BorroweditemController@adminitemsindex')->name('adminitemsindex');
Route::get('borrowitems/admin', 'BorroweditemController@adminindextwo')->name('adminindextwo');
Route::get('borrowitem/admin', 'BorroweditemController@adminindex')->name('adminindex');

Route::get('pendingitems/user', 'BorroweditemController@userpendingitems')->name('userpendingitems');
Route::resource('borrowitem', 'BorroweditemController');

Route::resource('tempitem', 'TempitemController');
Route::get('item/tobuy', 'ItemController@toBuy')->name('toBuy');
Route::resource('item', 'ItemController');
Route::resource('category', 'CategoryController');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+?)');
