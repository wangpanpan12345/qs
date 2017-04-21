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

    Route::get('/', 'SearchAdminController@today_new_static');
//    Route::get('/static', 'SearchAdminController@today_new_static');
    Route::get('/show', 'CompanyAdminController@company_by_page');
    Route::get('/detail/{id}', 'CompanyAdminController@company_detail_id');
    Route::get('/company/{name}', 'CompanyAdminController@company_detail_name')->name('company');
    Route::get('/founder/{id}', 'FounderAdminController@founder_detail_id');
    Route::post('/search', 'SearchAdminController@name_k');
//    Route::get('/search', 'SearchAdminController@name_k');
    Route::get('/invest', 'InvestAdminController@index');
    Route::get('/invest/detail/{id}', 'InvestAdminController@show');
    //tags
    Route::get('/tag/time/{time}', 'CompanyAdminController@tag_search');
    Route::get('/tag/add/update', 'TagController@add');
    Route::get('/tag/manage', 'TagController@manage');
    Route::get('/tag/manage_p', 'TagController@manage_p');
    Route::get('/tag/{id}', 'CompanyAdminController@tag_list');
    Route::get('/keynews', 'PhantomjsController@key_news');
    Route::get('/profile', 'UserController@profile');

    //note route
    Route::post('/notes/update', 'NotesController@update');
    Route::post('/notes/create', 'NotesController@create');
    Route::post('/notes/search', 'NotesController@search');
    Route::get('/notes/show', 'NotesController@show');

//    Route::get('/format/raisefund', 'CompanyAdminController@format_raisefund');
//    Route::get('/format/error', 'CompanyAdminController@format_raisefund_re');
    Route::get('/test/', 'CompanyAdminController@update_avatar');
});
Route::get('/qs-admin/keynews/author/{author}', 'PhantomjsController@key_news_author');


//some tool
Route::get('/excel', 'ExcelController@export_order');
//Route::get('mail/send','MailController@send');
Route::get('format/export_avatar', 'CompanyAdminController@export_avatar');
Route::get('format/update/crunch_avatar', 'CompanyAdminController@update_crunch_avatar');
Route::get('format/city/export', 'CompanyAdminController@city');
Route::get('format/invest/date', 'CompanyAdminController@invest_date_format_crunch');
Route::get('format/acquisitions/date', 'CompanyAdminController@acquisitions_date_format_crunch');
Route::get('test', function () {
    dd(Carbon\Carbon::createFromTimestamp(strtotime("21 minutes ago"))->toDateTimeString());
    $t = "AME Cloud Ventures<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tOS Fund<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAlexandria Venture Investments<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t真格基金<br />\r\n \t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t8VC";
    $invest = preg_replace("/(\s+)/", ' ', $t);
    $invest = explode("<br />", trim($invest));//"<br />"作为分隔切成数组
    dd(fopen(storage_path("app/daily_news_failed.txt"), "w+"));
    dd(($invest));
});


//crunchbase in db
Route::get('crunch/indb', 'CrunchBaseController@parase_starter');
Route::get('crunch/bioin', 'CrunchBioController@parase_starter');
Route::get('expert/in', 'CExpertController@parase_starter');

//crunchbase error fix
//Route::get('crunch/tagfix','CrunchBioController@tag_fix');

//wechat count related
Route::get('wechat/count', 'WechatCountController@starter');

//Daily News
Route::post('/dailynews/excerpt', 'PhantomjsController@excerpt');
Route::post('/dailynews/delete', 'PhantomjsController@delete');
Route::post('/dailynews/add', 'PhantomjsController@add');
Route::post('/dailynews/collect', 'PhantomjsController@collect');

Route::get('/dailynews', 'PhantomjsController@daily_list')->middleware("auth");
Route::get('/dailynews_p', 'PhantomjsController@daily_list_p')->middleware("auth");
Route::get('news_source/starter', 'PhantomjsController@starter');
Route::get('/timeline/tag/{tag}', 'TagController@timeline');
Route::get('/dailynews/source/{source}', 'PhantomjsController@source');
Route::get('/dailynews_p/source/{source}', 'PhantomjsController@source_p');
Route::get('/dailynews/key', 'PhantomjsController@daily_list_search');
Route::get('/dailynews/test', 'PhantomjsController@test');
//Daily Funds
Route::get('/dailyfunds', 'DailyFundsController@list_funds');

//Name Cards
Route::get('/namecard/list', 'NameCardController@name_card_list');
Route::get('/namecard', 'NameCardController@namecard');
Route::get('mobile/namecard/up', function () {
    return view('namecard_m');
});
Route::get('/namecard/up', function () {
    return view("namecard");
});
Route::get('/getMsgJson', 'WechatCollectController@getMsgJson');
Route::post('/getMsgJson', 'WechatCollectController@getMsgJson');

Route::get('/getWxHis', 'WechatCollectController@getWxHis');
Route::post('/getWxHis', 'WechatCollectController@getWxHis');

//knowledge
Route::post('/knowledge/add', 'KnowledgeController@add')->middleware("auth");
Route::post('/knowledge/update', 'KnowledgeController@update')->middleware("auth");
Route::post('/knowledge/delete', 'KnowledgeController@delete')->middleware("auth");

//event
Route::get('/event', function () {
    event(new \App\Events\DailyNewsUpdate(3, 1));
    return "hello world";
});
Route::get('/socket', function () {
    return view("socket");
});
Route::get('/dailynews/static/notify', 'PhantomjsController@static_notipy');
Route::post('/dailynews/edit/notify', 'PhantomjsController@edit_notify');
Route::get('/timeline/tag/{tag}/package/{pack}', 'TagController@package');


Route::get('/dailyfunds/indb', 'DailyFundsController@indb');


Route::get('/jobs/create/{table}/{subject}', 'JobsController@create')->middleware("auth");
Route::get('/jobs/admin', 'JobsController@index')->middleware("auth");

Route::get('/coreseek', 'ExcelController@coreseek');
Route::get('/coreseek/xml', 'ExcelController@coreseek_xml');

Route::get('/person/export', 'ExcelController@export_person_list');

Route::get('/haha', 'CompanyAdminController@index_all');

Route::get('/format_company', 'CompanyAdminController@format_company');

Route::get('/format_person', 'CompanyAdminController@format_person');

Route::get('/export_dict', 'ExcelController@my_dict');







