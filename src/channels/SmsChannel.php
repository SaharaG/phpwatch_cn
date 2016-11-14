<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');
    
    require_once(PW2_PATH . '/src/JZMessage.php');
    
    class SmsChannel extends Channel
    {
        public static $carriers = array(
            'SMS' => 'sms_cn',
            'Verizon' => 'vtext.com',
            'Cingular' => 'txt.att.net',
            'AT&amp;T' => 'txt.att.net'
        );

        public function getSubjectFormat()
        {
            return $this->config['subject'];
        }

        public function getMessageFormat()
        {
            return $this->config['message'];
        }

        public function getSubject($monitor)
        {
            return sprintf($this->config['subject'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function getMessage($monitor)
        {
            return sprintf($this->config['message'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function getNumber()
        {
            return $this->config['number'];
        }

        public function doNotify($monitor)
        {
            // mail($this->config['number'] . '@' . SmsChannel::gatewayFromCarrier($this->config['carrier']), $this->getSubject($monitor), $this->getMessage($monitor));
            $rand = "";//time(); //如不能发送相同内容的短信获取时间戳
            $values = array( 'loginfo' => '发送方式：SmsChannel，内容：' . $this->getMessage($monitor) . $rand);
            if(defined('SMS_TYPE') && SMS_TYPE== SmsChannel::gatewayFromCarrier($this->config['carrier'])){
                $jz = new JZMessage();
                $result = $jz->sendMsg($this->config['number'], $this->getMessage($monitor) . $rand);
                $values = array( 'loginfo' => '发送方式：wsdl短信，状态：'. $result . '，内容：' . $this->getMessage($monitor) . $rand);
                if( 0 > $result ){
                    sleep(3);
                    $result = $this->sendSMS($this->config['number'], $this->getMessage($monitor) . $rand);
                    $values = array( 'loginfo' => '发送方式：curl短信，状态：'. $result . '，内容：' . $this->getMessage($monitor) . $rand);
                }
            }else{
                mail($this->config['number'] . '@' . SmsChannel::gatewayFromCarrier($this->config['carrier']), $this->getSubject($monitor), $this->getMessage($monitor));
                $values = array( 'loginfo' => '发送方式：短信网关[' . SmsChannel::gatewayFromCarrier($this->config['carrier']) . ']，内容：' . $this->getMessage($monitor));
            }
            $GLOBALS['PW_DB']->executeInsert($values, 'loginfo');
        }

        public function getName()
        {
            return '手机短信';
        }

        public function getDescription()
        {
            return '发送一条手机短信.';
        }

        public function getCarrier()
        {
            return $this->config['carrier'];
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(strlen($data['subject']) == 0)
                $errors['subject'] = 'Subject cannot be blank.';
            $this->config['subject'] = $data['subject'];

            if(strlen($data['message']) == 0)
                $errors['message'] = 'Message cannot be blank.';
            $this->config['message'] = $data['message'];

            if(strlen($data['number']) < 7 || !is_numeric($data['number']) || intval($data['number']) < 0)
                $errors['number'] = 'Invalid number.  Must be numeric and at least 7 digits.';
            $this->config['number'] = $data['number'];

            if(!array_key_exists($data['carrier'], SmsChannel::$carriers))
                $errors['carrier'] = 'Invalid carrier.';
            $this->config['carrier'] = $data['carrier'];

            return $errors;
        }

        public function customProcessDelete()
        {
        }

        public function __toString()
        {
            return $this->config['number'] . ' (' . $this->config['carrier'] . ')';
        }

        public static function gatewayFromCarrier($carrier)
        {
            return SmsChannel::$carriers[$carrier];
        }

        public static function sendSMS($mobile, $msg)
        {
            $ch = curl_init();
            $post_data = array(
            "account" => SMS_ACCOUNT,
            "password" => SMS_PASSWD,
            "destmobile" => $mobile,
            "msgText" => $msg . SMS_SIGN,
            "sendDateTime" => ""
            );

            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);  
            curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
            $post_data = http_build_query($post_data);
            //echo $post_data;
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
            curl_setopt($ch, CURLOPT_URL, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage');
            //$info= 
            return $result = curl_exec($ch);
            curl_close($ch);
        }
    }
?>
