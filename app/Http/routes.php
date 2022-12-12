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

use App\Http\Controllers\AjaxController;

Route::get('robots.txt', 'PageController@robots')->name('robots');

Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
    Route::post('add-to-cart', [AjaxController::class, 'postAddToCart']);
    Route::post('update-to-cart', [AjaxController::class, 'postUpdateToCart']);
    Route::post('remove-from-cart', [AjaxController::class, 'postRemoveFromCart']);
    Route::post('purge-cart', [AjaxController::class, 'postPurgeCart']);
    Route::post('edit-cart-product', [AjaxController::class, 'postEditCartProduct']);
    Route::post('order', [AjaxController::class, 'postOrder'])->name('order');
	Route::post('request', 'AjaxController@postRequest')->name('request');
    Route::post('fast-request', 'AjaxController@postFastRequest')->name('fast-request');
    Route::post('questions', 'AjaxController@postQuestions')->name('questions');
	Route::post('writeback', 'AjaxController@postWriteback')->name('writeback');
	Route::post('contact-us', 'AjaxController@postContactUs')->name('contact-us');
	Route::post('callback', 'AjaxController@postCallback')->name('callback');
    Route::post('set-city', 'AjaxController@postSetCity')->name('set-city');
    Route::post('get-correct-region-link', 'AjaxController@postGetCorrectRegionLink')->name('get-correct-region-link');
    Route::get('show-popup-cities', [AjaxController::class, 'showCitiesPopup'])
        ->name('show-popup-cities');
//    Route::get('search', [AjaxController::class, 'showCitiesPopup'])
//        ->name('search');
    Route::post('set-view/{view}', 'AjaxController@postSetView')
        ->name('set-view');
    Route::post('update-filter', 'AjaxController@postUpdateFilter')
        ->name('update-filter');
    Route::post('filter-apply', 'AjaxController@postFilterApply')
        ->name('filter-apply');
});
Route::group(['middleware' => ['redirects', 'regions']], function() {
    $cities = getCityAliases();
    $cities = implode('|', $cities);
    Route::group([
        'prefix' => '{city}',
        'as'     => 'region.',
        'where'  => ['city' => $cities]
    ], function () use ($cities) {
        Route::get('/', ['as' => 'index', 'uses' => 'PageController@page']);

        Route::group(['prefix' => 'catalog', 'as' => 'catalog.'], function () {
            Route::any('/', ['as' => 'index', 'uses' => 'CatalogController@index']);
            Route::any('{alias}', ['as' => 'view', 'uses' => 'CatalogController@view'])
                ->where('alias', '([A-Za-z0-9\-\/_]+)');
        });

        Route::any('{alias}', ['as' => 'pages', 'uses' => 'PageController@region_page'])
            ->where('alias', '([A-Za-z0-9\-\/_]+)');
    });

    Route::get('/', ['as' => 'main', 'uses' => 'WelcomeController@index']);

    Route::any('news', ['as' => 'news', 'uses' => 'NewsController@index']);
    Route::get('news/{alias}', ['as' => 'news.item', 'uses' => 'NewsController@item']);

    Route::any('services', ['as' => 'services', 'uses' => 'ServiceController@index']);
    Route::get('services/{alias}', ['as' => 'services.item', 'uses' => 'ServiceController@view'])
        ->where('alias', '([A-Za-z0-9\-\/_]+)');

    Route::any('actions', ['as' => 'actions.index', 'uses' => 'ActionController@index']);
    Route::get('action/{alias}', ['as' => 'action.item', 'uses' => 'ActionController@view'])
        ->where('alias', '([A-Za-z0-9\-\/_]+)');

    Route::any('publications', ['as' => 'publications', 'uses' => 'PublicationController@index']);
    Route::get('publications/{alias}', ['as' => 'publications.item', 'uses' => 'PublicationController@item']);

    Route::any('offers', ['as' => 'offers', 'uses' => 'OfferController@index']);
    Route::get('offers/{alias}', ['as' => 'offers.item', 'uses' => 'OfferController@item']);

    Route::any('vacancy', ['as' => 'vacancy', 'uses' => 'VacancyController@index']);

    Route::any('reviews', ['as' => 'reviews', 'uses' => 'ReviewsController@index']);

    Route::any('partners', ['as' => 'partners', 'uses' => 'PartnersController@index']);

    Route::any('suppliers', ['as' => 'suppliers', 'uses' => 'SuppliersController@index']);

    Route::any('gosts', ['as' => 'gosts', 'uses' => 'GostsController@index']);

    Route::any('answers', ['as' => 'answers', 'uses' => 'AnswersController@index']);

    Route::any('search', ['as' => 'search', 'uses' => 'PageController@search']);

    Route::any('faq', ['as' => 'faq', 'uses' => 'FaqController@index']);

    Route::get('cart', ['as' => 'cart', 'uses' => 'CartController@getIndex']);
    Route::get('order-success/{id?}', ['as' => 'order-success', 'uses' => 'CartController@showSuccess']);

    Route::get('ajax-cities', ['as' => 'ajax-cities', 'uses' => 'PageController@ajaxCities']);

    Route::any('catalog', ['as' => 'catalog.index', 'uses' => 'CatalogController@index']);
    Route::any('catalog/{alias}', ['as' => 'catalog.view', 'uses' => 'CatalogController@view'])
        ->where('alias', '([A-Za-z0-9\-\/_]+)');

//    Route::any('pages/{alias}', ['as' => 'default', 'uses' => 'PageController@old_pages'])
//        ->where('alias', '([A-Za-z0-9\-\/_]+)');

    Route::any('{alias}', ['as' => 'default', 'uses' => 'PageController@page'])
        ->where('alias', '([A-Za-z0-9\-\/_]+)');
});
