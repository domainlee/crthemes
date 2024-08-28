<?php
class CRT_DB
{
    protected $default = array(
        'id' => 0,
        'name' => '',
        'email' => '',
        'age' => null,
    );

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

    public function delete() {

    }
}

new CRT_DB();