<?php
    class HttpValidationMonitor extends Monitor
    {
        public static $MODE_DOES_CONTAIN = 0x01;
        public static $MODE_DOESNT_CONTAIN = 0x02;

        public static $MATCH_FIND = 0x01;
        public static $MATCH_REGEX = 0x02;

        public function getName()
        {
            return 'HTTP验证监控';
        }

        public function getDescription()
        {
            return '这种类型的监控试图建立一个HTTP连接到web服务并
                    验证返回的信息。例如,如果检测响应中填写的是“ERROR”则匹配返回信息来确定服务是否离线。';
        }

        public function getTimeout()
        {
            return $this->config['timeout'];
        }

        public function getMatchString()
        {
            return $this->config['match_str'];
        }

        public function getMatchMethod()
        {
            return $this->config['match_method'] ? $this->config['match_method'] : HttpValidationMonitor::$MATCH_FIND;
        }

        public function getMode()
        {
            return $this->config['mode'] ? $this->config['mode'] : HttpValidationMonitor::$MODE_DOES_CONTAIN;
        }

        public function isValid($resp)
        {
            // echo $resp;
            if($this->getMatchMethod() == HttpValidationMonitor::$MATCH_FIND)
            {
                
                if(strpos($resp, $this->config['match_str']) === false){
                    return false;
                }
                return true;
            }
            else
            {
                if(preg_match($this->config['match_str'], $resp)){
                    return true;
                }
                return false;
            }
        }

        public function queryMonitor()
        {
            $sock = @fsockopen($this->hostname, $this->port, $errno, $errstr, intval($this->config['timeout']));
            if(!$sock)
                return false;
            $req  = "GET / HTTP/1.1\r\n";
            $req .= "Host: " . $this->hostname . "\r\n";
            $req .= "Connection: Close\r\n\r\n";
            fwrite($sock, $req);

            $resp = '';
            while(!feof($sock))
            {
                $resp .= fread($sock, 1024);
            }
            $valid = $this->isValid($resp);
            fclose($sock);
            if($this->getMode() == HttpValidationMonitor::$MODE_DOES_CONTAIN)
                return $valid;
            else
                return !$valid;
        }

        public function customProcessAddEdit($data, $errors)
        {
            if(!is_numeric($data['timeout']) || intval($data['timeout']) <= 0)
                $errors['timeout'] = 'Timeout must be a positive integer.';
            $this->config['timeout'] = intval($data['timeout']);
            if(strlen($data['match_str']) == 0)
                $errors['match_str'] = 'String to match cannot be blank.';
            $this->config['match_str'] = $data['match_str'];
            $this->config['mode'] = $data['mode'];
            $this->config['match_method'] = $data['match_method'];
            return $errors;
        }

        public function customProcessDelete()
        {
        }
    }
?>
