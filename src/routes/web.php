<?php

Route::group(['namespace' => 'Sayeed\Sslwireless\Http\Controllers'], function() {
	Route::get('sayeed/sslwireless', 'SslwirelessController@setConfig');
});