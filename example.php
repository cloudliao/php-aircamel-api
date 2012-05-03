<?php

	/**
	 *  This is a php-aircamel-api example.
	 *  @package php-aircamel-api
	 *  @desc    Get the API key via Official API website, http://api.aircamel.com.tw/apidoc/
     *  @see     http://api.aircamel.com.tw/apidoc/
	 *
	 **/
    
	$api_key = '1326711257';
	$api_secret = 'a0cfb4d6349957648c84ad5394b3e0e2';
    $output_type = 'serialize';
    
	require('aircamel_api.php');

	$AircamelAPI = new Aircamel_api($api_key, $api_secret);
    
	/**
	 ************************************************************************************************************************
	 * 分類商品列表
	 * 
	 ************************************************************************************************************************/

	echo "\n\n ----- 分類商品列表 ----- \n";
    $params['ct'] = 5459;
    $params['sb'] = 2;
    $params['dc'] = 0;
    $params['pg'] = 1;
    pre($AircamelAPI->category_list($params));
    unset($params);
	/**
	 ************************************************************************************************************************
	 * 店舖商品列表
	 *
	 ************************************************************************************************************************/

	echo "\n\n ----- 店舖商品列表 ----- \n";
    $params['sb'] = 2;
    $params['dc'] = 0;
    $params['pg'] = 1;
	pre($AircamelAPI->store_list($params));
    unset($params);
    
	/**
	 ************************************************************************************************************************
	 * 店舖交易列表
	 *
	 ************************************************************************************************************************/
    echo "\n\n ----- 店舖交易列表 ----- \n";
    $params['start_date'] = date('Y-m-d', strtotime('-3 month'));
    $params['end_date'] = date('Y-m-d');
    $params['pg'] = 1;
	pre($dealList = $AircamelAPI->store_deal_list($params));
	unset($params);

	/**
	 ************************************************************************************************************************
	 * 店舖交易明細
	 *
	 ************************************************************************************************************************/
    if($dealList['status'] == "success" && count($dealList['data'])>0){
        $dealId = $dealList['data'][0]['dealid'];
        
        echo "\n\n ----- 店舖交易明細 ----- \n";
        pre($AircamelAPI->store_deal_detail($dealId));
        unset($params);
    }
    
	/**
	 ************************************************************************************************************************
	 * 商品上架
	 *
	 ************************************************************************************************************************/
    echo "\n\n ----- 商品上架 ----- \n";
    $params['pname'] = 'Aircamel API package 測試商品上架'.date("Y-m-d H:i:s");
    $params['c_id'] = 62; # 參考 http://api.aircamel.com.tw/apidoc/?t=category
    $params['pamount'] = 1;
    $imagesContent = file_get_contents('logo.gif');
    $params['images'][] = base64_encode($imagesContent);
    $params['psellprice'] = 10; 
    $params['plocation'] = 1; # 參考 http://api.aircamel.com.tw/apidoc/?t=location
    $params['pcontent'] = '<table cellspacing="2" cellpadding="2" width="700" border="0">
                            <tbody>
                            <tr>
                            <td bgcolor="#DEF3FE" height="30">
                            <div align="center"><font color="#5B5B5B" size="2"><strong>商品規格</strong></font></div>
                            </td>
                            </tr>
                            <tr>
                            <td align="middle" width="626" bgcolor="#FCFFE1" height="27">
                            <p align="left"><font color="#5B5B5B" size="2">‧頻率響應：35HZ -
                            20KHZ/-3分貝<br>
                            ‧232瓦的總功率（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                            ‧超重低音喇叭尺寸：18.1 X 10.2 x 11.7英寸（46 ×25.8 x 29.7厘米）<br>
                            ‧衛星喇叭尺寸：4.25× 4.7× 6.25英寸（10.8× 12 ×15.9厘米）</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">低音音響：</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">‧8" 120W（IEC60268
                            -524小時連續額定）與耐用的橡膠環繞低音炮<br>
                            ‧四階封閉帶通箱體設計<br>
                            ‧橋雙60瓦的功率為120瓦D類放大器與集成的DSP（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                            ‧與100V的超高效不可或缺的電源 - 240V AC輸入</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">衛星喇叭：</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">‧雙向設計與可拆卸式音頻電纜<br>
                            ‧3"40W（IEC60628- 524小時連續評級）中音<br>
                            ‧1"16W（IEC60268 -524小時繼續評級）磁液冷卻絲綢振膜高音<br>
                            ‧56瓦每顆衛星（通過聯邦貿易委員會的“RMS”的方法來衡量）：<br>
                            ‧中檔40瓦D類放大器與集成的DSP<br>
                            ‧高音喇叭16瓦D類放大器與集成的DSP<br>
                            ‧I / O控制和編程</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">‧個人電腦輸入，重低音揚聲器<br>
                            ‧雙3.5mm輔助輸入（重低音揚聲器，桌面上的控制器之一）<br>
                            ‧3.5mm耳機輸出數字信號處理<br>
                            ‧有線桌面控制器與高清晰度彩色1.8"TFT顯示屏（4.6厘米）和多語言界面（英語，法語，意大利語，德語，西班牙語，俄語，葡萄牙語，日語和簡體中文）<br>

                            ‧藍光和DVD音頻，再現影院體驗影院音頻處理<br>
                            ‧動態的DSP方案和EQ曲線深夜聆聽，環境模擬，遊戲和電影風格的最佳享受</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">包裝內容</font></p>
                            <p align="left"><font color="#5B5B5B" size="2">‧重低音喇叭<br>
                            ‧兩個衛星喇叭<br>
                            ‧桌面控制器與6/1.8米電纜<br>
                            ‧3.5mm個人電腦輸入，立體聲 RCA線<br>
                            ‧兩個6"/1.8m衛星 ATX4連接器音頻電纜<br>
                            ‧電源線</font></p>
                            <p align="left"><font color="#5B5B5B" size="2"><br></font><strong><font color="#5B5B5B" size="2">原廠保固:兩年</font></strong></p>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            ';
    
    /** 以下非必填 **/
    
    $params['prefpic'] = array('http://www.aircamel.com.tw/images/celebration/logo2-trans.png'); 
    $params['spec'] = array( 0 => array (
                                    'size' => 'S', 'style' => '紅', 'count' => 1),
                             1 => array (
                                    'size' => 'M', 'style' => '紅', 'count' => 1), 
                             2 => array (
                                    'size' => 'L', 'style' => '紅', 'count' => 1), 
                             3 => array (
                                    'size' => 'S', 'style' => '白', 'count' => 1),
                             4 => array (
                                    'size' => 'M', 'style' => '白', 'count' => 1), 
                             5 => array (
                                    'size' => 'L', 'style' => '白', 'count' => 1), 
                             
                             );
    $params['pbuyamt'] = 1;
    $params['pmktprice'] = 100;
    $params['sdate'] = date("Y-m-d");
    $params['shour'] = date("H");
    $params['sminute'] = 0;
    $params['edate'] = date("Y-m-d", strtotime('+2 day'));
    $params['ehour'] = date("H");
    $params['age'] = 0;
    $params['length'] = 0.1;
    $params['width'] = 0.1;
    $params['height'] = 0.1;
    $params['weight'] = 1111;
    $params['plength'] = 0.1;
    $params['pwidth'] = 0.1;
    $params['pheight'] = 0.1; 
    $params['pweight'] = 1;
    $params['ptag'] = array(1,2,3,4);
    $params['tw_site'] = 1;
    $params['cn_site'] = 0;
    
    pre($goodAdd = $AircamelAPI->good_add($params));
    unset($params);
    
    /*
	 ******************************************
	 * 商品資訊
	 *
	 ******************************************/
    echo "\n\n ----- 商品資訊 ----- \n";
    if($goodAdd['status'] == 'success'){
        pre($AircamelAPI->good_show($goodAdd['data']['pid']));
        unset($params);
    }
     
	/*
	 ************************************************************************************************************************
	 * 商品修改
	 *
	 ************************************************************************************************************************/
    echo "\n\n ----- 商品修改 ----- \n";
    if($goodAdd['status'] == 'success'){
        $params['pid'] = $goodAdd['data']['pid'];
        
        /** 以下非必填 **/
        $params['pname'] = 'Aircamel API package 測試商品修改'.date("Y-m-d H:i:s");
        $params['c_id'] = 60; # 參考 http://api.aircamel.com.tw/apidoc/?t=category
        $params['pamount'] = 10;
        $imagesContent = file_get_contents('logo.gif');
        $params['images'][] = base64_encode($imagesContent);
        $params['psellprice'] = 9; 
        $params['plocation'] = 2; # 參考 http://api.aircamel.com.tw/apidoc/?t=location
        $params['pcontent'] = '修改商品 '.date("Y-m-d H:i:s").'<br>
                                <table cellspacing="2" cellpadding="2" width="700" border="0">
                                <tbody>
                                <tr>
                                <td bgcolor="#DEF3FE" height="30">
                                <div align="center"><font color="#5B5B5B" size="2"><strong>商品規格</strong></font></div>
                                </td>
                                </tr>
                                <tr>
                                <td align="middle" width="626" bgcolor="#FCFFE1" height="27">
                                <p align="left"><font color="#5B5B5B" size="2">‧頻率響應：35HZ -
                                20KHZ/-3分貝<br>
                                ‧232瓦的總功率（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                                ‧超重低音喇叭尺寸：18.1 X 10.2 x 11.7英寸（46 ×25.8 x 29.7厘米）<br>
                                ‧衛星喇叭尺寸：4.25× 4.7× 6.25英寸（10.8× 12 ×15.9厘米）</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">低音音響：</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧8" 120W（IEC60268
                                -524小時連續額定）與耐用的橡膠環繞低音炮<br>
                                ‧四階封閉帶通箱體設計<br>
                                ‧橋雙60瓦的功率為120瓦D類放大器與集成的DSP（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                                ‧與100V的超高效不可或缺的電源 - 240V AC輸入</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">衛星喇叭：</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧雙向設計與可拆卸式音頻電纜<br>
                                ‧3"40W（IEC60628- 524小時連續評級）中音<br>
                                ‧1"16W（IEC60268 -524小時繼續評級）磁液冷卻絲綢振膜高音<br>
                                ‧56瓦每顆衛星（通過聯邦貿易委員會的“RMS”的方法來衡量）：<br>
                                ‧中檔40瓦D類放大器與集成的DSP<br>
                                ‧高音喇叭16瓦D類放大器與集成的DSP<br>
                                ‧I / O控制和編程</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧個人電腦輸入，重低音揚聲器<br>
                                ‧雙3.5mm輔助輸入（重低音揚聲器，桌面上的控制器之一）<br>
                                ‧3.5mm耳機輸出數字信號處理<br>
                                ‧有線桌面控制器與高清晰度彩色1.8"TFT顯示屏（4.6厘米）和多語言界面（英語，法語，意大利語，德語，西班牙語，俄語，葡萄牙語，日語和簡體中文）<br>

                                ‧藍光和DVD音頻，再現影院體驗影院音頻處理<br>
                                ‧動態的DSP方案和EQ曲線深夜聆聽，環境模擬，遊戲和電影風格的最佳享受</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">包裝內容</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧重低音喇叭<br>
                                ‧兩個衛星喇叭<br>
                                ‧桌面控制器與6/1.8米電纜<br>
                                ‧3.5mm個人電腦輸入，立體聲 RCA線<br>
                                ‧兩個6"/1.8m衛星 ATX4連接器音頻電纜<br>
                                ‧電源線</font></p>
                                <p align="left"><font color="#5B5B5B" size="2"><br></font><strong><font color="#5B5B5B" size="2">原廠保固:兩年</font></strong></p>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                ';
        
        
        
        $params['prefpic'] = array('http://www.aircamel.com.tw/images/celebration/logo2-trans.png'); 
        // 商品修改不能改尺寸跟款式, 可增加規格
        $params['spec'] = array( 0 => array ( 'count' => 2),
                                 1 => array ( 'count' => 2), 
                                 2 => array ( 'count' => 2), 
                                 3 => array ( 'count' => 2),
                                 4 => array ( 'count' => 2), 
                                 5 => array ( 'count' => 2), 
                                );
        $params['pbuyamt'] = 1;
        $params['pmktprice'] = 100;
        
        // 商品修改不能更改上下架的時間
        $params['sdate'] = date("Y-m-d");
        $params['shour'] = date("H");
        $params['sminute'] = 0;
        $params['edate'] = date("Y-m-d", strtotime('+2 day'));
        $params['ehour'] = date("H");
        $params['age'] = 0;
        $params['length'] = 1;
        $params['width'] = 1;
        $params['height'] = 1;
        $params['weight'] = 1;
        $params['plength'] = 0.2;
        $params['pwidth'] = 0.2;
        $params['pheight'] = 0.2;
        $params['pweight'] = 2222; 
        $params['ptag'] = array(1,2,3,4);
        $params['tw_site'] = 1;
        $params['cn_site'] = 0;
         
        pre($goodSet = $AircamelAPI->good_set($params));
        unset($params);
    }
    if($goodSet['status'] == 'success'){
        echo "<a href='http://goods.aircamel.com.tw/".$goodAdd['data']['pid']."' target='_blank' >商品修改成功頁</a><br>";
    }
	
     
     /*
	 ************************************************************************************************************************
	 * 商品下架
	 *
	 ************************************************************************************************************************/
    echo "\n\n ----- 商品下架 ----- \n";
    if($goodAdd['status'] == 'success'){
        $params['pid'] = $goodAdd['data']['pid'];
        $params['note'] = 'Aircamel API Package 測試下架'.date("Y-m-d H:i:s");
        pre($goodDown = $AircamelAPI->good_down($params));
        unset($params);
    }
    
    /*
	 ************************************************************************************************************************
	 * 商品重新上架
	 *
	 ************************************************************************************************************************/
    echo "\n\n ----- 商品重新上架 ----- \n";
    if($goodDown['status'] == 'success'){
        $params['pid'] = $goodAdd['data']['pid'];
        
        /** 以下非必填 **/
        $params['pname'] = 'Aircamel API package 測試商品重新上架'.date("Y-m-d H:i:s");
        $params['c_id'] = 60; # 參考 http://api.aircamel.com.tw/apidoc/?t=category
        $params['pamount'] = 10;
        $imagesContent = file_get_contents('logo.gif');
        $params['images'][] = base64_encode($imagesContent);
        $params['psellprice'] = 9; 
        $params['plocation'] = 2; # 參考 http://api.aircamel.com.tw/apidoc/?t=location
        $params['pcontent'] = '商品重新上架 '.date("Y-m-d H:i:s").'<br>
                                <table cellspacing="2" cellpadding="2" width="700" border="0">
                                <tbody>
                                <tr>
                                <td bgcolor="#DEF3FE" height="30">
                                <div align="center"><font color="#5B5B5B" size="2"><strong>商品規格</strong></font></div>
                                </td>
                                </tr>
                                <tr>
                                <td align="middle" width="626" bgcolor="#FCFFE1" height="27">
                                <p align="left"><font color="#5B5B5B" size="2">‧頻率響應：35HZ -
                                20KHZ/-3分貝<br>
                                ‧232瓦的總功率（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                                ‧超重低音喇叭尺寸：18.1 X 10.2 x 11.7英寸（46 ×25.8 x 29.7厘米）<br>
                                ‧衛星喇叭尺寸：4.25× 4.7× 6.25英寸（10.8× 12 ×15.9厘米）</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">低音音響：</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧8" 120W（IEC60268
                                -524小時連續額定）與耐用的橡膠環繞低音炮<br>
                                ‧四階封閉帶通箱體設計<br>
                                ‧橋雙60瓦的功率為120瓦D類放大器與集成的DSP（通過聯邦貿易委員會的“RMS”的方法測量）<br>
                                ‧與100V的超高效不可或缺的電源 - 240V AC輸入</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">衛星喇叭：</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧雙向設計與可拆卸式音頻電纜<br>
                                ‧3"40W（IEC60628- 524小時連續評級）中音<br>
                                ‧1"16W（IEC60268 -524小時繼續評級）磁液冷卻絲綢振膜高音<br>
                                ‧56瓦每顆衛星（通過聯邦貿易委員會的“RMS”的方法來衡量）：<br>
                                ‧中檔40瓦D類放大器與集成的DSP<br>
                                ‧高音喇叭16瓦D類放大器與集成的DSP<br>
                                ‧I / O控制和編程</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧個人電腦輸入，重低音揚聲器<br>
                                ‧雙3.5mm輔助輸入（重低音揚聲器，桌面上的控制器之一）<br>
                                ‧3.5mm耳機輸出數字信號處理<br>
                                ‧有線桌面控制器與高清晰度彩色1.8"TFT顯示屏（4.6厘米）和多語言界面（英語，法語，意大利語，德語，西班牙語，俄語，葡萄牙語，日語和簡體中文）<br>

                                ‧藍光和DVD音頻，再現影院體驗影院音頻處理<br>
                                ‧動態的DSP方案和EQ曲線深夜聆聽，環境模擬，遊戲和電影風格的最佳享受</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">包裝內容</font></p>
                                <p align="left"><font color="#5B5B5B" size="2">‧重低音喇叭<br>
                                ‧兩個衛星喇叭<br>
                                ‧桌面控制器與6/1.8米電纜<br>
                                ‧3.5mm個人電腦輸入，立體聲 RCA線<br>
                                ‧兩個6"/1.8m衛星 ATX4連接器音頻電纜<br>
                                ‧電源線</font></p>
                                <p align="left"><font color="#5B5B5B" size="2"><br></font><strong><font color="#5B5B5B" size="2">原廠保固:兩年</font></strong></p>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                ';
        
        
        
        $params['prefpic'] = array('http://www.aircamel.com.tw/images/celebration/logo2-trans.png'); 
        // 商品重新上架不能改尺寸跟款式, 可增加規格
        $params['spec'] = array( 0 => array ( 'count' => 3),
                                 1 => array ( 'count' => 3), 
                                 2 => array ( 'count' => 3), 
                                 3 => array ( 'count' => 3),
                                 4 => array ( 'count' => 3), 
                                 5 => array ( 'count' => 3), 
                                );
        $params['pbuyamt'] = 1;
        $params['pmktprice'] = 100;
        
        $params['sdate'] = date("Y-m-d");
        $params['shour'] = date("H");
        $params['sminute'] = 0;
        $params['edate'] = date("Y-m-d", strtotime('+2 day'));
        $params['ehour'] = date("H");
        $params['age'] = 0;
        $params['length'] = 1;
        $params['width'] = 1;
        $params['height'] = 1;
        $params['weight'] = 1;
        $params['plength'] = 0.2;
        $params['pwidth'] = 0.2;
        $params['pheight'] = 0.2;
        $params['pweight'] = 2222; 
        $params['ptag'] = array(1,2,3,4);
        $params['tw_site'] = 1;
        $params['cn_site'] = 0;
        
        sleep(1);
        pre($AircamelAPI->good_reset($params));
        unset($params);
    }
    
    function pre($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
?>
