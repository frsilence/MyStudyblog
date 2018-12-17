<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('/','blog/index/index');

//用户管理
Route::group('auth/',function(){
	Route::get('login', 'blog/auth/login');
	Route::post('post_login','blog/auth/post_login');
	Route::get('register','blog/auth/register');
	Route::post('post_register','blog/auth/post_register');
	Route::get('logout','blog/auth/logout');
});

//test
Route::group('test/',function(){
	Route::get('create_category','index/index/create_category');
});

//文章分类
Route::get('category/:id','blog/article/get_category');

//文章操作
Route::group('article/',function(){
	Route::get('addarticle','blog/article/addArticlePage');
});

//API
Route::group('api/',function(){
	//图片上传
	Route::post('upload/upload_image','blog/Upload/upload_image');
	//文章操作
	Route::group('article/',function(){
		Route::post('addarticle','blog/article/addarticle');
	});
});




return [

];
