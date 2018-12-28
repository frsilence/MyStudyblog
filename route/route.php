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
	Route::get('code_line',function(){
		return 's';
	});
});



//文章操作
Route::group('article/',function(){
	Route::get('addarticle','blog/article/addArticlePage');
	Route::get('id/:id','blog/article/readArticle');
	Route::get('category/id/:id','blog/article/getCategory');
	Route::get('category/all','blog/article/getAllCategory');
});

//用户操作
Route::group('member/',function(){
	Route::get('index/id/:id','blog/member/readMember');
	Route::get('api/sda','blog/member/readMember');
});

//API
Route::group('api/',function(){
	//文章编辑图片上传
	Route::post('upload/upload_image','blog/Upload/upload_image');
	//用户头像上传
	Route::post('upload/upload_userimage','blog/Upload/upload_userimage');
	//文章操作
	Route::group('article/',function(){
		Route::post('addarticle','blog/article/addArticle');
		Route::post('addcomment','blog/article/addComment');
		Route::get('category/:id/articlelist','blog/article/getCategoryArticleList');
		Route::get('member/:id/articlelist','blog/member/getMemberArticleList');
		Route::get('member/:id/commentlist','blog/member/getMemberCommentList');
		Route::post('collectarticle/:id','blog/article/collectarticle')
	});
	//用户操作
	Route::group('member/',function(){
		Route::post('index/update_info/:id','blog/member/updateInfoForm');
		Route::post('index/update_userimage/:id','blog/member/updateUserimage');
		Route::get('selfloginrecord','blog/member/getMemberSelfLoginRecord');
});
});




return [

];
