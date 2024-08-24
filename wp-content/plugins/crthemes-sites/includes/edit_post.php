<?php
class Booknow_Edit_Page{
	public $datas;
    function __construct($atts = array()){
    	$datas = shortcode_atts( array(
                'title'=>"",
                'menu' => '',
                'permissions' => '',
                'slug' => '',
                'backto' => 'Back',
                'button_save' => 'Save',
                'order'=>1
            ), $atts );
    	$this->datas = $datas;
    	$this->setup();
    }  
    function setup(){
        add_action( 'admin_menu', array($this,"add_menu"),12 );
        add_action( 'booknow_appointment_add_new', array( $this, 'page_edit' ) );
    }
    function add_menu() {
    	$datas = $this->datas;
        if(isset($_GET["action"]) && ( $_GET["action"] == "add_new" || $_GET["action"] == "edit" )){
            $page = "page_edit";
        }else{
            $page = "page_show";
        }
        add_submenu_page('booknow',$datas["menu"] , $datas["title"], $datas["permissions"],$datas["slug"], array($this,$page),$datas["order"] );
    }
    function page_show(){
       $datas = $this->datas;
       do_action("booknow_show_page_".$datas["slug"]);
    }
    function page_edit(){
    	$datas = $this->datas;
        do_action("booknow_before_load_".$datas["slug"]);
    	$id ="";
	    if( isset($_REQUEST['id']) ){
	    	$id = sanitize_text_field($_REQUEST['id']);
	    }
	    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], $datas["slug"]  )) {
	        do_action("booknow_submit_".$datas["slug"],$id);
	    }
	    ?>
	    <div class="wrap booknow-editor">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php echo esc_html($datas["title"]) ?> <a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page='.$datas["slug"] ); ?>">
            <?php echo esc_html($datas["backto"]) ?></a>
        </h2>
        <?php 
        if(isset($_GET["message"])){
            $message = sanitize_text_field($_GET["message"]);
            switch($message){ 
                case "added":
                    ?>
                    <div id="message" class="notice notice-success"><p><?php echo $datas["title"]." created"?></p></div>
                    <?php
                    break;
                case "updated";
                ?>
                    <div id="message" class="notice notice-success"><p><?php echo $datas["title"]." updated"?></p></div>
                    <?php
                    break;
            }
        }else{
            $booknow_admin_notice = get_option("booknow_admin_notice");
            if($booknow_admin_notice != null && count($booknow_admin_notice)>0){
                $type_notice = array_key_first($booknow_admin_notice);
                $values_notice = $booknow_admin_notice[$type_notice];
                switch($type_notice){
                    case "warning":
                        ?>
                        <div id="message" class="notice notice-warning"><p>
                            <?php
                                if($values_notice != null && is_array($values_notice) && count($values_notice) > 0){
                                    echo esc_html(implode(", ",$values_notice));
                                }
                            ?></p>
                        </div>
                        <?php
                        break;
                }
                delete_option("booknow_admin_notice");
            }
        }
        ?>
        <form id="form" method="POST" action="">
            <input type="hidden" id="booknow_nonce" name="nonce" value="<?php echo wp_create_nonce($datas["slug"] )?>"/>
            <input type="hidden" id="booknow_id" name="id" value="<?php echo esc_attr($id) ?>"/>
            <div class="metabox-holder" id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="submitdiv" class="postbox">
                            <h3><?php esc_html_e("Action","booknow") ?></h3>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">
                                    <div id="major-publishing-actions">
                                        <div id="delete-action">
                                           <input type="submit" class="button-primary" name="booknow-save" value="<?php echo esc_attr($datas["button_save"]) ?>"> 
                                        </div><!-- #delete-action -->

                                        <div id="publishing-action"> 
                                        </div>
                                            <div class="clear"></div>
                                    </div><!-- #major-publishing-actions -->
                                </div><!-- #submitpost -->
                            </div>
                        </div>
                        <?php
                        do_action("booknow_".$datas["slug"]."_add_new_side",$id);
                        ?>
                        
                    </div>
                    <div id="postbox-container-2" class="postbox-container">
                        <?php
                        do_action("booknow_".$datas["slug"]."_add_new",$id);
                        ?>
                    </div>
                    
                </div>
            </div>
        </form>
        
    </div>
        <?php
    }
}