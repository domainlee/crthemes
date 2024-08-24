<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Booknow_List_Table extends WP_List_Table{
    private $table_type = "booknow";
    public function set_table( $table= "" ) {
        $this->table_type = $table;
    }
    public function prepare_items($upcoming =false){
        $table_type = $this->table_type;
        $hidden = array();
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = apply_filters("table_booknow_".$table_type."_total_items",array());
        $currentPage = $this->get_pagenum();
        $per_page = 10;
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        $sql_array = array(
            "paged"=> $paged,
            "per_page"=> $per_page,
            "orderby"=> $orderby,
            "order"=> $order,
        );
        $sql_array = apply_filters("booknow_".$table_type."_main_query",$sql_array);
        $data = apply_filters("table_booknow_".$table_type."_data_items",array(),$sql_array);
        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($total_items / $per_page),
            )
        );
        $this->items      = $data;
    }
    public function no_items() {
        esc_html_e( 'No data.', 'booknow' );
    }
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
    function column_name($item)
    {
        $page="";
        if(isset($_GET["page"])){
            $page = sanitize_text_field($_GET["page"]);
        }
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=add_new&id=%s">%s</a>',$page, $item['id'], __('Edit', 'booknow')),
            'delete' => sprintf('<a class="check-delete" href="?page=%s&action=delete&id=%s">%s</a>', $page, $item['id'], __('Delete', 'booknow')),
        );
        if(isset($item['name'])){
            return sprintf('%s %s',
                $item['name'],
                $this->row_actions($actions)
            );
        }else{
            return sprintf('%s %s %s',
                $item['last_name'],$item['first_name'],
                $this->row_actions($actions)
            );
        }  
    }
    function column_order_id($item)
    {
        $page="";
        if(isset($_GET["page"])){
            $page = sanitize_text_field($_GET["page"]);
        }
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=add_new&id=%s">%s</a>',$page, $item['id'], __('Edit', 'booknow')),
            'delete' => sprintf('<a class="check-delete" href="?page=%s&action=delete&id=%s">%s</a>', $page, $item['id'], __('Delete', 'booknow')),
        );
        if(isset($item['id'])){
            $view = sprintf('<strong><a href="?page=%s&action=add_new&id=%s">%s</a></strong>',$page, $item['id'], '#'.$item['id'].' '. booknow_format_name_text($item['customer_firstname'],$item['customer_lastname']));
            return sprintf('%s %s',
                $view,
                $this->row_actions($actions)
            );
        } 
    }
    function column_appointments($item)
    {
        $page="";
        if(isset($_GET["page"])){
            $page = sanitize_text_field($_GET["page"]);
        }
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=add_new&type=edit&id=%s">%s</a>',$page, $item['id'], __('Edit', 'booknow')),
            'delete' => sprintf('<a class="check-delete" href="?page=%s&action=delete&id=%s">%s</a>', $page, $item['id'], __('Delete', 'booknow')),
        );
        if(isset($item['date'])){
            $view = sprintf('<strong><a href="?page=%s&action=edit&type=view&id=%s">%s</a></strong>',$page, $item['id'], $item['date']);
            return sprintf('%s %s',
                $view,
                $this->row_actions($actions)
            );
        }
    }
    public function get_columns()
    {
        $table_type = $this->table_type;
        $columns = apply_filters("manage_booknow_".$table_type."_posts_columns",array('cb' => '<input type="checkbox" />'));
        return $columns;
    }
    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }
    function process_bulk_action(){ 
        $table_type = $this->table_type;
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            if (!empty($ids)) {
                add_action("table_booknow_".$table_type."_remove_items",$ids);
            }
        }
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    public function get_sortable_columns()
    {
        return array();
    }
    public function column_default( $item, $column_name ){
        $table_type = $this->table_type;
        $columns = apply_filters("manage_booknow_".$table_type."_posts_custom_column",$column_name, $item["id"],$item);
        return $columns;
    }
}