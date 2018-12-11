<?php

namespace app\blog\model;

use think\Model;

/**
 * 网站链接数据表模型
 */
class WebsiteLink extends Model
{
    protected $table = 'website_link';

    /**
     * 获取所有链接信息
     */
    public function getAllLink()
    {
    	$link_list = $this->field('id,name,logo,url')->wher('status'=>0)->order(['sort'=>'desc','create_at'=>'asc'])->select();
    	return $link_list;
    }
}
