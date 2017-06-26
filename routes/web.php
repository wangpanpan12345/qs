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
    abort(404);
    return;
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
    Route::post('/show', 'CompanyAdminController@company_s_page');
    Route::get('/detail/{id}', 'CompanyAdminController@company_detail_id');
    Route::post('/company/man_score', 'CompanyAdminController@man_score');
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
    Route::post('/keynews/shared', 'PhantomjsController@key_news_shared');
    Route::get('/profile', 'UserController@profile');
    Route::get('/score', 'UserController@score_list');

    //note route
    Route::post('/notes/update', 'NotesController@update');
    Route::post('/notes/create', 'NotesController@create');
    Route::post('/notes/search', 'NotesController@search');
    Route::post('/notes/author', 'NotesController@author_source');
    Route::post('/notes/delete', 'NotesController@delete');
    Route::get('/notes/show', 'NotesController@show');

    //konwledge
    Route::get('/knowledge/{name}', 'KnowledgeController@show');

//    Route::get('/format/raisefund', 'CompanyAdminController@format_raisefund');
//    Route::get('/format/error', 'CompanyAdminController@format_raisefund_re');
    Route::get('/test/', 'CompanyAdminController@update_avatar');
    Route::get('/today_news', 'PhantomjsController@today_news');
    //
    Route::post('/topic/create', 'JobTopicController@create');
    Route::post('/topic/headline-create', 'JobTopicController@headline_create');
    Route::post('/topic/headline-get', 'JobTopicController@headline_get');
    Route::post('/topic/headline-edit', 'JobTopicController@headline_edit');
    Route::post('/topic/touser', 'JobTopicController@touser');
    Route::post('/topic/status', 'JobTopicController@status');


    //filter
    Route::get('/filter/fullname', 'FilterController@fullname');
    Route::get('/filter/scale', 'FilterController@scale');
    Route::get('/filter/get_scale', 'FilterController@get_scale');
    Route::get('/filter/get_similar', 'FilterController@get_similar');
    Route::get('/filter/current_fullname', 'FilterController@current_fullname');
    Route::get('/filter/distinct_fullname', 'FilterController@distinct_fullname');
    Route::get('/filter/get_investor', 'FilterController@get_investor');
    Route::get('/filter/get_crawl_investor', 'FilterController@get_crawl_investor');
    Route::get('/filter/company_f_invest', 'FilterController@get_company_from_invest');
    Route::get('/filter/invest_f_invest', 'FilterController@get_invest_from_invest');
    Route::get('/filter/hospital_meta', 'FilterController@hospital_meta');
    Route::get('/filter/hospital_meta_format', 'FilterController@hospital_meta_format');





});
Route::get('/qs-admin/keynews/author/{author}', 'PhantomjsController@key_news_author');


//some tool
Route::get('/excel', 'ExcelController@export_order');
Route::get('/yiyuan', 'ExcelController@export_invest');
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
Route::post('baike/indb', 'BaikeController@start_job');
Route::get('wiki/{name}', 'BaikeController@wiki');
Route::get('baike', function () {
    return view("baike");
});

//crunchbase error fix
//Route::get('crunch/tagfix','CrunchBioController@tag_fix');

//wechat count related
//Route::get('wechat/count', 'WechatCountController@starter');

//Daily News
Route::post('/dailynews/get_one', 'PhantomjsController@get_one');
Route::post('/dailynews/excerpt', 'PhantomjsController@excerpt');
Route::post('/dailynews/delete', 'PhantomjsController@delete');
Route::post('/dailynews/add', 'PhantomjsController@add');
Route::post('/dailynews/collect', 'PhantomjsController@collect');
Route::post('/dailynews/cancel_collect', 'PhantomjsController@cancel_collect');
Route::post('/dailynews/up_all', 'PhantomjsController@up_all');

Route::get('/dailynews', 'PhantomjsController@daily_list')->middleware("auth");
Route::get('/dailynews_p', 'PhantomjsController@daily_list_p')->middleware("auth");
Route::get('news_source/starter', 'PhantomjsController@starter');
Route::get('/timeline/tag/{tag}', 'TagController@timeline')->middleware("auth");
Route::get('/dailynews/source/{source}', 'PhantomjsController@source')->middleware("auth");
Route::get('/dailynews_p/source/{source}', 'PhantomjsController@source_p')->middleware("auth");
Route::get('/dailynews/key', 'PhantomjsController@daily_list_search')->middleware("auth");
Route::get('/dailynews/test', 'PhantomjsController@test');

Route::get('/source', 'PhantomjsController@show_all_source')->middleware("auth");
//Daily Funds
Route::get('/dailyfunds', 'DailyFundsController@list_funds');

//Name Cards
Route::get('/namecard/list', 'NameCardController@name_card_list')->middleware("auth");
Route::get('/namecard', 'NameCardController@namecard')->middleware("auth");
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
Route::get('/timeline/tag/{tag}/package/{pack}', 'TagController@package')->middleware("auth");


Route::get('/dailyfunds/indb', 'DailyFundsController@indb')->middleware("auth");


Route::get('/jobs/create/{table}/{subject}', 'JobsController@create')->middleware("auth");
Route::get('/jobs/admin', 'JobsController@index')->middleware("auth");

Route::get('/coreseek', 'ExcelController@coreseek')->middleware("auth");
Route::get('/coreseek/xml', 'ExcelController@coreseek_xml')->middleware("auth");

Route::get('/person/export', 'ExcelController@export_person_list')->middleware("auth");

Route::get('/haha', 'CompanyAdminController@index_all')->middleware("auth");

Route::get('/format_company', 'CompanyAdminController@format_company')->middleware("auth");

Route::get('/format_person', 'CompanyAdminController@format_person')->middleware("auth");

Route::get('/export_dict', 'ExcelController@my_dict')->middleware("auth");
Route::get('/vc', 'PhantomjsController@get_vcbeat')->middleware("auth");

//大item
Route::get("/item/{name}", 'ItemController@item')->middleware("auth");

//funding
Route::get('funding/story/{key}', 'BaikeController@search_engine_keywords_starter');

//IC
//Route::get('IC/basic', 'ICController@start_job');

//member
Route::get('/member/{date}', 'PhantomjsController@key_news_shared_list');








