<?php

class WPAdm_GA
{
    const URL_GA_SERVER = 'http://secure.wpadm.com/ga/';
    const URL_GA_AUTH = 'http://secure.wpadm.com/ga.php';
    const URL_GA_PUB_KEY = 'http://secure.wpadm.com/ga/getPubKey';

    const EMAIL_SUPPORT = 'support@wpadm.com';

    const REQUEST_PARAM_NAME = 'wpadm_ga_request';

    public static function visitView() {
        self::processingPostRequest();

        WPAdm_GA_View::$subtitle = 'Audience Overview';

        if($template = self::getErrorTemplate()) {
            WPAdm_GA_View::$content_file = $template;
        } else {
            if(WPAdm_GA_Options::gaTokenIsExpired() && !isset($_GET['token'])) {
                ob_clean();
                $location =  self::URL_GA_AUTH . '?redirect=' . urlencode(self::getCurUrl());
                header("Location: $location");
            }
            WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'visit.php';
        }
        require  WPADM_GA__VIEW_LAYOUT;
    }




    public static function usersView() {
        self::processingPostRequest();
        WPAdm_GA_View::$subtitle = 'Visitors  Overview';

        if($template = self::getErrorTemplate()) {
            WPAdm_GA_View::$content_file = $template;
        } else {
            if(WPAdm_GA_Options::gaTokenIsExpired()  && !isset($_GET['token'])) {
                ob_clean();
                $location = self::URL_GA_AUTH . '?redirect=' . urlencode(self::getCurUrl());
                header("Location: $location");
            }
            WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'users.php';
        }
        require  WPADM_GA__VIEW_LAYOUT;
    }

    public function sourceView() {
        self::processingPostRequest();
        WPAdm_GA_View::$subtitle = 'Source stat';
        WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'source.php';
        require  WPADM_GA__VIEW_LAYOUT;
    }

    public function settingsView() {
        self::processingPostRequest();
        WPAdm_GA_View::$subtitle = 'settings';

        if($template = self::getErrorTemplate()) {
            WPAdm_GA_View::$content_file = $template;
        } else {
            if(WPAdm_GA_Options::gaTokenIsExpired()  && !isset($_GET['token'])) {
                ob_clean();
                $location = self::URL_GA_AUTH . '?redirect=' . urlencode(self::getCurUrl());
                header("Location: $location");
            }
            WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'settings.php';
        }



        //GA Account
        $ga_accout_form = new wpadmForm();
        $ga_accout_form->setValue('ga-id', WPAdm_GA_Options::getGAId());
        $ga_accout_form->setValue('ga-webPropertyId', WPAdm_GA_Options::getGAWebPropertyId());
        $ga_accout_form->setValue('ga-url', WPAdm_GA_Options::getGAUrl());
        $ga_accout_form->setValue('ga-enableCode', WPAdm_GA_Options::getGAEnableCode());

        if('POST' == strtoupper($_SERVER['REQUEST_METHOD'])
            && isset($_POST['form_name'])
            && 'ga-account' == $_POST['form_name']
        ) {
	        if ('disconnect' == filter_input(INPUT_POST, 'ga-disconnect-btn', FILTER_SANITIZE_STRING)) {
		        self::googleAnalyticsDisconnect();
		        return;
	        }
            $id = filter_input(INPUT_POST, 'ga-id', FILTER_SANITIZE_NUMBER_INT);
            $url = filter_input(INPUT_POST, 'ga-url', FILTER_SANITIZE_URL);
            $webPropertyId = filter_input(INPUT_POST, 'ga-webPropertyId', FILTER_SANITIZE_STRING);
            $enableCode = (int)filter_input(INPUT_POST, 'ga-enableCode', FILTER_SANITIZE_NUMBER_INT);
            $enableCode = ($enableCode) ? 1 : 0;

            WPAdm_GA_Options::setGAId($id);
            WPAdm_GA_Options::setGAUrl($url);
            WPAdm_GA_Options::setGAWebPropertyId($webPropertyId);
            WPAdm_GA_Options::setGAEnableCode($enableCode);
            
            $ga_accout_form->setValue('ga-id', $id);
            $ga_accout_form->setValue('ga-webPropertyId', $webPropertyId);
            $ga_accout_form->setValue('ga-enableCode', $enableCode);

            //redirect to stat
            ob_clean();
            
            if (isset($_GET['modal'])) {
                echo '<script> top.frames.location.reload(false);</script>';    
            } else {
                $location = admin_url() . 'options-general.php?page=wpadm-ga-menu-visit';
                header("Location: $location");
            }
            exit;
        }
        
        
        
//        $wpadm_account_form = new wpadmAuthForm();

//        if('POST' == strtoupper($_SERVER['REQUEST_METHOD'])
//            && isset($_POST['form_name'])
//            && 'wpadm-account' == $_POST['form_name']
//        ) {
//            $form_data = array(
//                'wpadm_username' => $_POST['wpadm_username'],
//                'wpadm_password' => $_POST['wpadm_password'],
//                'wpadm_password_confirm' => $_POST['wpadm_password_confirm'],
//                'wpadm_imnewuser_checkbox' => (isset($_POST['wpadm_imnewuser_checkbox'])&& $_POST['wpadm_imnewuser_checkbox'] == 1) ? 1 :0
//            );
//            $wpadm_account_form->setData($form_data);
//
//            if ($wpadm_account_form->isValid()) {
//
//                WPAdm_GA_Api::register();
//                echo 'doit';
//                exit;
//            }
//        }



//        if('POST' == strtoupper($_SERVER['REQUEST_METHOD'])
//            && isset($_POST['form_name'])
//            && 'ga-account-logout' == $_POST['form_name']
//        ) {
//
//            $url = get_option('siteurl');
//            $p_url = parse_url($url);
//            $postdata = array(
//                'host' => $p_url['host'],
//            );
//
//            $response = wp_remote_post(self::URL_GA_SERVER . 'logout', array(
//                'method' => 'POST',
//                'timeout' => 45,
//                'body' => self::getParamsForRequest($postdata)
//            ));
//
//            if ( is_wp_error( $response ) ) {
//                //todo: отработать исключение
//                $error_message = $response->get_error_message();
//                echo "Something went wrong: $error_message";
//            } else {
//
//            }
//
//            echo '<!-- start dump --><pre><small>' . __FILE__ . "</small>\n";
//            print_r($response['body']);
//            echo '</pre><!-- end dump -->';
//            exit;
//
//
//        }



        require  WPADM_GA__VIEW_LAYOUT;
    }

    public static function processingPostRequest()
    {
        if ('POST' == strtoupper($_SERVER['REQUEST_METHOD'])
            && isset($_POST['wpadm_ga_manual_tracking_code'])
        ) {
            $code = trim($_POST['wpadm_ga_manual_tracking_code']);
            if ($code) {
                update_option('wpadm_ga_manual_tracking_code', $code);
            } else {
                delete_option('wpadm_ga_manual_tracking_code');
            }
        }
    }


    static function adminNotice() {

    }

    public static function plugin_activation() {
        //get pub key
        $response = wp_remote_post(self::URL_GA_PUB_KEY, array(
            'method' => 'POST',
            'timeout' => 45,
            'body' => array('refer'=>site_url())
        ));

        if ( is_wp_error( $response ) ) {
            ////
            $error_message = $response->get_error_message();
        } else {
            preg_match("|(-----BEGIN PUBLIC KEY-----.*-----END PUBLIC KEY-----)|Uis", $response['body'], $m);
            if (isset($m[1]) && !empty($m[1])) {
                update_option('wpadm_ga_pub_key', $m[1]);
            }
        }
        
        self::cron_activation();
    }
    
    public static function plugin_deactivation() {
        delete_option('wpadm_ga_pub_key');
        //delete_option('wpadm_ga');
        self::cron_deactivation();
        //todo: delete cahce table
    }

    public static function cron_activation() {
        add_action('wpadm_ga_cache_clear', array('Wpadm_GA_Cache', 'cronRemoveExpiredCache'));
        wp_clear_scheduled_hook('wpadm_ga_cache_clear');
        wp_schedule_event(time(), 'daily', 'wpadm_ga_cache_clear');
    }

    public static function cron_deactivation() {
        wp_clear_scheduled_hook('wpadm_ga_cache_clear');
    }


    public static function init() {
        ob_start();
        self::requireFiles();
        self::checkDB();
        $request_name = self::REQUEST_PARAM_NAME;
        if( isset( $_POST[$request_name] ) && ! empty ( $_POST[$request_name] ) ) {
            self::proccessRequest();
        }

        
//        if ($show_notice_5star === false) {
//            
//        }
        

    }

    public static function setDtStartWork() {
        if (!get_option('wpadm-ga-first_time')) {
            update_option('wpadm-ga-first_time', time());
        }
    }

    protected static function proccessRequest() {
        $request_name = self::REQUEST_PARAM_NAME;
        $params = unserialize(base64_decode($_POST[$request_name]));

        $v = self::verifySignature($params['sign'], get_option('wpadm_ga_pub_key'), md5(serialize($params['data'])));

        $request = $params['data'];

        if($v && isset($request['action'])) {
            switch($request['action']) {
                case 'access_token':
                    WPAdm_GA_Options::setGAAccessToken($request['data']['access_token']);
                    WPAdm_GA_Options::setGAExpiresIn($request['data']['expires_in']);
                    WPAdm_GA_Options::setGACreated($request['data']['created']);

                    $ga_id = WPAdm_GA_Options::getGAId();
                    if (isset($request['data']['property']) && empty($ga_id)
                        && isset($request['data']['property']['ga_id']) && !empty($request['data']['property']['ga_id'])
                        && isset($request['data']['property']['ga_url']) && !empty($request['data']['property']['ga_url'])
                        && isset($request['data']['property']['ga_webPropertyId']) && !empty($request['data']['property']['ga_webPropertyId'])
                    ) {
                        WPAdm_GA_Options::setGAUrl($request['data']['property']['ga_url']);
                        WPAdm_GA_Options::setGAId($request['data']['property']['ga_id']);
                        WPAdm_GA_Options::setGAWebPropertyId($request['data']['property']['ga_webPropertyId']);
                    }

                    header("HTTP/1.0 201 Created");
                    break;
            }
        }
        exit;
    }


    protected static function googleAnalyticsDisconnect() {
        self::sendRequest(self::URL_GA_SERVER . 'disconnect', array(
            'action' => 'disconnect',
            'refer'=>self::getCurUrl()
        ));

	    WPAdm_GA_Options::setGAId(null);
	    WPAdm_GA_Options::setGAWebPropertyId(null);
	    WPAdm_GA_Options::setGAUrl(null);
	    WPAdm_GA_Options::setGAAccessToken(null);
	    WPAdm_GA_Options::setGACreated(null);
	    WPAdm_GA_Options::setGAEnableCode(null);
	    WPAdm_GA_Options::setGATypeCode(null);

	    header('location: ' . self::getCurUrl());

    }

    protected static function sendRequest($url, array $params) {
        $data = base64_encode(serialize($params));
        $req = array(
            'data' => $data,
            'sign' => self::getSignature(get_option('wpadm_ga_pub_key'), $data),
            'refer'=>self::getCurUrl(),
        );

	    
	    echo '<!-- start dump --><pre><small>' . __FILE__ . "</small>\n";
	    print_r($url);
	    echo '</pre><!-- end dump -->';
	    
        $response = wp_remote_post($url, array(
            'method' => 'POST',
            'timeout' => 45,
            'body' => $req
        ));

//        if ( is_wp_error( $response ) ) {
//            $error_message = $response->get_error_message();
//        } else {
//        }

    }


    protected static function requireFiles() {
        require_once( WPADM_GA__PLUGIN_DIR . 'class.wpadm-ga-options.php' );
        require_once( WPADM_GA__PLUGIN_DIR . 'class.wpadm-ga-view.php' );
        require_once( WPADM_GA__PLUGIN_DIR . 'class.wpadm-ga-cache.php' );

        require_once( WPADM_GA__PLUGIN_DIR . 'form'.DIRECTORY_SEPARATOR.'wpadmForm.php' );
        require_once( WPADM_GA__PLUGIN_DIR . 'form'.DIRECTORY_SEPARATOR.'wpadmAuthForm.php' );
        
        
    }

    public static function registerPluginStyles() {
        wp_register_style( 'wpadm-ga-css', plugins_url(WPADM_GA__PLUGIN_NAME. '/view/scripts/wpadm-ga.css' ) );
        wp_enqueue_style( 'wpadm-ga-css' );

        wp_register_style( 'wpadm-daterangepicker-css', plugins_url(WPADM_GA__PLUGIN_NAME. '/view/scripts/daterangepicker/daterangepicker.css' ) );
        wp_enqueue_style( 'wpadm-daterangepicker-css' );

    }

    public static function registerPluginScripts() {
        wp_register_script( 'wpadm-ga-js', plugins_url(WPADM_GA__PLUGIN_NAME. '/view/scripts/wpadm-ga.js' ) );
        wp_enqueue_script( 'wpadm-ga-js' );

        wp_register_script( 'wpadm-moment-js', plugins_url(WPADM_GA__PLUGIN_NAME. '/view/scripts/moment.min.js' ) );
        wp_enqueue_script( 'wpadm-moment-js' );

        wp_register_script( 'wpadm-daterangepicker-js', plugins_url(WPADM_GA__PLUGIN_NAME. '/view/scripts/daterangepicker/daterangepicker.js' ) );
        wp_enqueue_script( 'wpadm-daterangepicker-js' );
    }

    public static function generateMenu() {
        $pages = array();

        $menu_position = '1.9998887770';
        $pages[] = add_menu_page(
            'Analytics Counter',
            'Analytics Counter',
            'read',
            WPADM_GA__MENU_PREFIX . 'visit',
            array('Wpadm_GA', 'visitView'),
            plugins_url('/view/img/icon.png',__FILE__),
            $menu_position
        );
        $pages[] = add_submenu_page(
            WPADM_GA__MENU_PREFIX . 'visit',
            'Audience overview',
            'Audience overview',
            'read',
            WPADM_GA__MENU_PREFIX . 'visit',
            array('Wpadm_GA', 'visitView')
        );


        $pages[] = add_submenu_page(
            WPADM_GA__MENU_PREFIX . 'visit',
            'Visitors overview',
            'Visitors overview',
            'read',
            WPADM_GA__MENU_PREFIX . 'users',
            array('Wpadm_GA', 'usersView')
        );


        $pages[] = add_options_page(
            'Analytics Counter Settings',
            'Analytics Counter',
            'administrator',
            WPADM_GA__MENU_PREFIX . 'settings',
            array('Wpadm_GA', 'settingsView')
        );

        foreach($pages as $page) {
            add_action( 'admin_print_scripts-' . $page, array('Wpadm_GA', 'registerPluginScripts' ));
            add_action( 'admin_print_styles-' . $page, array('Wpadm_GA', 'registerPluginStyles' ) );
        }

    }
    
    public static function generateGACodeOnSite() {
        $token = WPAdm_GA_Options::getGAAccessToken();
        if (empty($token)) {
            $code = get_option('wpadm_ga_manual_tracking_code');
            if ($code) {
                echo '<!-- '.WPADM_GA__PLUGIN_NAME.' google analytics manual tracking code -->';
                echo stripslashes($code);
                echo '<!--  -->';
            }

        } elseif (WPAdm_GA_Options::getGAEnableCode() == 1 && WPAdm_GA_Options::getGAWebPropertyId()) {
            echo '<!-- '.WPADM_GA__PLUGIN_NAME.' google analytics tracking code -->';
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'ga_code_universal.php';
            echo '<!--  -->';
        }
    }


    protected static function getErrorTemplate() {

        if(!get_option('wpadm_ga_pub_key')) {
            return WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'error_admin_empty_pub_key.php';
        }
        
        $token = WPAdm_GA_Options::getGAAccessToken();
        if (empty($token)) {
            WPAdm_GA_View::$subtitle = 'Account Connection';
            return WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'error_admin_empty_ga_token.php';
        }

        $site = WPAdm_GA_Options::getGAUrl();
        if (empty($site) && $_GET['page'] != 'wpadm-ga-menu-settings') {
            return WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'error_admin_empty_ga_site.php';
        }
        
        if(isset($_GET['google_oauth2_error'])) {
            return WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'error_admin_google_error.php';
        }

        if(isset($_GET['error'])) {
            return WPAdm_GA_View::$content_file = WPADM_GA__PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR . 'error_admin_wpadm_error.php';
        }

        return null;
    }

    protected static function getParamsForRequest($data) {
        $params = array(
            'data' => $data,
            'sign' => self::getSSLSign($data)
        );
        
        return array(REQUEST_PARAM_NAME => base64_encode(serialize($params)));
    }
    
    protected static function getSSLSign($data) {
        $str = md5(serialize($data));
        if(function_exists('openssl_public_encrypt')) {
            openssl_public_encrypt($str, $sign, get_option('wpadm_ga_pub_key'));
        } else {
            set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/lib/phpseclib');
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib'.DIRECTORY_SEPARATOR . 'phpseclib' . DIRECTORY_SEPARATOR . 'Crypt'.DIRECTORY_SEPARATOR.'RSA.php';
            $rsa = new Crypt_RSA();
            $rsa->loadKey(get_option('wpadm_ga_pub_key'));
            $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
            $sign = $rsa->encrypt($str);
        }
        return $sign;
    }
    
    protected static function verifySignature($sign, $pub_key, $text) {
        if (function_exists('openssl_public_decrypt')) {
            openssl_public_decrypt($sign, $request_sign, $pub_key);
            $ret = ($text == $request_sign);
            return $ret;
        } else {
            set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/lib/phpseclib');
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib'.DIRECTORY_SEPARATOR . 'phpseclib' . DIRECTORY_SEPARATOR . 'Crypt'.DIRECTORY_SEPARATOR.'RSA.php';
            $rsa = new Crypt_RSA();
            $rsa->loadKey($pub_key);
            $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
            return ($rsa->decrypt($sign) == $text);
        }
    }

    protected static function getSignature($pub_key, $text) {
        if (function_exists('openssl_public_encrypt')) {
            $signature = '';
            openssl_public_encrypt($text, $signature, $pub_key);
            return $signature;
        } else 
        {
            set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/lib/phpseclib');
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib'.DIRECTORY_SEPARATOR . 'phpseclib' . DIRECTORY_SEPARATOR . 'Crypt'.DIRECTORY_SEPARATOR.'RSA.php';
            $rsa = new Crypt_RSA();
            $rsa->loadKey($pub_key);
            $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
            return $rsa->encrypt($text);
        }
    }

    protected static function decodeData($str) {
        return json_decode(base64_decode($str), true);
    }


    protected static function checkDB() {
        $opt_ver = WPADM_GA__PLUGIN_NAME . '-db-version'; 
        $cur_version = get_option($opt_ver, 0);

        if ($cur_version < WPADM_GA__DB_VERSION) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpadm_ga_cache";
            $sql = "CREATE TABLE " . $table_name . " (
              id int(11) NOT NULL AUTO_INCREMENT,
              query text NOT NULL,
              html text,
              result text,
              request_type varchar(20),
              object_type varchar(20),
              clearable tinyint(4) DEFAULT '1',
              expired_in int(11) DEFAULT 0,
              PRIMARY KEY  (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            $sql = $sql = "CREATE TABLE " . $table_name . " (
              id int(11) NOT NULL AUTO_INCREMENT,
              query text NOT NULL,
              html text,
              result text,
              request_type varchar(20),
              object_type varchar(20),
              clearable tinyint(4) DEFAULT '1',
              expired_in int(11) DEFAULT 0,
              PRIMARY KEY  (id),
              KEY  expired_in (expired_in),
              FULLTEXT KEY query (query)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

            dbDelta($sql);

            self::cron_activation();

            update_option($opt_ver, WPADM_GA__DB_VERSION);
        }
    }

    public static function plugin_uninstall() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpadm_ga_cache";
        $sql = "DROP TABLE " . $table_name;
        $wpdb->query($sql);

        $opt_ver = WPADM_GA__PLUGIN_NAME . '-db-version';
        delete_option($opt_ver);
    }

    protected static function getIp()
    {
        $user_ip = '';
        if ( getenv('REMOTE_ADDR') ){
            $user_ip = getenv('REMOTE_ADDR');
        }elseif ( getenv('HTTP_FORWARDED_FOR') ){
            $user_ip = getenv('HTTP_FORWARDED_FOR');
        }elseif ( getenv('HTTP_X_FORWARDED_FOR') ){
            $user_ip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif ( getenv('HTTP_X_COMING_FROM') ){
            $user_ip = getenv('HTTP_X_COMING_FROM');
        }elseif ( getenv('HTTP_VIA') ){
            $user_ip = getenv('HTTP_VIA');
        }elseif ( getenv('HTTP_XROXY_CONNECTION') ){
            $user_ip = getenv('HTTP_XROXY_CONNECTION');
        }elseif ( getenv('HTTP_CLIENT_IP') ){
            $user_ip = getenv('HTTP_CLIENT_IP');
        }

        $user_ip = trim($user_ip);
        if ( empty($user_ip) ){
            return '';
        }
        if ( !preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $user_ip) ){
            return '';
        }
        return $user_ip;
    }


    public static function sendSupport() {
        if (isset($_POST['message'])) {
            $ticket = date('ymdHis') . rand(1000, 9999);
            $subject = "Support [sug:$ticket]: Analytics counter plugin";
            $message = "Client email: " . get_option('admin_email') . "\n";
            $message .= "Client site: " . home_url() . "\n";
            $message .= "Client suggestion: " . $_POST['message']. "\n\n";
            $message .= "Client ip: " . self::getIp() . "\n";
            $browser = @$_SERVER['HTTP_USER_AGENT'];
            $message .= "Client useragent: " . $browser . "\n";
            $header[] = "Reply-To: " . get_option('admin_email') . "\r\n";
            if (wp_mail(self::EMAIL_SUPPORT, $subject, $message, $header)) {
                echo json_encode(array(
                    'status' => 'success'
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error'
                ));
            }
            wp_die();
        }
    }

    public static function stopNotice5Stars() {
        if (isset($_POST['stop'])) {
            update_option('wpadm-ga-stopNotice5Stars', true);

        }
        wp_die();
    }

    protected static function getCurUrl() {
        return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . rtrim($_SERVER['HTTP_HOST'], '/')."/" . ltrim($_SERVER['REQUEST_URI'], '/');
    }
}

