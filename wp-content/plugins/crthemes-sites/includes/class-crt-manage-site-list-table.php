<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRThemes_Manage_Site_List_Table
 */
class CRThemes_Manage_Site_List_Table extends WP_List_Table
{
    function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => __('Name', 'crthemes-management-site'),
            'description'         => __('Description', 'crthemes-management-site'),
            'status'   => __('Status', 'crthemes-management-site'),
            'order'        => __('Order', 'crthemes-management-site')
        );
        return $columns;
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'name':
            case 'description':
            case 'status':
            case 'order':
            default:
                return $item[$column_name];
        }
    }

    function prepare_items()
    {
        //data
        $this->table_data = $this->get_table_data();

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'name';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        usort($this->table_data, array(&$this, 'usort_reorder'));

        /* pagination */
        $per_page = 3;
        $current_page = $this->get_pagenum();
        $total_items = count($this->table_data);

        $this->table_data = array_slice($this->table_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items, // total number of items
            'per_page'    => $per_page, // items to show on a page
            'total_pages' => ceil( $total_items / $per_page ) // use ceil to round up
        ));

        $this->items = $this->table_data;
    }

    private function get_table_data() {
        global $wpdb;
        $table = $wpdb->prefix . 'crthemes_management_sites';
        return $wpdb->get_results(
            "SELECT * from {$table}",
            ARRAY_A
        );
    }
}