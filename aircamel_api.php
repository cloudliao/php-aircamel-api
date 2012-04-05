<?php
/**
 * 飛翔駱駝 API 串接模組
 * @package   php-aircamel-api
 * @license   
 * @link      
 *
 */
Class Aircamel_api {

    /**
     * API KEY
     * @var $api_key
     */
    var $api_key;
    
    /**
     * Password
     * @var string $password
     */
    var $api_secret;

    /**
     * the array send to Plurk.com
     * @var $post_array
     */
    var $post_array = array();
    
    /**
     * the array send to Plurk.com
     * @var $post_array
     */
    var $token;
    
    /**
     * the array send to Plurk.com
     * @var $post_array
     */
    var $persistent = TRUE;
    
    /**
     * Current HTTP Status Code
     * @var int $http_status
     */
    var $http_status;

    /**
     * Current HTTP Server Response
     * @var JSON object $http_response
     */
    var $result;

    /**
     * log file path.
     * set your custom log path.
     * @var string $log_path
     */
    var $log_path = '';
    var $login = FALSE;
    var $error = array();
    
    function __construct($api_key , $api_secret) {
        global $log_path, $output_type;
        
        $this->api_key = ($api_key != "") ? $api_key : '';
        $this->api_secret = ($api_secret != "") ? $api_secret : '';
        $this->output_type = ($output_type != "") ? $output_type : 'json';
        $this->log_path = ($log_path != "") ? $log_path : NULL;
        
        if(isset($params) && is_array($params)){
            $this->post_array = $params;
        }
        
        if($this->login == FALSE){
            $this->member_login();
        }
    }

    /**
     * funciton log
     *
     * @param $logAr
     */
    function log($logAr = NULL){
    
        if( ! isset($this->log_path)) $this->log_path = 'err.log';
        $array = str_replace("\n","",var_export($this->post_array, TRUE));

        error_log("[".date("Y-m-d_H:i:s")."] - {$logAr['action']} - {$logAr['code']} - $array\n", 3, $this->log_path);
    }

    /**
     * function api_post
     * Connect to aircamel api
     *
     * @param $url
     * @param $array
     * @return JSON object
     */
    function curl_post($params){
    
        $params['api_key'] = $this->api_key;
        $params['output_type'] = $this->output_type;
        if($this->persistent && $params['action'] != 'member.login'){
            $params['token'] = $this->get_token();
        }
        
        
        $ch = curl_init();

        $this->post_array = $params;
        $api_url = 'http://api.aircamel.com.tw/'.str_replace('.','/',$params['action']);
        
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($params));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        curl_setopt($ch, CURLOPT_USERAGENT, PLURK_AGENT);

        if(isset($this->proxy))
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);

        if(isset($this->proxy_auth))
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth);

        $response = curl_exec($ch);
        if($this->output_type == 'json'){
            $result = json_decode($response, true);
        }else{
            $result = unserialize($response);
        }
        $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
        if ($this->http_status != '200'){
            $this->error = $this->http_status;
            if($this->log_path !="") $this->log();
            return false;
        }

        if($result['status'] == 'success'){
            $this->result = $result;
            return true;
        }else{
            $this->error = $result;
            if( $this->log_path != "" ) $this->log($result);
            
            return false;
        }
        
        
    }

    
    function get_token(){
        
        if( $this->token_time > 2*3600 ){
            
            if($this->member_login()){
                return $this->token;
            #todo:    
            }else{
                return $this->token;
            }
            
        }else{
        
            return $this->token;
        }
        
    }
    
    function set_token(){
        $this->token = $this->result['token'];
        $this->token_time = $this->result['store_auth_date'];
        $this->login = TRUE;
    }
    
    function member_login(){
        
        $post_array['action'] = 'member.login';
        $post_array['api_key'] = $this->api_key;
        $post_array['api_sign'] = md5($this->api_key.$this->api_secret.date('Y-m-d'));
        
        if($this->curl_post($post_array)){
            $this->set_token();
            return true;
        }else{
            return false;
        }
        
    }
    
    function post($params = NULL){
        
        if($this->curl_post($params)){
            return $this->result;
        }else{
            return $this->error;
        }
        
    }
    
    function category_list($params){
        if($params['ct'] == ""){
            return array('status' => false, 'msg' => 'ct 必要參數忘了填');
        }
        $params['action'] = 'category.list';
        return $this->post($params);
    }
    
    function store_list($params){
        $params['action'] = 'store.list';
        return $this->post($params);
    }
    
    function store_deal_list($params){
        $params['action'] = 'store.deal_list';
        return $this->post($params);
    }
    
    function store_deal_detail($params){
        if($params['dealid'] == ""){
            return array('status' => false, 'msg' => 'dealid 必要參數忘了填');
        }
        $params['action'] = 'store.deal_detail';
        return $this->post($params);
    }
    
    function good_add($params){
        $params['action'] = 'good.add';
        return $this->post($params);
    }
    
    function good_set($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.set';
        return $this->post($params);
    }
    
    function good_reset($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.reset';
        return $this->post($params);
    }
    
    function good_show($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.show';
        return $this->post($params);
    }
    
    function good_down($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.down';
        return $this->post($params);
    }
    
}