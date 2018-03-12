<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group([
    'middleware' => ['web'],
    'domain' => 'xuegushi.cn'
], function () {
    /* =============  Frontend ==============*/
    /* == Homepage ==*/
    Route::get('/', 'HomeController@index');

    /* ================ 诗文======================== */
    Route::group([
        'prefix' => 'poem',
    ],function (){
        Route::get('/', 'Frontend\PoemsController@index');
        Route::get('/{id}','Frontend\PoemsController@show');
    });
//Route::get('/poems/update', 'Frontend\PoemsController@updatePoemLikeCount');

    /* =================== 诗人 =======================*/
    Route::group([
        'prefix' => 'author'
    ],function (){
        Route::get('/', 'Frontend\AuthorController@index');
        Route::get('/{id}','Frontend\AuthorController@show');
        Route::get('/{id}/poems','Frontend\AuthorController@showAllPoems');
    });
    /* =============== 古诗 整理 ==================== */
    Route::get('gushi/{name}','Frontend\GuShiController@index');
    /* =============== ajax ========================== */
    Route::group([
        'prefix' => 'ajax'
    ],function (){
        Route::post('/update/like','Frontend\PoemsController@updateLike');
        Route::post('/update/collect','Frontend\PoemsController@updateCollect');
        Route::post('/judge/like','Frontend\PoemsController@judgeLike');
        Route::get('/voiceCombine','Frontend\PoemsController@voiceCombine');
    });
    /* ============== 个人页面 ====================== */
    Route::group([
        'prefix' => 'people',
//        'middleware' => ['auth']
    ],function (){
        Route::get('/{id}','People\ShowController@index')->where('value', '[0-9]+');
    });
    /* =============== 收藏=========================== */
    Route::group([
        'prefix' => '/collections',
        'middleware' => ['auth']
    ],function () {
        Route::get('/', 'People\CollectionController@index');
        Route::get('/{type}', 'People\CollectionController@index');
    });
    /* =============== 喜欢 ====================== */
    Route::group([
        'prefix' => '/likes',
        'middleware' => ['auth']
    ],function () {
        Route::get('/', 'People\LikeController@index');
        Route::get('/{type}', 'People\LikeController@index');
    });
    /* =============== 搜索功能 ====================== */
    Route::group([
        'prefix' => 'search',
    ],function () {
        Route::get('/','Frontend\SearchController@index');
        Route::get('/poem','Frontend\SearchController@poem');
        Route::get('/author','Frontend\SearchController@author');
    });
    /* =============== 名句 ========================= */
    Route::group([
        'prefix' => 'sentence',
    ],function (){
        Route::get('/', 'Frontend\SentenceController@index');
        Route::get('/theme/{theme}','Frontend\SentenceController@getTheme');
    });
    /* =============== 专题页面 ===================== */
    Route::group([
        'prefix' =>'page'
    ],function (){
        Route::get('/{id}', 'Frontend\PageController@show');
    });
    /* =============== 微信小程序 ==================== */
    Route::group([
        'prefix' => 'wxxcx',
    ],function (){
        Route::get('/userInfo', 'Frontend\WxxcxController@getWxUserInfo');
        Route::get('/getRandomPoem', 'Frontend\WxxcxController@getRandomPoem');
        Route::get('/getHomeData', 'Frontend\WxxcxController@getHomeData');
        Route::get('/getPoemData', 'Frontend\WxxcxController@getPoemData');
        Route::get('/getSentenceData', 'Frontend\WxxcxController@getSentenceData');
        Route::get('/poem/{id}', 'Frontend\WxxcxController@getPoemDetail');
    });
    /* =============== 静态页面 ====================== */
    Route::get('/contact','Frontend\PageController@contact');
    Route::get('/join','Frontend\PageController@join');
    Route::get('/about','Frontend\PageController@about');
});
Route::group(['domain' => 'zhuanlan.xuegushi.cn'], function() {
    Route::get('/', 'ZhuanLan\ZhuanLanController@index');
    Route::get('/apply', 'ZhuanLan\ZhuanLanController@apply');
    // 专栏
    Route::group([
        'prefix' =>'zhuanlan'
    ],function (){
        Route::post('/create', 'ZhuanLan\ZhuanLanController@store');
        Route::post('/judgeDomain', 'ZhuanLan\ZhuanLanController@judgeDomain');

    });
    // 文章
    Route::group([
        'prefix' => 'post'
    ],function (){
        Route::get('/','ZhuanLan\PostController@index');
        Route::post('/create','ZhuanLan\PostController@store');
        Route::post('/update','ZhuanLan\PostController@update');
        Route::post('/delete','ZhuanLan\PostController@delete')->where('id', '[0-9]+');
        Route::post('/reset','ZhuanLan\PostController@reset')->where('id', '[0-9]+');
        Route::get('{id}','ZhuanLan\PostController@show')->where('id', '[0-9]+');
        Route::get('{id}/edit','ZhuanLan\PostController@edit')->where('id', '[0-9]+');
        Route::get('{id}/preview','ZhuanLan\PostController@show')->where('id', '[0-9]+');
        Route::post('{id}/{type}','ZhuanLan\PostController@updateLikeOrCollect')->where('id', '[0-9]+');
    });
    Route::group([
        'prefix' =>'me'
    ],function (){
        Route::get('/drafts','ZhuanLan\MeController@drafts');
        Route::get('/posts','ZhuanLan\MeController@posts');
        Route::get('/publications', 'ZhuanLan\MeController@publications');
        Route::get('/subscribes', 'ZhuanLan\MeController@subscribes');
        Route::get('/setting', 'ZhuanLan\MeController@setting');
        Route::post('/update', 'ZhuanLan\MeController@updateSetting');
    });
    Route::group([
        'prefix' => 'people'
    ],function (){
        Route::get('/{id}','ZhuanLan\MeController@show');
        Route::get('/{id}/subscribes','ZhuanLan\MeController@subscribes');
        Route::get('/{id}/favorites','ZhuanLan\MeController@favorites');
        Route::get('/{id}/collects','ZhuanLan\MeController@collects');
        Route::get('/{id}/comments','ZhuanLan\MeController@comments');
    });
    // 上传图片
    Route::post('/uploads_image/{type}', 'LA\UploadsController@uploadZhuanlanAvatar');
    Route::post('/uploads/userAvatar', 'LA\UploadsController@uploadUserAvatar');

    Route::get('/write','ZhuanLan\PostController@write');
    // 搜索功能
    Route::group([
        'prefix' => 'search',
    ],function () {
        Route::get('/','Frontend\SearchController@index');
        Route::get('/poem','Frontend\SearchController@poem');
        Route::get('/author','Frontend\SearchController@author');
    });
    // 登录/注册/登出
    Route::get('/login','Auth\AuthController@showLoginForm');
    Route::get('/register','Auth\AuthController@showRegistrationForm');
    Route::get('/logout','Auth\AuthController@logout');
    // api
    Route::group([
        'prefix' => 'api'
    ],function (){
        Route::post('/posts/{id}/comments','ZhuanLan\ReviewController@create');
        Route::get('/posts/{id}/comments','ZhuanLan\ReviewController@show');
        Route::get('/posts/{id}/comments/{parent_id}/conversation','ZhuanLan\ReviewController@conversation');
        Route::post('/posts/{id}/comments/{t_id}/like','ZhuanLan\ReviewController@like');
        Route::get('/posts/{id}/comments/{t_id}/delete','ZhuanLan\ReviewController@delete');
    });
    Route::get('/{domain}','ZhuanLan\ZhuanLanController@show');
    Route::get('/{domain}/about','ZhuanLan\ZhuanLanController@about');
    Route::post('/{domain}/follow','ZhuanLan\ZhuanLanController@follow');
    Route::get('/{domain}/followers','ZhuanLan\ZhuanLanController@followers');
});
//Route::get('/authors/update', 'Frontend\AuthorController@updateAuthorsLikeCount');

/* =============== Admin Routes ================== */

require __DIR__.'/admin_routes.php';