<?php
// require_once('lib/nusoap.php');
require_once(PW2_PATH . '/src/jzsms/lib/nusoap.php');
class JZMessage{
	
	private $userAccount;
	private $passwd;
	private $signature;
	
	public function __construct($userAccount='sdk_yymob',$passwd='20140318',$signature='【游友移动】'){
		$this->userAccount=$userAccount;
		$this->passwd=$passwd;
		$this->signature=$signature;
	}
	public function sendMsg($mobileNum,$content){
		$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8      = false;
		$client->xml_encoding     = 'utf-8';
		$err = $client->getError();
		$params = array(
				'account' => $this->userAccount,
				'password' => $this->passwd,
				'destmobile' => $mobileNum,
				'msgText' => $content.$this->signature ,
		);
		$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
		return $result['sendBatchMessageReturn'];
	}

    //发送营销短信接口
	public function sendMarketingMsg($mobileNum,$content){
		$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8      = false;
		$client->xml_encoding     = 'utf-8';
		$err = $client->getError();
		$params = array(
			'account' => 'sdk_youyou',
			'password' => 20151215,
			'destmobile' => $mobileNum,
			'msgText' => $content.'退订回复T'.$this->signature ,
		);
		$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
		return $result['sendBatchMessageReturn'];
	}
    //获取上行信息
    public function getReceivedMsg(){
        $client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8      = false;
        $client->xml_encoding     = 'utf-8';
        $err = $client->getError();
        $params = array(
            'account' => $this->userAccount,
            'password' => $this->passwd
        );
        $result = $client->call('getReceivedMsg', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
        return $result['getReceivedMsgReturn'];
    }
	//获取短信余额
	public function getUserInfo($type){
		$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8      = false;
		$client->xml_encoding     = 'utf-8';
		$err = $client->getError();
		if($type=='marketing'){ //营销短信
            $params = array(
                'account' => 'sdk_youyou',
                'password' => 20151215,
            );
        }else{
            $params = array(  //正常订单短信
                'account' => $this->userAccount,
                'password' => $this->passwd,
            );
        }
		$result = $client->call('getUserInfo', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
		return $result['getUserInfoReturn'];
	}


}