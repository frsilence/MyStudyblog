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
	Route::get('redis',function(){
		for($i=0;$i<=10000000;$i++){
			Cache::store('redis')->set('blog_my_num_'.($i*$i+23).'_'.$i,(($i*4+3).'testsajgdjhasgdjh'),1000000);
		}
		return json(Cache::store('redis')->get('my_num_9999'));
	});
	Route::get('shuzu',function(){
		return json(Cache::store('redis')->get('my_num'));
	});
	Route::get('queue','common/test/actionHelloJob');
	Route::get('config',function(){
		$config =  config('cache.');
		$redis = new Redis();
		$redis->connect($config['redis']['host'],$config['redis']['port']);
		$redis->auth($config['redis']['password']);
		Log::record('redis','error');
		return json($redis->get('blog_my_num'));
	});
	Route::get('session',function(){
		return json(session(''));
	});
	Route::get('ip',function(){
		return json(json_decode(file_get_contents('http://freeapi.ipip.net/61.183.207.98')));
	});
});



//文章操作
Route::group('article/',function(){
	Route::get('addarticle','blog/article/addArticlePage');
	Route::get('id/:id','blog/article/readArticle');
	Route::get('category/id/:id','blog/article/getCategory');
	Route::get('category/all','blog/article/getAllCategory');
	Route::get('search','blog/article/getSearchArticle');
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
		Route::get('member/:id/collectarticlelist','blog/member/getMemberCollectArticleList');
		Route::post('collectarticle/:id','blog/article/collectArticle');
		Route::post('uncollectarticle/:id','blog/article/uncollectArticle');
		Route::post('checkarticlemember','blog/article/checkArticleMember');
		Route::post('praisearticle/:id','blog/article/addPraise');
		Route::post('searcharticle','blog/article/searchArticle');
		Route::post('relatedarticle','blog/article/getRelatedArticle');
		Route::post('praisearticlelist','blog/article/getPraiseArticleList');
		Route::post('clickarticlelist','blog/article/getClickArticleList');

	});
	//用户操作
	Route::group('member/',function(){
		Route::post('index/update_info/:id','blog/member/updateInfoForm');
		Route::post('index/update_userimage/:id','blog/member/updateUserimage');
		Route::get('selfloginrecord','blog/member/getMemberSelfLoginRecord');
		Route::post('checkmemberfollow/:id','blog/member/checkMemberFollow');
		Route::post('addmemberfollow/:id','blog/member/addMemberFollow');
		Route::post('deletememberfollow/:id','blog/member/deleteMemberFollow');
});
});




return [

];
