<?php
class CRT_Register
{
    protected $data;

    public function __construct() {
        add_filter( 'wpcf7_validate_text*', array( $this, 'custom_domain_confirmation_validation_filter' ), 20, 2 );
        add_filter( 'wpcf7_validate_email*', array( $this, 'custom_email_confirmation_validation_filter' ), 20, 2 );
        add_filter( 'wpcf7_mail_components', array( $this, 'my_wpcf7_mail_components' ), 10, 3 );
        add_action('wpcf7_mail_sent', array( $this, 'action_after_submit' ) );
        add_action('rest_api_init', function () {
            register_rest_route('register', '/active/(?P<code>[a-zA-Z0-9-]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'active_site'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('transfer', 'domain', array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'transfer'),
                'permission_callback' => '__return_true',
            ));
        });

    }

    public function active_site($request) {
        $code = $request['code'];
        global $table_crtheme_manage_sites;
        $client_info_site = $table_crtheme_manage_sites->get($code);
        if(empty($client_info_site)) {
            header('Content-Type: text/html');
            echo '<style>body {font-family: Arial, "Times New Roman", "Bitstream Charter", Times, serif; font-size: 14px;} p {margin: 0 0 5px;}</style>';
            echo '<p>Code: ' . $code . ' not exists</p>';
            return;
        }
        $theme_name = $client_info_site['theme_id'];
        $theme_client = $client_info_site['name'];
        $code = $client_info_site['active_code'];

        $wp_hasher = $this->randomPassword();
        $password = wp_hash_password($wp_hasher);
        $site_theme = CRTHEMES_PRODUCT_ENV == 'dev' ? 'http://'.$theme_name.'.domain' : 'https://'.$theme_name.'.crthemes.com';
        $site_client_host = CRTHEMES_PRODUCT_ENV == 'dev' ? $theme_client.'.domain' : $theme_client.'.crthemes.com';
        $site_client = CRTHEMES_PRODUCT_ENV == 'dev' ? 'http://'.$site_client_host : 'https://'.$site_client_host;
        $info_domain = parse_url($site_client);

        $db_name = "user_" . $this->crt_get_string($theme_client);
        $db_password = md5($theme_client);

        $create_db_name = "CREATE DATABASE $db_name;";
        $create_db_user = "CREATE USER '$db_name'@'%' IDENTIFIED by '$db_password';";
        $db_grant = "GRANT ALL PRIVILEGES ON $db_name.* to '$db_name'@'%'";
        $db_flush = "FLUSH PRIVILEGES;";
        $db_exit = "exit;";

        $db_update_option = "UPDATE wp_options SET option_value = REPLACE(option_value, '$site_theme', '$site_client') WHERE option_name = 'home' OR option_name = 'siteurl';";
        $db_update_option_code = "UPDATE wp_options SET option_value = '$code' WHERE wp_options.option_name = 'crt_manage_code';";
        $db_update_post_content = "UPDATE wp_posts SET post_content = REPLACE (post_content, '$site_theme', '$site_client');";
        $db_update_post_excerpt = "UPDATE wp_posts SET post_excerpt = REPLACE (post_excerpt, '$site_theme', '$site_client');";
        $db_update_post_value = "UPDATE wp_postmeta SET meta_value = REPLACE (meta_value, '$site_theme','$site_client');";
        $db_update_term_meta = "UPDATE wp_termmeta SET meta_value = REPLACE (meta_value, '$site_theme','$site_client');";
        $db_update_comment_content = "UPDATE wp_comments SET comment_content = REPLACE (comment_content, '$site_theme', '$site_client');";
        $db_update_comment_author = "UPDATE wp_comments SET comment_author_url = REPLACE (comment_author_url, '$site_theme','$site_client');";
        $db_update_guid = "UPDATE wp_posts SET guid = REPLACE (guid, '$site_theme', '$site_client') WHERE post_type = 'attachment';";
        $db_update_password = "UPDATE wp_users SET user_pass = MD5('$wp_hasher'), user_url = '$site_client' WHERE wp_users.user_login = 'admin';";
        $output = null;
        $retval = null;
        // Copy Source
        exec('cp -a '.CRTHEMES_URL_PROJECTS.'/'.$theme_name.'/ '.CRTHEMES_URL_PROJECTS.'/'.$theme_client, $output, $retval);

        // Virtual Host
        exec('touch '.CRTHEMES_VIRTUAL_HOST.'/httpd-'.$theme_client.'.conf', $output, $retval);

        $curFile = glob(CRTHEMES_URL_PROJECTS.'/'.$theme_client."/*.sql");

        // Updated file wp-config.php
        $wp_config = CRTHEMES_URL_PROJECTS.'/'.$theme_client ."/wp-config.php";
        $content = file($wp_config);
        $content[23] = "define( 'DB_NAME', '$db_name' );\r\n";
        unset($content[22]);
        $content[26] = "define( 'DB_USER', '$db_name' );\r\n";
        unset($content[25]);
        $content[29] = "define( 'DB_PASSWORD', '$db_password' );\r\n\r\n";
        unset($content[28]);

        $content[30] = "define( 'WP_HOME', '$site_client' );\r\n";
        $content[31] = "define( 'WP_SITEURL', '$site_client' );";

        $path = '/';
        $path2 = '/index.php';
        if(isset($info_domain['path'])) {
            $path = $info_domain['path'] . '/';
            $path2 = $info_domain['path'] . '/index.php';
        }

        $allContent = implode("", $content);
        file_put_contents($wp_config, $allContent);

        // Updated file .htaccess
        $htaccess = CRTHEMES_URL_PROJECTS.'/'.$theme_client ."/.htaccess";
        $htaccess_content = file($htaccess);
        $htaccess_content[8] = "RewriteBase $path\r\n";
        $htaccess_content[12] = "RewriteRule . $path2 [L]\r\n";
        $htaccess_allContent = implode("", $htaccess_content);
        file_put_contents($htaccess, $htaccess_allContent);

        // Create Virtual Host
        $document_root = CRTHEMES_URL_PROJECTS.'/'.$theme_client;
        $virtual_host = CRTHEMES_VIRTUAL_HOST.'/httpd-'.$theme_client.'.conf';
        $virtual_host_content = file($virtual_host);
        if(CRTHEMES_PRODUCT_ENV == 'dev') {
            $virtual_host_content[0] = "ServerName $site_client_host:80\r\n";
            $virtual_host_content[1] = "<VirtualHost $site_client_host:80>\r\n";
            $virtual_host_content[2] = "    DocumentRoot \"$document_root\" \r\n";
            $virtual_host_content[3] = "    ServerName $site_client_host\r\n";
            $virtual_host_content[4] = "    ServerAlias $site_client_host\r\n";
            $virtual_host_content[5] = "</VirtualHost>\r\n";
        } elseif (CRTHEMES_PRODUCT_ENV == 'production') {
            $virtual_host_content[0] = "ServerName $site_client_host:80\r\n";
            $virtual_host_content[1] = "<VirtualHost $site_client_host:80>\r\n";
            $virtual_host_content[2] = "DocumentRoot \"$document_root\" \r\n";
            $virtual_host_content[3] = "ServerName $site_client_host\r\n";
            $virtual_host_content[4] = "ServerAlias $site_client_host\r\n";
            $virtual_host_content[5] = "<Directory $document_root>\r\n";
            $virtual_host_content[6] = "Options Indexes FollowSymLinks\r\n";
            $virtual_host_content[7] = "AllowOverride All\r\n";
            $virtual_host_content[8] = "Require all granted\r\n";
            $virtual_host_content[9] = "</Directory>\r\n";
            $virtual_host_content[10] = "RewriteEngine on\r\n";
            $virtual_host_content[11] = "RewriteCond %{SERVER_NAME} =$site_client_host\r\n";
            $virtual_host_content[12] = "RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]\r\n";
            $virtual_host_content[13] = "</VirtualHost>\r\n";
            $virtual_host_content[14] = "<IfModule mod_ssl.c>\r\n";
            $virtual_host_content[15] = "<VirtualHost $site_client_host:443>\r\n";
            $virtual_host_content[16] = "DocumentRoot \"$document_root\" \r\n";
            $virtual_host_content[17] = "ServerName $site_client_host\r\n";
            $virtual_host_content[18] = "ServerAlias $site_client_host\r\n";
            $virtual_host_content[19] = "SSLCertificateFile /etc/letsencrypt/live/crthemes.com/fullchain.pem\r\n";
            $virtual_host_content[20] = "SSLCertificateKeyFile /etc/letsencrypt/live/crthemes.com/privkey.pem\r\n";
            $virtual_host_content[21] = "Include /etc/letsencrypt/options-ssl-apache.conf\r\n";
            $virtual_host_content[22] = "<Directory $document_root>\r\n";
            $virtual_host_content[23] = "Options Indexes FollowSymLinks\r\n";
            $virtual_host_content[24] = "AllowOverride All\r\n";
            $virtual_host_content[25] = "Require all granted\r\n";
            $virtual_host_content[26] = "</Directory>\r\n";
            $virtual_host_content[27] = "</VirtualHost>\r\n";
            $virtual_host_content[28] = "</IfModule>\r\n";
        }
        $virtual_host_allContent = implode("", $virtual_host_content);
        file_put_contents($virtual_host, $virtual_host_allContent);

        // Create database
        exec(CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." -e \"$create_db_name\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." -e \"$create_db_user\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." -e \"$db_grant\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." -e \"$db_flush\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." -e \"$db_exit\" ", $output, $retval);

        // Import demo database
        if(!empty($curFile)) {
            $db_import = CRTHEMES_EXEC_MYSQL . " ".CRTHEMES_EXEC_MYSQL_ROOT." $db_name <".$curFile[0];
            exec($db_import, $output, $retval);
        }

        // Update for site
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_option\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_option_code\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_content\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_excerpt\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_value\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_term_meta\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_comment_content\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_comment_author\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_guid\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_password\" ", $output, $retval);
        if(CRTHEMES_PRODUCT_ENV == 'production') {
            exec("sudo a2ensite httpd-".$theme_client.".conf", $output, $retval);
            exec("systemctl reload apache2", $output, $retval);
            exec("chown -R www-data:www-data ". $document_root, $output, $retval);
            exec('chmod -R g+w '.$document_root.'/wp-content/themes', $output, $retval);
            exec('chmod -R g+w '.$document_root.'/wp-content/plugins', $output, $retval);
        }

        header('Content-Type: text/html');
        $client_info_site['db_user'] = $db_name;
        $client_info_site['db_name'] = $db_name;
        $client_info_site['db_password'] = $db_password;
        $table_crtheme_manage_sites->update($client_info_site);
        echo '<style>body {font-family: Arial, "Times New Roman", "Bitstream Charter", Times, serif; font-size: 14px;} p {margin: 0 0 5px;}</style>';
        echo '<p>Your site: ' . $site_client . '</p>';
        echo '<p>Your site wp-admin: '. $site_client .'/wp-admin'. '</p>';
        echo '<p>Username: admin'. '</p>';
        echo '<p>Password: '.$wp_hasher. '</p>';
        return;
    }

    public function transfer($request) {
        $data = $request->get_body();
        $data_request = json_decode($data, true);
        $code = $data_request['code'];
        $domain = $data_request['domain'];

        global $table_crtheme_manage_sites;
        $client_info_site = $table_crtheme_manage_sites->get($code);
        $domain_check = $this->is_valid_domain_name($domain);
        if(empty($client_info_site) || !$domain_check) {
            echo 'failure';
            exit();
        }

        $site_theme = 'http://'.$client_info_site['name'].'.domain';
        $site_client_host = $domain;
        $site_client = 'http://'.$site_client_host;
        $theme_client = $client_info_site['name'];

        $db_name = $client_info_site['db_user'];
        $db_password = $client_info_site['db_password'];


        $db_update_option = "UPDATE wp_options SET option_value = REPLACE(option_value, '$site_theme', '$site_client') WHERE option_name = 'home' OR option_name = 'siteurl';";
        $db_update_post_content = "UPDATE wp_posts SET post_content = REPLACE (post_content, '$site_theme', '$site_client');";
        $db_update_post_excerpt = "UPDATE wp_posts SET post_excerpt = REPLACE (post_excerpt, '$site_theme', '$site_client');";
        $db_update_post_value = "UPDATE wp_postmeta SET meta_value = REPLACE (meta_value, '$site_theme','$site_client');";
        $db_update_term_meta = "UPDATE wp_termmeta SET meta_value = REPLACE (meta_value, '$site_theme','$site_client');";
        $db_update_comment_content = "UPDATE wp_comments SET comment_content = REPLACE (comment_content, '$site_theme', '$site_client');";
        $db_update_comment_author = "UPDATE wp_comments SET comment_author_url = REPLACE (comment_author_url, '$site_theme','$site_client');";
        $db_update_guid = "UPDATE wp_posts SET guid = REPLACE (guid, '$site_theme', '$site_client') WHERE post_type = 'attachment';";

        $output = null;
        $retval = null;

        // Update for site
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_option\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_content\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_excerpt\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_post_value\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_term_meta\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_comment_content\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_comment_author\" ", $output, $retval);
        exec(CRTHEMES_EXEC_MYSQL . " -u$db_name -p$db_password $db_name -e \"$db_update_guid\" ", $output, $retval);

        // Update Virtual Host
        $document_root = CRTHEMES_URL_PROJECTS.'/'.$theme_client;
        $virtual_host = CRTHEMES_VIRTUAL_HOST.'/httpd-'.$theme_client.'.conf';
        $virtual_host_content = file($virtual_host);
        $virtual_host_content[0] = "ServerName $site_client_host:80\r\n";
        $virtual_host_content[1] = "<VirtualHost $site_client_host:80>\r\n";
        $virtual_host_content[2] = "    DocumentRoot \"$document_root\" \r\n";
        $virtual_host_content[3] = "    ServerName $site_client_host\r\n";
        $virtual_host_content[4] = "    ServerAlias $site_client_host\r\n";
        $virtual_host_content[5] = "</VirtualHost>\r\n";
        $virtual_host_allContent = implode("", $virtual_host_content);
        file_put_contents($virtual_host, $virtual_host_allContent);

        $client_info_site['domain_transfer'] = $site_client_host;
        $table_crtheme_manage_sites->update($client_info_site);

        echo 'done';
        exit();
    }

    public function action_after_submit ($cf7) {
        if($cf7->id == 576) {
            global $table_crtheme_manage_sites;

            $email_client = $_POST['your-email'];
            $name_client = $_POST['your-domain'];
            $theme = $_POST['theme'];
            $subject = 'Register site';
            $code = md5($email_client . $name_client);
            $link_active = home_url( '/' ).'wp-json/register/active/'.$code;
            $message = 'Hi there! link active site '. $link_active;
            $data = array(
                'id' => 0,
                'name' => $name_client,
                'email' => $email_client,
                'active_code' => $code,
                'active_code_link' => $link_active,
                'date' => date("Y-m-d"),
                'theme_id' => strtolower($theme),
                'status' => $table_crtheme_manage_sites::STATUS_DRAFT,
            );
            $result = $table_crtheme_manage_sites->create($data);
            if($result) {
                wp_mail($email_client, $subject, $message);
            }
        }
    }

    public function custom_domain_confirmation_validation_filter($result, $tag) {
        $check_is_domain = $this->is_valid_domain_name($_POST['your-domain']);
        if(!$check_is_domain) {
            $result->invalidate( $tag, 'Domain is not in correct format' );
            return $result;
        }

        $check_is_domain_exist = $this->is_valid_domain_exist($_POST['your-domain']);
        if(!$check_is_domain_exist) {
            $result->invalidate( $tag, 'Domain name already exists' );
            return $result;
        }

        return $result;
    }

    public function custom_email_confirmation_validation_filter($result, $tag) {
//        $result->invalidate( $tag, 'Email exist' );
        return $result;
    }

    public function my_wpcf7_mail_components($components, $form, $mail_object) {
        $submission = WPCF7_Submission::get_instance();

        // Get the contact form fields.
        $contact_form_fields = $submission->get_posted_data();

        // Create a custom message.
        $custom_message = 'Link active product';
        $custom_message .= ' ' . $contact_form_fields['your-name'] . ' has sent you a message.';

        // Append the custom message to the email body.
        $components['body'] = str_replace("[your-message]", $custom_message, $components['body']);
        return $components;
    }

    public function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }

    public function is_valid_domain_exist($name) {
        global $table_crtheme_manage_sites;
        $result = $table_crtheme_manage_sites->get_name($name);
        if(!empty($result)) {
            return false;
        }
        return true;
    }

    public function crt_get_string($s) {
        return preg_replace("/[^a-zA-Z0-9]+/", "", $s);
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 16; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}
new CRT_Register();