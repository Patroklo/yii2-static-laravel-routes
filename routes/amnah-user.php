<?php
use cyneek\yii2\routes\components\Route;


	/**
	 * Checks if a user is logged in. If not, it will throw a ForbiddenHttpException
	 */
	Route::filter('logged_in', function() {
		if (Yii::$app->user->getIsLoggedIn())
		{
			return TRUE;
		}
		else
		{
			 throw new \yii\web\ForbiddenHttpException(\Yii::t('yii', 'Not logged in.'));
		}
	});


	/**
	 * Checks if a user is logged out. If not, it will throw a ForbiddenHttpException
	 */
	Route::filter('logged_out', function() {
		if (Yii::$app->user->isGuest())
		{
			return TRUE;
		}
		else
		{
			 throw new \yii\web\ForbiddenHttpException(\Yii::t('yii', 'Must be logged out.'));
		}
	});


	/**
	 * BASIC SITES
	 */

	Route::any('user', 'user/default/index');
	Route::any('user/login', 'user/login');
	Route::any('user/logout', 'user/logout', ['before' => 'logged_in']);
	Route::any('user/register', 'user/register');
	Route::any('user/account', 'user/account', ['before' => 'logged_in']);
	Route::any('user/profile', 'user/profile');
	Route::any('user/forgot', 'user/forgot');
	Route::any('user/reset', 'user/reset');
	Route::any('user/resend', 'user/resend');
	Route::any('user/resend-change', 'user/resend-change');
	Route::any('user/cancel', 'user/cancel');
	Route::any('user/confirm', 'user/confirm');
	Route::any('user/auth/login', 'user/auth/login');
	Route::any('user/auth/connect', 'user/auth/connect');

	/**
	 * ADMIN SITES
	 */

	Route::any('user/admin', 'user/admin');
	Route::any('user/admin/view', 'user/admin/view');
	Route::any('user/admin/create', 'user/admin/create');
	Route::any('user/admin/update', 'user/admin/update');
	Route::any('user/admin/delete', 'user/admin/delete');


	/**
	 * OAUTH SITES
	 */

	Route::any('user/auth/login', 'user/auth/login');
	Route::any('user/auth/connect', 'user/auth/connect');

//user 	This 'actions' list. Appears only when YII_DEBUG=true, otherwise redirects to /login or /account
//user/admin 	Admin CRUD
//user/login 	Login page
//user/logout 	Logout page
//user/register 	Register page
//user/auth/login 	Register/login via social account
//user/auth/connect 	Connect social account to currently logged in user account
//user/account 	User account page (email, username, password)
//user/profile 	Profile page
//user/forgot 	Forgot password page
//user/reset?key=zzzzz 	Reset password page. Automatically generated from forgot password page
//user/resend 	Resend email confirmation (for both activation and change of email)
//user/resend-change 	Resend email change confirmation (quick link on the 'Account' page)
//user/cancel 	Cancel email change confirmation (quick link on the 'Account' page)
//user/confirm?key=zzzzz 	Confirm email address. Automatically generated upon registration/email change
//user/auth/login?authclient=zzzzz 	Register/login via social authentication
//user/auth/connect?authclient=zzzzz 	Connect social authentication account to currently logged in user