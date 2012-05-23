<?php
/**
 * 飛翔駱駝 API 串接模組
 * 
 * @package   php-aircamel-api
 * @license   Aircamel Inc.
 * @version   1.0
 * @link      http://api.aircamel.com.tw/apidoc/
 * @since     2012-04-06  
 *
 */
Class Aircamel_api {

    /**
     * API KEY
     * @var string $api_key
     */
    var $api_key;
    
    /**
     * API Secret 私鑰
     * @var string $password
     */
    var $api_secret;
    
    /**
     * aircamel api Url 的位置
     * @var string $apiUrl
     */
    var $apiUrl = 'http://api.aircamel.com.tw/';

    /**
     * 會員登入回傳的 token
     * @var string $token
     */
    var $token;
    
    /**
     * 取得 token 的時間
     * @var unknown_type
     */
    var $token_time;
    
    /**
     * 是否持續連線
     * @var bool $persistent
     */
    var $persistent = TRUE;
   
    /**
     * Current HTTP Server Response
     * @var JSON object $http_response
     */
    var $result;

    /**
     * 回傳失敗時，LOG 檔案路徑
     * @var string $log_path
     */
    var $log_path = '';
    
    /**
     * 是否已登錄
     * @var bool $login
     */
    var $login = FALSE;
    
    /**
     * 回傳錯誤的資訊陣列
     * @var array $error
     */
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
     * function log
     * 記錄錯誤發生時的 log
     * @param array $logAr 
     */
    function log($logAr = NULL){
        $array = str_replace("\n","",var_export($this->post_array, TRUE));
        error_log("[".date("Y-m-d_H:i:s")."] - {$logAr['action']} - {$logAr['code']} - $array\n", 3, $this->log_path);
    }

    /**
     * function curl_post
     * 透過 curl 來傳遞，成功會改變 result 的值，失敗會存入 log 及 改變 error 的值
     *
     * @param string $url
     * @param array $params
     * @return bool 
     */
    function curl_post($url, $params){
        
        $ch = curl_init();
        $this->post_array = $params;
               
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($params));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        if($this->output_type == 'json'){
            $result = json_decode($response, true);
        }else{
            $result = unserialize($response);
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
        if ($http_status != '200'){
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

    /**
     * 取得 Token
     * 取得 Token 的值，判斷 token_time 是否大於2小時，否則重新登錄
     * @return token 回傳 token 的值  
     */
    function get_token(){
        if( $this->token_time > 2*3600 ){
            
            if($this->member_login()){
                return $this->token;
            # 回傳失敗時    
            }else{
                return $this->token;
            }
            
        }else{
            return $this->token;
        }
    }
    
    /**
     * 存入 token 值
     * 存入 token 值以用來做持續連線，若 token_time 的時間小於2小時必須重新取得
     */
    function set_token(){
        $this->token = $this->result['token'];
        $this->token_time = $this->result['store_auth_date'];
        $this->login = TRUE;
    }
    
    /**
     * 會員登入 api
     * 必須先透過登入來取得 token 
     * @return bool
     */
    function member_login(){
        
        $post_array['action'] = 'member.login';
        $post_array['api_key'] = $this->api_key;
        $post_array['output_type'] = $this->output_type;
        $post_array['api_sign'] = md5($this->api_key.$this->api_secret.date('Y-m-d'));
        $api_url = $this->apiUrl . str_replace('.','/',$post_array['action']);
        
        if($this->curl_post($api_url, $post_array)){
            $this->set_token();
            return true;
        }else{
            return false;
        }
        
    }
    
    /**
     * POST api
     * 將陣列 POST 到 aircamel api, 成功回傳結果，失敗回錯錯誤結果
     * @param array $params
     * @return array
     */
    function post($params = NULL){
    	
    	$params['api_key'] = $this->api_key;
        $params['output_type'] = $this->output_type;
        $api_url = $this->apiUrl . str_replace('.','/',$params['action']);
        
        if($this->persistent && $params['action'] != 'member.login'){
            $params['token'] = $this->get_token();
        }
        
        if($this->curl_post($api_url, $params)){
            return $this->result;
        }else{
            return $this->error;
        }
        
    }
    
    /**
     * 分類商品列表
     * 取得該分類下的商品列表
     * @param array $params ct => 分類編號, sb => 排序方式, dc => 排序大至小, pg => 分頁
     * @return array 回傳結果
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=category.list
     */
    function category_list($params){
        if($params['ct'] == ""){
            return array('status' => false, 'msg' => 'ct 必要參數忘了填');
        }
        $params['action'] = 'category.list';
        return $this->post($params);
    }
    
    /**
     * 店舖商品列表
     * 取得店舖中的商品列表, 只能取得自己店舖的商品列表
     * @param array $params sb => 排序方式, dc => 排序大至小, pg => 分頁
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=store.list
     */
    function store_list($params){
        $params['action'] = 'store.list';
        return $this->post($params);
    }
    
    /**
     * 店舖交易列表
     * 取得我的店鋪內的6個月的全部訂單列表
     * @param array $params start_date => 開始日期, end_date => 結束日期, pg => 分頁
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=store.deal_list
     */
    function store_deal_list($params){
        $params['action'] = 'store.deal_list';
        return $this->post($params);
    }
    
    /**
     * 店舖交易明細
     * 取得我的店鋪內的單一訂單的明細資料
     * @param string $params $deal_id  交易編號
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=store.deal_detail
     */
    function store_deal_detail($deal_id){
        if($deal_id == ""){
            return array('status' => false, 'msg' => 'deal_id 必要參數忘了填');
        }
        $params['action'] = 'store.deal_detail';
        $params['deal_id'] = $deal_id;
        return $this->post($params);
    }
    /**
     * 店舖交易對帳作業
     * 將該筆訂單改為已對帳
     * @param string $params $deal_id  交易編號
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=store.deal_check
     */
    function store_deal_check($deal_id){
        if($deal_id == ""){
            return array('status' => false, 'msg' => 'deal_id 必要參數忘了填');
        }
        $params['action'] = 'store.deal_check';
        $params['deal_id'] = $deal_id;
        return $this->post($params);
    }
    /**
     * 店舖交易出貨作業
     * 將該筆訂單改為已出
     * @param array $params deal_id  交易編號, ship_type 運送方式, ship_no 物流追蹤碼, date 出貨日期 (YYYY-mm-dd), time 時間 (1~24), descript 出貨說明, note 出貨備註  
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=store.deal_ship
     */
    function store_deal_ship($params){
         
        $params['action'] = 'store.deal_ship';
        if($params['deal_id'] == "" || $params['ship_type'] == "" || $params['date'] == "" || $params['time'] == ""){
            return array('status' => false, 'msg' => '必要參數忘了填');
        }
        return $this->post($params);
    }
    
    /**
     * 商品上架
     * 商品上架到飛翔駱駝
     * 傳遞方式： spec中，['size'], ['style'] -> 擇一而填或兩者必填；['count']不能為空。
     * images 不能為空(至少要有一個值) 
     * @param array $params 
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=good.add
     */
    function good_add($params){
        $params['action'] = 'good.add';
        
        if(isset($params['spec'])){
        	if( count($params['spec']) == 0 ){
        		unset($params['spec']);	
        	}else{
        		foreach($params['spec'] as $v){
		        	if ($v['style'] == "" && $v['size'] == ""){
		        		return array('status' => false, 'msg' => 'spec 中 style, size 空值錯誤');
		        	}elseif( (int)$v['count'] <= 0){
		        		return array('status' => false, 'msg' => 'spec 中 count 空值錯誤');
		        	}
        		}
        	}
        	
        }
        if( !is_array($params['images']) || $params['images'][0] == "" ){
        	return array('status' => false, 'msg' => 'images 空值');
        }
        
        if( is_array($params['prefpic']) && count($params['prefpic'])>0 ){
        	foreach($params['prefpic'] as $v){
        		if($v == "")$error++;
        	}
        	if($error>=5)unset($params['prefpic']);
        	
        }else{
        	unset($params['prefpic']);
        }
        
        return $this->post($params);
    }
    
    /**
     * 商品修改
     * 修改飛翔駱駝商品 
     * @param array $params 商品修改 array
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=good.set
     */
    function good_set($params){
    	
    	# 檢查變數是否符合
    	if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
    	
        if(is_array($params['spec']) && count($params['spec']) == 0){
        	unset($params['spec']);	
        }elseif( !is_array($params['spec']) ){
        	unset($params['spec']);
        }
        
        if( is_array($params['images']) && $params['images'][0] == "" ){
        	unset($params['images']);
        }
        
        if( is_array($params['prefpic']) && $params['prefpic'][0] == "" ){
        	unset($params['prefpic']);
        }
        
        $params['action'] = 'good.set';
        return $this->post($params);
    }
    
    /**
     * 商品重新上架 
     * 重新下架商品，檢查與商品修改一致 
     * @param array $params 上架商品 array
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=good.reset
     */
    function good_reset($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        
    # 檢查變數是否符合
    	if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        
        if(is_array($params['spec']) && count($params['spec']) == 0){
        	unset($params['spec']);	
        }elseif(!is_array($params['spec'])){
        	unset($params['spec']);
        }
        
        if( is_array($params['images']) && $params['images'][0] == "" ){
        	unset($params['images']);
        }
        
        if( is_array($params['prefpic']) && $params['prefpic'][0] == "" ){
        	unset($params['prefpic']);
        }
        
        $params['action'] = 'good.reset';
        return $this->post($params);
    }
    
    /**
     * 商品資訊 
     * 商品資訊，只能找出自己店舖中的商品。
     * @param string $pid 商品編號
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=good.show
     */
    function good_show($pid){
        if($pid == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.show';
        $params['pid'] = $pid;
        return $this->post($params);
    }
    
    /**
     * 商品下架
     * 商品下架，若 pid 中所傳的商品已下架，則只更新下架理由，其它不做異動。
     * @param array $params pid => 商品編號, note => 下架理由
     * @return array
     * @link http://api.aircamel.com.tw/apidoc/?t=doc&action=good.down
     */
    function good_down($params){
        if($params['pid'] == ""){
            return array('status' => false, 'msg' => 'pid 必要參數忘了填');
        }
        $params['action'] = 'good.down';
        return $this->post($params);
    }
    
}