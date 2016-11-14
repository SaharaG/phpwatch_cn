<?php
    require_once(PW2_PATH . '/src/Monitor.php');
    require_once(PW2_PATH . '/src/Channel.php');
    
    class WeChatChannel extends Channel
    {
        public function getSubjectFormat()
        {
            return $this->config['subject'];
        }

        public function getMessageFormat()
        {
            return $this->config['message'];
        }

        private function getSubject($monitor)
        {
            return sprintf($this->config['subject'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        private function getMessage($monitor)
        {
            return sprintf($this->config['message'], $monitor->getHostname(), $monitor->getPort(), $monitor->getAlias());
        }

        public function getSCKey()
        {
            return $this->config['sckey'];
        }

        public function doNotify($monitor)
        {   
            $this->sc_send($this->getMessage($monitor), $this->getSubject($monitor), $this->config['sckey']);
            //记录日志
            $values = array( 'loginfo' => '发送方式：微信['. $this->config['sckey'] . ']:' . $this->getMessage($monitor));
            $GLOBALS['PW_DB']->executeInsert($values, 'loginfo');
        }

        public function getName()
        {
            return '微信';
        }

        public function getDescription()
        {
            return '微信提醒.需要在Server酱网站生成SCKEY';
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(strlen($data['subject']) == 0)
                $errors['subject'] = 'Subject cannot be blank.';
            $this->config['subject'] = $data['subject'];

            if(strlen($data['message']) == 0)
                $errors['message'] = 'Message cannot be blank.';
            $this->config['message'] = $data['message'];

            if(@eregi("^[a-zA-Z0-9_]+$]", $data['sckey']))
                $errors['sckey'] = 'sckey is invalid.';
            $this->config['sckey'] = $data['sckey'];

            return $errors;
        }

        public function customProcessDelete()
        {
        }
        
        public function __toString()
        {
            return $this->config['sckey'];
        }

        public function sc_send(  $text , $desp = '' , $key = ''  )
        {
            $postdata = http_build_query(
            array(
                'text' => $text,
                'desp' => $desp
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        return $result = file_get_contents('http://sc.ftqq.com/'.$key.'.send', false, $context);
        }
    }
?>
