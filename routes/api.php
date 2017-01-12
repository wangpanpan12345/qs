<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {

    return $request->user();
})->middleware('auth:api');

Route::post('/dy_search', 'SearchAdminController@insert_search');
Route::post('/add_company', 'CompanyAdminController@add');
Route::post('/update_company', 'CompanyAdminController@update');
Route::post('/company/name/dy', 'SearchAdminController@name_k_dy');
Route::post('/person/name/dy', 'SearchAdminController@name_k_p_dy');

Route::post('/dailynews/company','PhantomjsController@daily_news_company');
Route::post('/dailynews/person','PhantomjsController@daily_news_person');
Route::get('/dailynews/tags/','TagController@tag_search');
Route::post('/dailynews/tags/','TagController@tag_search');
Route::get('/dailynews/company/date','PhantomjsController@daily_news_date');
Route::post('/dailynews/tags/update','PhantomjsController@daily_news_tags_update');
Route::post('/tags/add','TagController@add');

//Route::post('/dailynews/excerpt','PhantomjsController@excerpt');
//
Route::post('/namecard', 'NameCardController@namecard');
Route::post('/namecard/upload','UploadController@imgUpload');
Route::post('/namecard/add','NameCardController@add');
Route::get('/auth',function(){

})->middleware('auth:api');

