<?php

use Illuminate\Support\Facades\Route;

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
define('PAGINATION_COUNT',10);

Route::group(['namespace'=>'Admin','middleware' => 'auth:admin'], function() {
    Route::get('/','DashboardController@index') -> name('admin.dashboard');
    ################### Begin languages route ##########################
    Route::group(['prefix'=>'languages'],function(){
        //ده روت عرض اللغات عملنا روت واحد عشان انا بعض بس
        Route::get('/','LanguagesController@index')->name('admin.languages');
        //ده روتين اضافه اللغات وحفظها ف الداتا بيز
        Route::get('create','LanguagesController@create')->name('admin.languages.create');
        Route::post('store','LanguagesController@store')->name('admin.languages.store');

        Route::get('edit/{id}','LanguagesController@edit')->name('admin.languages.edit');
        Route::post('update/{id}','LanguagesController@update')->name('admin.languages.update');

        Route::get('delete/{id}','LanguagesController@destroy')->name('admin.languages.delete');
    });




    ################### End languages route ##########################

    ################### Begin MainCategories route ##########################

    Route::group(['prefix'=>'main_categories'],function(){
        Route::get('/','MainCategoryController@index')->name('admin.maincategories');


        Route::get('create','MainCategoryController@create')->name('admin.maincategories.create');
        Route::post('store','MainCategoryController@store')->name('admin.maincategories.store');


        Route::get('edit/{id}','MainCategoryController@edit')->name('admin.maincategories.edit');
        Route::post('update/{id}','MainCategoryController@update')->name('admin.maincategories.update');


        Route::get('delete/{id}','MainCategoryController@destroy')->name('admin.maincategories.delete');
    });




    ################### End MainCategories route ##########################


    ################### Begin Vendors route ##########################

    Route::group(['prefix'=>'vendors'],function(){
        Route::get('/','VendorsController@index')->name('admin.vendors');


        Route::get('create','VendorsController@create')->name('admin.vendors.create');
        Route::post('store','VendorsController@store')->name('admin.vendors.store');


        Route::get('edit/{id}','VendorsController@edit')->name('admin.vendors.edit');
        Route::post('update/{id}','VendorsController@update')->name('admin.vendors.update');


        Route::get('delete/{id}','VendorsController@destroy')->name('admin.vendors.delete');
    });




    ################### End Vendors route ##########################

});



Route::group(['namespace'=>'Admin','middleware' => 'guest:admin'], function(){
     Route::get('login' ,'LoginController@getLogin')-> name('login');
     Route::post('login' ,'LoginController@login') -> name('admin.login');
});

Route::get('test',function(){
    return get_languages();
});
