<?php
    $PW2_CONFIG = array(
        'db_scheme' => 'MySQL',
        'db_info' => array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '123456',
            'db' => 'phpwatch'
        ),
        'path' => dirname(__FILE__),
    );

    define('PW2_VERSION', '_CN 0.1 Base On 2.0.4 Beta');
    define('PW2_PATH', $PW2_CONFIG['path']);

    #mail_type sendmail || client
    // define('MAIL_TYPE','sendmail');
    define('MAIL_TYPE','client');
    if(MAIL_TYPE == 'client'){
        define('DEFAULT_EMAIL_NAME','');
        define('DEFAULT_EMAIL_ADDR','customer-care@youyoumob.com');
        define('EMAIL_SENDTYPE','smtp');
        define('EMAIL_HOST','220.181.97.132');
        define('EMAIL_PORT','25');
        // define('EMAIL_SSL',true);
        define('EMAIL_ACCOUNT','customer-care@youyoumob.com');
        define('EMAIL_PASSWORD','Yy20142015');
    }
    #建周短信平台
    define('SMS_TYPE','sms_cn');
    if(SMS_TYPE == 'sms_cn'){
        define('SMS_ACCOUNT','sdk_yymob');
        define('SMS_PASSWD','20140318');
        define('SMS_SIGN','【游友移动】');
    }

?>