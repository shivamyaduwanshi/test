<?php

/**
 * Authentication Routes;
 */
Route::name('backend.')->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('api/reset/password/success',function(){ return view('api.forgot_password_success_response'); })->name('api.rest.password.success');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::put('/update/profile', 'HomeController@updateProfile')->name('update.profile');
    Route::put('/change/password', 'HomeController@changePassword')->name('change.password');
    Route::get('/members', 'HomeController@members')->name('members');
    Route::get('/member/details/{id}', 'HomeController@memberDetails')->name('member.details');
    Route::get('members/export/', 'HomeController@exportMembers')->name('export.members');
    Route::delete('/delete/account', 'HomeController@deleteAccount')->name('delete.account');
    Route::put('/active/account', 'HomeController@activeAccount')->name('active.account');
    Route::put('/deactive/account', 'HomeController@deactiveAccount')->name('deactive.account');

     /**
     * Ajax Route's
     */
     Route::get('/ajax/get/sub/categories/{id?}', 'AjaxController@getSubCategories')->name('ajaxGetSubCategories');
     Route::post('/ajax/store/sub/categories', 'AjaxController@storeSubCategory')->name('ajaxStoreSubCategory');
     Route::post('/ajax/update/sub/categories', 'AjaxController@updateSubCategory')->name('ajaxUpdateSubCategory');
     Route::delete('/ajax/delete/sub/categories', 'AjaxController@deleteSubCategory')->name('ajaxdeleteSubCategory');

     Route::get('/ajax/variation/options/{id?}', 'AjaxController@variationOptions')->name('variationOptions');
     Route::post('/ajax/variation/option/store', 'AjaxController@storeVariationOption')->name('ajaxStoreVariationOption');
     Route::put('/ajax/variation/option/update', 'AjaxController@updateVariationOption')->name('ajaxUpdateVariationOption');
     Route::delete('/ajax/variation/option/delete', 'AjaxController@deleteVariationOption')->name('ajaxDeleteVariationOption');
     
    /**
     *  Media Route's
     */
    Route::name('mediagallery.')->group(function () {
        Route::get('media/gallaries', 'MediaGalleryController@index')->name('index');
        Route::get('create/media', 'MediaGalleryController@create')->name('create');
        Route::post('store/media', 'MediaGalleryController@store')->name('store');
        Route::get('show/media/{id}', 'MediaGalleryController@show')->name('show');
        Route::put('update/media/{id}', 'MediaGalleryController@update')->name('update');
        Route::delete('delete/media', 'MediaGalleryController@destroy')->name('delete');
    });

     /**
     *  Banner Route's
     */
    Route::get('banners', 'BannerController@index')->name('index.banner');
    Route::get('create/banner', 'BannerController@create')->name('create.banner');
    Route::post('store/banner', 'BannerController@store')->name('store.banner');
    Route::get('show/banner/{id}', 'BannerController@show')->name('show.banner');
    Route::put('update/banner/{id}', 'BannerController@update')->name('update.banner');
    Route::delete('delete/banner', 'BannerController@destroy')->name('delete.banner');

    /**
     * Category Route's
     */
    Route::get('categories', 'CategoryController@index')->name('index.category');
    Route::get('add/category', 'CategoryController@create')->name('create.category');
    Route::post('store/category', 'CategoryController@store')->name('store.category');
    Route::get('show/category/{id}', 'CategoryController@show')->name('show.category');
    Route::put('update/category/{id}', 'CategoryController@update')->name('update.category');
    Route::delete('delete/category', 'CategoryController@destroy')->name('delete.category');

    /**
     * Product Route's
     */
    Route::get('products', 'ProductController@index')->name('index.product');
    Route::get('add/product', 'ProductController@create')->name('create.product');
    Route::post('store/product', 'ProductController@store')->name('store.product');
    Route::get('show/product/{id}', 'ProductController@show')->name('show.product');
    Route::put('update/product/{id}', 'ProductController@update')->name('update.product');
    Route::delete('delete/product', 'ProductController@destroy')->name('delete.product');

     /**
     * Category Route's
     */
    Route::get('categories', 'CategoryController@index')->name('index.category');
    Route::get('add/category', 'CategoryController@create')->name('create.category');
    Route::post('store/category', 'CategoryController@store')->name('store.category');
    Route::get('show/category/{id}', 'CategoryController@show')->name('show.category');
    Route::put('update/category/{id}', 'CategoryController@update')->name('update.category');
    Route::delete('delete/category', 'CategoryController@destroy')->name('delete.category');

    /**
     * Variation Route's
     */
    Route::get('variations', 'VariationController@index')->name('index.variation');
    Route::get('variation/options/{id}', 'VariationController@option')->name('option.variation');
    Route::get('add/variation', 'VariationController@create')->name('create.variation');
    Route::post('store/variation', 'VariationController@store')->name('store.variation');
    Route::get('show/variation/{id}', 'VariationController@show')->name('show.variation');
    Route::put('update/variation/{id}', 'VariationController@update')->name('update.variation');
    Route::delete('delete/variation', 'VariationController@destroy')->name('delete.variation');

     /**
     * Variation Option Route's
     */
   // Route::get('variation/options', 'VariationOptionController@index')->name('index.variation.option');
    Route::get('add/variation/option', 'VariationOptionController@create')->name('create.variation.option');
    Route::post('store/variation/option', 'VariationOptionController@store')->name('store.variation.option');
    Route::get('show/variation/{id}/option', 'VariationOptionController@show')->name('show.variation.option');
    Route::put('update/variation/{id}/option', 'VariationOptionController@update')->name('update.variation.option');
    Route::delete('delete/variation/option{id}', 'VariationOptionController@destroy')->name('delete.variation.option');

    /**
     * Dancer Route's
     */
    Route::get('vendors', 'VendorController@index')->name('index.vendor');
    Route::get('create/vendor', 'VendorController@create')->name('create.vendor');
    Route::post('store/vendor', 'VendorController@store')->name('store.vendor');
    Route::get('show/vendor/{id}', 'VendorController@show')->name('show.vendor');
    Route::put('update/vendor/{id}', 'VendorController@update')->name('update.vendor');
    Route::get('export/vendor', 'VendorController@export')->name('export.vendor');
    Route::put('active/vendor', 'VendorController@activeAccount')->name('active.vendor');
    Route::delete('delete/vendor', 'VendorController@destroy')->name('delete.vendor');
    Route::put('/deactive/vendor', 'VendorController@deactiveAccount')->name('deactive.vendor');

    /**
     * User Route's
     */
    Route::get('users', 'UserController@index')->name('index.user');
    Route::get('show/user/{id}', 'UserController@show')->name('show.user');
    Route::put('update/user/{id}', 'UserController@update')->name('update.user');
    Route::get('export/user', 'UserController@export')->name('export.user');
    Route::put('active/user', 'UserController@activeAccount')->name('active.user');
    Route::delete('delete/user', 'UserController@destroy')->name('delete.user');
    Route::put('/deactive/user', 'UserController@deactiveAccount')->name('deactive.user');

    Route::get('config','HomeController@config')->name('index.config');
    Route::get('show/config/{id}','HomeController@getConfig')->name('show.config');
    Route::put('update/config/{id}','HomeController@updateConfig')->name('update.config');

    Route::get('/show/image/{filename}', function($filename){

        // $path = public_path() . '/uploads/2021/04/' . $filename;

        // if(!File::exists($path)) {
        //     return response()->json(['message' => 'Image not found.'], 404);
        // }
    
        // $file = File::get($path);
        // $type = File::mimeType($path);
    
        // $response = Response::make($file, 200);
        // $response->header("Content-Type", $type);
    
        // return $response;
    });

});