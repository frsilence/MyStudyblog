<?php

namespace app\blog\model;

use think\Model;

/**
 * 搜索模型
 */
class BlogSearch extends Model
{
    protected $table = 'blog_search';

    /**
     * 保存搜索关键词
     * @param  string $search_word 搜索关键词
     * @return null
     */
    public function saveSearchWord($search_word)
    {
    	$word = $this->where('word',$search_word)->find();
    	if(!empty($word)){
    		$this->where('word',$search_word)->update([
    			'search_num'=>$word['search_num']+1]);
    	}else{
    		$this->save(['word'=>$word,'search_num'=>1]);
    	}
    }

    /**
     * 获取搜索排行
     */
    public function getSearchRank($limit=5)
    {
    	return $this->limit($limit)->order('search_num','desc')->select();
    }
}
