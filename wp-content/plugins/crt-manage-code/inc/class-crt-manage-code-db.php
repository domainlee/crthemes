<?php
class CRT_DB_CODE
{
    protected $default = array(
        'id' => 0,
        'order_id' => '',
        'code' => '',
        'name_service' => '',
        'date' => '',
        'active_date' => '',
        'status' => self::STATUS_INACTIVE,
    );

    public $table_name = '';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'crtheme_manage_codes';
    }

    public function create($data_request) {
        global $wpdb;
        $item = shortcode_atts($this->default, $data_request);
        $result = $wpdb->insert($this->table_name, $item);
        return $result;
    }

    public function update($data_request) {
        global $wpdb;
        $item = shortcode_atts($this->default, $data_request);
        $result = $wpdb->update($this->table_name, $item, array('id' => $item['id']));
        return $result;
    }

    public function delete($item) {

    }

    public function get($code) {
        global $wpdb;
        $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $this->table_name . "  WHERE active_code LIKE '".$code."'"), ARRAY_A);
        return $post;
    }

    public function get_name($name) {
        global $wpdb;
        $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $this->table_name . "  WHERE name LIKE '".$name."'"), ARRAY_A);
        return $post;
    }

    public function get_code_order_id($order_id) {
        global $wpdb;
        $post = $wpdb->get_row($wpdb->prepare("SELECT code FROM " . $this->table_name . "  WHERE order_id LIKE '%d'", $order_id), ARRAY_A);
        return $post;
    }

    public function get_code($code) {
        global $wpdb;
        $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $this->table_name . "  WHERE code LIKE '%s'", $code), ARRAY_A);
        return $post;
    }

}

new CRT_DB_CODE();