<?php
class CRT_DB
{
    protected $default = array(
        'id' => 0,
        'name' => '',
        'email' => '',
        'active_code' => '',
        'active_code_link' => '',
        'date' => '',
        'theme_id' => '',
        'status' => self::STATUS_DRAFT,
    );

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'crtheme_manage_sites';
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

    public function exist($item) {
        global $wpdb;
        $post = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->table_name . "  WHERE sku LIKE '".$sku."'"));
    }

}

new CRT_DB();