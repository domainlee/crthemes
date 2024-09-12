<?php
class CRT_Register
{
    protected $data;
    public function __construct() {
        add_filter( 'wpcf7_validate_text*', array( $this, 'custom_domain_confirmation_validation_filter' ), 20, 2 );
        add_filter( 'wpcf7_validate_email*', array( $this, 'custom_email_confirmation_validation_filter' ), 20, 2 );
        add_filter( 'wpcf7_mail_components', array( $this, 'my_wpcf7_mail_components' ), 10, 3 );
        add_action('wpcf7_mail_sent', array( $this, 'action_after_submit' ) );

        if ( ! defined( 'CRTHEMES_URL_PROJECTS' ) ) {
            define( 'CRTHEMES_URL_PROJECTS', '/Applications/MAMP/htdocs/users' );
        }
        if ( ! defined( 'CRTHEMES_URL_PROJECT_DEFAULT' ) ) {
            define( 'CRTHEMES_URL_PROJECT_DEFAULT', '/Applications/MAMP/htdocs/users/default' );
        }

        if ( ! defined( 'CRTHEMES_URL_PROJECT_ITEM' ) ) {
            define( 'CRTHEMES_URL_PROJECT_ITEM', '/Applications/MAMP/htdocs/users/' );
        }

        add_action('rest_api_init', function () {
            register_rest_route('register', '/active/(?P<code>[a-zA-Z0-9-]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'active_site'),
                'permission_callback' => '__return_true',
            ));
        });
    }

    public function active_site($request) {
        $code = $request['code'];
        global $table_crtheme_manage_sites;
        $theme_name = 'melissa-portfolio';
        $theme_client = 'your-domain';

        $wp_hasher = $this->randomPassword();
        $password = wp_hash_password($wp_hasher);
        $site_theme = 'http://localhost/users/'.$theme_name;
        $site_client = 'http://localhost/users/'.$theme_client;
        $info_domain = parse_url($site_client);

        $db_name = "user_" . $this->crt_get_string($theme_client);
        $db_password = md5($theme_client);

        $create_db_name = "CREATE DATABASE $db_name;";
        $create_db_user = "CREATE USER '$db_name'@'localhost' IDENTIFIED by '$db_password';";
        $db_grant = "GRANT ALL PRIVILEGES ON $db_name.* TO '$db_name'@'localhost' WITH GRANT OPTION;";
        $db_flush = "FLUSH PRIVILEGES;";
        $db_exit = "exit;";

        $db_update_option = "UPDATE wp_options SET option_value = REPLACE(option_value, '$site_theme', '$site_client') WHERE option_name = 'home' OR option_name = 'siteurl';";
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
        exec('cp -a '.CRTHEMES_URL_PROJECT_ITEM.'/'.$theme_name.'/ '.CRTHEMES_URL_PROJECTS.'/'.$theme_client, $output, $retval);

        $curFile = glob(CRTHEMES_URL_PROJECTS.'/'.$theme_client."/*.sql");
        if(!empty($curFile)) {
            $db_import = "/Applications/MAMP/Library/bin/mysql -uroot -proot $db_name <".$curFile[0];
        }

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

        // Create database
        exec("/Applications/MAMP/Library/bin/mysql -uroot -proot -e \"$create_db_name\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -uroot -proot -e \"$create_db_user\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -uroot -proot -e \"$db_grant\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -uroot -proot -e \"$db_flush\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -uroot -proot -e \"$db_exit\" ", $output, $retval);

        // Import demo database
        exec($db_import, $output, $retval);

        // Update for site
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_option\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_post_content\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_post_excerpt\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_post_value\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_term_meta\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_comment_content\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_comment_author\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_guid\" ", $output, $retval);
        exec("/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_password\" ", $output, $retval);
//        echo "/Applications/MAMP/Library/bin/mysql -u$db_name -p$db_password $db_name -e \"$db_update_password\" ";die;
//        exec('chown -R www-data:www-data /var/www/your_domain', $result);
//        exec('chmod -R g+w /var/www/your_domain/wp-content/themes', $result);
//        exec('chmod -R g+w /var/www/your_domain/wp-content/plugins', $result);
        echo 'Your site: ' . $site_client;
        echo '<br/>';
        echo 'Your site wp-admin: '. $site_client .'/wp-admin';
        echo '<br/>';
        echo 'username: admin';
        echo '<br/>';
        echo 'password: '.$wp_hasher;
    }

    public function action_after_submit ($cf7) {
        if($cf7->id == 576) {
            global $table_crtheme_manage_sites;

            $email_client = $_POST['your-email'];
            $name_client = $_POST['your-domain'];
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
                'theme_id' => 'melissa-portfolio',
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
//        $result->invalidate( $tag, 'Domain already exists' );
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