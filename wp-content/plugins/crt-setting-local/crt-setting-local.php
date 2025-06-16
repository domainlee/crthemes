<?php
/**
 * Plugin Name: CRT Setting Local
 * Description: CRT Setting Local is a front page customizer plugin for Wordpress themes by author domainlee
 * Version: 1.0.0
 * Author: Domainlee
 * Author URI: https://crthemes.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
! defined( 'CRT_SETTING_LOCAL_PLUGIN_FILE' ) && define( 'CRT_SETTING_LOCAL_PLUGIN_FILE', __FILE__ );
! defined( 'CRT_SETTING_LOCAL_URI' ) && define( 'CRT_SETTING_LOCAL_URI', plugin_dir_url( __FILE__ ) );
! defined( 'CRT_SETTING_LOCAL_DIR' ) && define( 'CRT_SETTING_LOCAL_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'CRT_SETTING_LOCAL_URL_DEMO' ) && define( 'CRT_SETTING_LOCAL_URL_DEMO', wp_get_theme()->get( 'ThemeURI' ) );
! defined( 'CRT_SETTING_LOCAL_THEME_NAME' ) && define( 'CRT_SETTING_LOCAL_THEME_NAME', wp_get_theme()->get( 'Name' ) );


//require_once 'class-crt-setting-local-base.php';
//add_action('plugins_loaded', array('CRT_Setting_Local', 'instance'));

function crt_setting_button_buy_now() {
    ?>
    <style>
        .crt-setting-btn-buy-now {
            position: fixed;
            width: 55px;
            text-align: center;
            height: 85px;
            font-size: 15px;
            top: 50%;
            right: 10px;
            font-family: 'arial';
            border: 1px solid;
            background-color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            flex-direction: column;
        }
        .crt-setting-btn-buy-now:hover {
            text-decoration: none;
        }
        .crt-setting-btn-buy-now svg {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin: 5px 0;
        }
    </style>
    <a class="crt-setting-btn-buy-now" href="<?php echo CRT_SETTING_LOCAL_URL_DEMO ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>Buy Now</a>
    <?php
}
add_action( 'wp_footer', 'crt_setting_button_buy_now' );

