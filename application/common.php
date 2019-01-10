<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('__success')) {

    /**
     * 成功时返回的信息
     * @param $msg 消息
     * @return \think\response\Json
     */
    function __success($msg, $data = '') {
        return json(['code' => 0, 'msg' => $msg, 'data' => $data]);
    }
}

if (!function_exists('__error')) {

    /**
     * 错误时返回的信息
     * @param $msg 消息
     * @return \think\response\Json
     */
    function __error($msg, $data = '') {
        return json(['code' => 1, 'msg' => $msg, 'data' => $data]);
    }
}

if (!function_exists('get_ip')) {

    /**
     * 获取用户ip地址
     * @return array|false|string
     */
    function get_ip() {
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
}

if (!function_exists('getLoginInfo')) {

    /**
     * 根据ip获取地理位置
     * @param string $ip
     * @return mixed
     */
    function getLoginInfo($ip = '') {
        empty($ip) ? $ip = get_ip():$ip='';
        if($ip===''){
            $data = [
            'login_ip'      => $ip,
            'login_area' => '',
        ];
        }else{
            $areafromip = json_decode(file_get_contents("http://freeapi.ipip.net/{$ip}"));
            $login_area = $areafromip[0].'-'.$areafromip[1].'-'.$areafromip[2];
            $data = [
                'login_ip'      => $ip,
                'login_area' => '',
            ];
        }  
            return $data;
        }
}