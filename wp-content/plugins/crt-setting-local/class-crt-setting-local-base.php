<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Setting_Local
 */
class CRT_Setting_Local {

    public static $_instance = '';

    public function __construct() {

    }

    public static function instance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


}