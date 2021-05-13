<?php
  
  Route::get('/', 'Backend\Auth\LoginController@showLoginForm')->name('login');
  Route::get('login', 'Backend\Auth\LoginController@showLoginForm')->name('login');

?>