<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
//use App\Laravel_test;


Route::get('/home', 'HomeController@index');

//Auth::routes();
Route::get('/', function () {
    return view("welcome");
})->middleware('auth');

//auth
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['middleware' => 'auth', 'prefix' => 'qs-admin'], function () {
//Route::group(['prefix' => 'qs-admin'], function () {

    Route::get('/', 'CompanyAdminController@index');
    Route::get('/show', 'CompanyAdminController@company_by_page');
    Route::get('/detail/{id}', 'CompanyAdminController@company_detail_id');
    Route::get('/founder/{id}', 'FounderAdminController@founder_detail_id');
    Route::post('/search', 'SearchAdminController@name_k');
    Route::get('/invest', 'InvestAdminController@index');
    Route::get('/invest/detail/{id}', 'InvestAdminController@show');
    //tags
    Route::get('/tag/time/{time}', 'CompanyAdminController@tag_search');
    Route::get('/tag/add/update', 'TagController@add');
    Route::get('/tag/manage', 'TagController@manage');
    Route::get('/tag/{id}', 'CompanyAdminController@tag_list');
    Route::get('/keynews', 'PhantomjsController@key_news');

//    Route::get('/format/raisefund', 'CompanyAdminController@format_raisefund');
//    Route::get('/format/error', 'CompanyAdminController@format_raisefund_re');
//    Route::get('/test/', 'CompanyAdminController@update_avatar');
});


//some tool
//Route::get('/excel', 'ExcelController@export_invest');
//Route::get('mail/send','MailController@send');
Route::get('format/export_avatar','CompanyAdminController@export_avatar');
Route::get('format/update/crunch_avatar','CompanyAdminController@update_crunch_avatar');
Route::get('format/city/export','CompanyAdminController@city');
Route::get('test',function(){
    $t = "AME Cloud Ventures<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tOS Fund<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAlexandria Venture Investments<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t真格基金<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t8VC";
    $invest = preg_replace("/(\s+)/", ' ', $t);
    $invest = explode("<br />", trim($invest));//"<br />"作为分隔切成数组

    dd(($invest));
});


//crunchbase in db
Route::get('crunch/indb', 'CrunchBaseController@parase_starter');
Route::get('crunch/bioin', 'CrunchBioController@parase_starter');

//crunchbase error fix
//Route::get('crunch/tagfix','CrunchBioController@tag_fix');

//wechat count related
Route::get('wechat/count', 'WechatCountController@starter');

//Daily News
Route::post('/dailynews/excerpt','PhantomjsController@excerpt');
Route::post('/dailynews/add','PhantomjsController@add');
Route::get('/dailynews', 'PhantomjsController@daily_list')->middleware("auth");
Route::get('news_source/starter', 'PhantomjsController@starter');
Route::get('/timeline/tag/{tag}','TagController@timeline');
Route::get('/dailynews/source/{source}', 'PhantomjsController@source');
Route::get('/dailynews/key', 'PhantomjsController@daily_list_search');
Route::get('/dailynews/test', 'PhantomjsController@test');
//Daily Funds
Route::get('/dailyfunds', 'DailyFundsController@list_funds');

//Name Cards
Route::get('/namecard/list', 'NameCardController@name_card_list');
Route::get('/namecard', 'NameCardController@namecard');
Route::get('mobile/namecard/up',function(){
    return view('namecard_m');
});
Route::get('/namecard/up', function(){
    return view("namecard");
});


