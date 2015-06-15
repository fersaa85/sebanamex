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

//

Route::group(array('prefix' => 'admin', 'middleware' => 'auth'), function() {
	Route::controller('users', 	  	   "UsersController");
	Route::controller('coupons',  	   "CouponsController");
	Route::controller('comments', 	   "CommentsController");
	Route::controller('notifications', "NotificationsController");
});

Route::controller('api', 'ApiController');
Route::controller('',    'HomeController');

