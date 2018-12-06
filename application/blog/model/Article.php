<?php

namespace app\blog\model;

use think\Model;

/**
 * 文章数据表模型
 */

class Article extends Model
{
    protected $table = 'article';

    /**
     * 增加文章
     * @param  array $article_info 文章信息
     * @return  int article_id
     */
    public function addArticle($article_info)
    {
    	$this->startTrans();
    	try{
    		$article=$this->save($article_info);
    		$article_id = $this->article_id;
    		$this->commit();
    	} catch(/Exception $e){
    		$this->rollback;
    		return false;
    	}
    	$article_id = $this->article_id;

    }
}
