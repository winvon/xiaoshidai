<?php
/**
 * Created by PhpStorm.
 * User: Enson
 * Date: 2018/12/27
 * Time: 15:10
 */

namespace common\helpers;


class RespDataFormat
{
    CONST RESP_JSON = 'json';
    CONST RESP_XML = 'xml';

    /**
     * 响应浏览器接口数据，数据格式默认json（json、xml）
     * @param null $data 数据
     * @param int $status 响应状态 默认0，请看文档-全局状态码
     * @param string $msg 信息
     * @return format data string
     */
    public static function respBrowser($data=[],$status=0,$msg='',$append=false){
        if(!is_numeric($status)){
            return;
        }
        $format=isset($_GET['format']) ? $_GET['format'] : self::RESP_JSON;
        if($append){
            $data['status']=$status;
            $data['msg']=empty($msg) ? WeHelper::getRespStatus($status) : $msg;
        }else{
            $data=array(
                'status'=>$status,
                'msg'=>empty($msg) ? WeHelper::getRespStatus($status) : $msg,
                'data'=>$data
            );
        }
        switch($format){
            case self::RESP_JSON: return self::respClientJSON($data);
            case self::RESP_XML: return self::respClientXML($data);
        }
    }

    /**
     * 响应服务器接口数据，数据格式默认json,共选值：RespDataFormat::RESP_JSON、RespDataFormat::RESP_XML
     * @param array $data
     * @param string $format
     * @return format data string
     */
    public static function respServer($data=array(),$format=self::RESP_JSON){
        switch($format){
            case self::RESP_JSON: return json_encode($data);
            case self::RESP_XML: header("Content-Type:text/xml");return self::xmlToEncode($data);
        }
    }

    private static function respClientJSON($data){
//        header("Content-Type:application/json");
        return json_encode($data);
        exit;
    }

    private static function respClientXML($data){
        header("Content-Type:text/xml");

        $xml="<?xml version='1.0' encoding='UTF-8'?>";
        $xml .="<root>";
        $xml .=self::xmlToEncode($data);
        $xml .="</root>";

        return $xml;
        exit;
    }

    private static function xmlToEncode($data){
        $xml="";
        foreach($data as $k=>$v){
            if(is_array($v)){
                $xml .="<{$k}>".self::xmlToEncode($v)."</{$k}>";
            }else{
                if(is_numeric($k)){
                    // $xml .="<item id='{$k}'>".(strpos($v,'<') !== false ? "<![CDATA[".$v."]]>" : $v)."</item>";
                    $xml .="<item id='{$k}'>".(is_string($v) ? "<![CDATA[".$v."]]>" : $v)."</item>";
                }else{
                    // $xml .="<{$k}>".(strpos($v,'<') !== false ? "<![CDATA[".$v."]]>" : $v)."</{$k}>";
                    $xml .="<{$k}>".(is_string($v) ? "<![CDATA[".$v."]]>" : $v)."</{$k}>";
                }
            }
        }
        return $xml;
    }
}