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
        $theme_client = 'your-portfolio';

        exec('cp -a '.CRTHEMES_URL_PROJECT_ITEM.'/'.$theme_name.'/ '.CRTHEMES_URL_PROJECTS.'/'.$theme_client, $result);
        exec('/Applications/MAMP/Library/bin/mysql -uroot -proot -e "CREATE DATABASE user_user1234"', $db_user);
        exec('/Applications/MAMP/Library/bin/mysql -uroot -proot -e "CREATE USER user_user1234@localhost IDENTIFIED by 123456"', $db_name);

//        exec('chown -R www-data:www-data /var/www/your_domain', $result);
//        exec('chmod -R g+w /var/www/your_domain/wp-content/themes', $result);
//        exec('chmod -R g+w /var/www/your_domain/wp-content/plugins', $result);
        print_r($result);
        print_r($db_user);
        print_r($db_name);

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

}
new CRT_Register();