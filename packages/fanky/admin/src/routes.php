<?php

//Route::any('admin', ['as' => 'admin', 'uses' => 'Fanky\Admin\Controllers\AdminController@main']);
use Fanky\Admin\Controllers\AdminCatalogController;
use Fanky\Admin\Controllers\AdminActionController;

Route::group(['namespace' => 'Fanky\Admin\Controllers', 'prefix' => 'admin', 'as' => 'admin'], function () {
	Route::any('/', ['uses' => 'AdminController@main']);
	Route::group(['as' => '.pages', 'prefix' => 'pages'], function () {
		$controller  = 'AdminPagesController@';
		Route::get('/', $controller . 'getIndex');
		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('edit/{id?}', $controller . 'postEdit')
			->name('.edit');

		Route::get('get-pages/{id?}', $controller . 'getGetPages')
			->name('.get_pages');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('reorder', $controller . 'postReorder')
			->name('.reorder');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.del');

		Route::get('filemanager', [
			'as'   => '.filemanager',
			'uses' => $controller . 'getFileManager'
		]);
		Route::get('imagemanager', [
			'as'   => '.imagemanager',
			'uses' => $controller . 'getImageManager'
		]);
	});

	Route::group(['as' => '.catalog', 'prefix' => 'catalog'], function () {
		$controller  = 'AdminCatalogController@';
		Route::get('/', [AdminCatalogController::class, 'getIndex']);

		Route::get('products/{id?}', $controller . 'getProducts')
			->name('.products');

		Route::post('catalog-edit/{id?}', $controller . 'postCatalogEdit')
			->name('.catalogEdit');

		Route::get('catalog-edit/{id?}', $controller . 'getCatalogEdit')
			->name('.catalogEdit');

		Route::post('catalog-save', $controller . 'postCatalogSave')
			->name('.catalogSave');

		Route::post('catalog-reorder', $controller . 'postCatalogReorder')
			->name('.catalogReorder');

		Route::post('catalog-delete/{id}', $controller . 'postCatalogDelete')
			->name('.catalogDel');

		Route::get('product-edit/{id?}', $controller . 'getProductEdit')
			->name('.productEdit');

		Route::post('product-save', $controller . 'postProductSave')
			->name('.productSave');

		Route::post('product-reorder', $controller . 'postProductReorder')
			->name('.productReorder');

		Route::post('update-order/{id}', $controller . 'postUpdateOrder')
			->name('.update-order');

		Route::post('product-delete/{id}', $controller . 'postProductDelete')
			->name('.productDel');

		Route::post('product-image-upload/{id}', $controller . 'postProductImageUpload')
			->name('.productImageUpload');

		Route::post('product-image-delete/{id}', $controller . 'postProductImageDelete')
			->name('.productImageDel');

		Route::post('product-image-order', $controller . 'postProductImageOrder')
			->name('.productImageOrder');

		Route::get('get-catalogs/{id?}', $controller . 'getGetCatalogs')
			->name('.get_catalogs');

        Route::post('add-related/{id}', [
            'as'   => '.add_related',
            'uses' => $controller . 'postAddRelated'
        ]);

        Route::post('del-related/{id}', [
            'as'   => '.del_related',
            'uses' => $controller . 'postDelRelated'
        ]);

        Route::post('save-related/{id}', [
            'as'   => '.save_related',
            'uses' => $controller . 'postSaveRelated'
        ]);

        Route::post('add-param/{id}', [
            'as'   => '.add_param',
            'uses' => $controller . 'postAddParam'
        ]);
        Route::post('del-param/{id}', [
            'as'   => '.del_param',
            'uses' => $controller . 'postDelParam'
        ]);
        Route::post('edit-param/{id}', [
            'as'   => '.edit_param',
            'uses' => $controller . 'postEditParam'
        ]);
        Route::post('save-param/{id}', [
            'as'   => '.save_param',
            'uses' => $controller . 'postSaveParam'
        ]);
        Route::post('update-filter-title/{id}', [
            'as'   => '.update-filter-title',
            'uses' => $controller . 'postUpdateFilterTitle'
        ]);
	});

    Route::group(['as' => '.product-icons', 'prefix' => 'product-icons'], function () {
        $controller = 'AdminProductIconsController@';
        Route::get('/', $controller . 'getIndex');

        Route::get('edit/{id?}', $controller . 'getEdit')
            ->name('.edit');

        Route::post('save', $controller . 'postSave')
            ->name('.save');

        Route::post('delete/{id}', $controller . 'postDelete')
            ->name('.delete');

        Route::post('delete-image/{id}', $controller . 'postDeleteImage')
            ->name('.delete-image');
    });

    Route::group(['as' => '.actions', 'prefix' => 'actions'], function () {
        $controller  = 'AdminActionController@';
        Route::get('/', [AdminActionController::class, 'getIndex']);

        Route::get('products/{id?}', $controller . 'getProducts')
            ->name('.products');

        Route::post('action-edit/{id?}', $controller . 'postActionEdit')
            ->name('.actionEdit');

        Route::get('action-edit/{id?}', $controller . 'getActionEdit')
            ->name('.catalogEdit');

        Route::post('action-save', $controller . 'postActionSave')
            ->name('.actionSave');

        Route::post('action-reorder', $controller . 'postActionReorder')
            ->name('.actionReorder');

        Route::post('action-delete/{id}', $controller . 'postActionDelete')
            ->name('.actionDel');

        Route::get('get-actions/{id?}', $controller . 'getGetActions')
            ->name('.getActions');

        Route::get('product-edit/{id?}', $controller . 'getProductEdit')
            ->name('.productEdit');

        Route::post('product-save', $controller . 'postProductSave')
            ->name('.productSave');

        Route::post('product-reorder', $controller . 'postProductReorder')
            ->name('.productReorder');

        Route::post('update-order/{id}', $controller . 'postUpdateOrder')
            ->name('.update-order');

        Route::post('product-delete/{id}', $controller . 'postProductDelete')
            ->name('.productDel');

        Route::post('product-image-upload/{id}', $controller . 'postProductImageUpload')
            ->name('.productImageUpload');

        Route::post('product-image-delete/{id}', $controller . 'postProductImageDelete')
            ->name('.productImageDel');

        Route::post('product-image-order', $controller . 'postProductImageOrder')
            ->name('.productImageOrder');


        Route::post('add-action-product/{id}', [
            'as'   => '.add_action_product',
            'uses' => $controller . 'postAddActionProduct'
        ]);
        Route::post('del-action-product/{id}', [
            'as'   => '.del_action_product',
            'uses' => $controller . 'postDelActionProduct'
        ]);
        Route::post('edit-action-product/{id}', [
            'as'   => '.edit_action_product',
            'uses' => $controller . 'postEditActionProduct'
        ]);
        Route::post('save-action-product/{id}', [
            'as'   => '.save_action_product',
            'uses' => $controller . 'postSaveActionProduct'
        ]);
    });

	Route::group(['as' => '.news', 'prefix' => 'news'], function () {
		$controller = 'AdminNewsController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.delete');

		Route::post('delete-image/{id}', $controller . 'postDeleteImage')
			->name('.delete-image');
	});

    Route::group(['as' => '.offers', 'prefix' => 'offers'], function () {
		$controller = 'AdminOffersController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.delete');

		Route::post('delete-image/{id}', $controller . 'postDeleteImage')
			->name('.delete-image');
	});

    Route::group(['as' => '.orders', 'prefix' => 'orders'], function () {
		$controller = 'AdminOrdersController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('view/{id?}', $controller . 'getView')
			->name('.view');

		Route::post('del/{id}', $controller . 'postDelete')
			->name('.del');
	});

//    Route::controller('admin/orders', 'Fanky\Admin\Controllers\AdminOrdersController', [
//        'getIndex' => 'admin.orders',
//        'getView' => 'admin.orders.view',
//        'postDelete' => 'admin.orders.del',
//    ]);

	Route::group(['as' => '.publications', 'prefix' => 'publications'], function () {
		$controller = 'AdminPublicationsController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.delete');

	});

	Route::group(['as' => '.gallery', 'prefix' => 'gallery'], function () {
		$controller = 'AdminGalleryController@';
		Route::get('/', $controller . 'anyIndex');
		Route::post('gallery-save', $controller . 'postGallerySave')
			->name('.gallerySave');
		Route::post('gallery-edit/{id?}', $controller . 'postGalleryEdit')
			->name('.gallery_edit');
		Route::post('gallery-delete/{id}', $controller . 'postGalleryDelete')
			->name('.galleryDel');
		Route::any('items/{id}', $controller . 'anyItems')
			->name('.items');
		Route::post('image-upload/{id}', $controller . 'postImageUpload')
			->name('.imageUpload');
		Route::post('image-edit/{id}', $controller . 'postImageEdit')
			->name('.imageEdit');
		Route::post('image-data-save/{id}', $controller . 'postImageDataSave')
			->name('.imageDataSave');
		Route::post('image-del/{id}', $controller . 'postImageDelete')
			->name('.imageDel');
		Route::post('image-order', $controller . 'postImageOrder')
			->name('.order');
	});

	Route::group(['as' => '.reviews', 'prefix' => 'reviews'], function () {
		$controller = 'AdminReviewsController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('reorder', $controller . 'postReorder')
			->name('.reorder');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.del');
	});

	Route::group(['as' => '.settings', 'prefix' => 'settings'], function () {
		$controller = 'AdminSettingsController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('group-items/{id?}', $controller . 'getGroupItems')
			->name('.groupItems');

		Route::post('group-save', $controller . 'postGroupSave')
			->name('.groupSave');

		Route::post('group-delete/{id}', $controller . 'postGroupDelete')
			->name('.groupDel');

		Route::post('clear-value/{id}', $controller . 'postClearValue')
			->name('.clearValue');

		Route::any('edit/{id?}', $controller . 'anyEditSetting')
			->name('.edit');

		Route::any('block-params', $controller . 'anyBlockParams')
			->name('.blockParams');

		Route::post('edit-setting-save', $controller . 'postEditSettingSave')
			->name('.editSave');

		Route::post('save', $controller . 'postSave')
			->name('.save');
	});

	Route::group(['as' => '.redirects', 'prefix' => 'redirects'], function () {
		$controller = 'AdminRedirectsController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::get('delete/{id}', $controller . 'getDelete')
			->name('.delete');

		Route::post('save', $controller . 'postSave')
			->name('.save');
	});

	Route::group(['as' => '.feedbacks', 'prefix' => 'feedbacks'], function () {
		$controller = 'AdminFeedbacksController@';
		Route::get('/', $controller . 'getIndex');

		Route::post('read/{id?}',$controller . 'postRead')
			->name('.read');
		Route::post('delete/{id?}', $controller . 'postDelete')
			->name('.del');
	});

	Route::group(['as' => '.users', 'prefix' => 'users'], function () {
		$controller = 'AdminUsersController@';
		Route::get('/', $controller . 'getIndex');

		Route::post('edit/{id?}', $controller . 'postEdit')
			->name('.edit');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('del/{id}', $controller . 'postDelete')
			->name('.del');
	});

	Route::group(['as' => '.cities', 'prefix' => 'cities'], function () {
		$controller = 'AdminCitiesController@';
		Route::get('/', $controller . 'getIndex');

		Route::get('edit/{id?}', $controller . 'getEdit')
			->name('.edit');

		Route::post('delete/{id}', $controller . 'postDelete')
			->name('.del');

		Route::post('save', $controller . 'postSave')
			->name('.save');

		Route::post('tree/{id?}', $controller . 'postTree')
			->name('.tree');
	});
});
