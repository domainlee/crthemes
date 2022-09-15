<?php
/**
 * Created by PhpStorm.
 * User: mienle
 * Date: 5/11/18
 * Time: 2:16 PM
 */

function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'wpb_widget',
            __('WPBeginner Widget', 'wpb_widget_domain'),
            array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        echo '<div class="widget_colume">';
        echo __( 'Hello, World!', 'wpb_widget_domain' );
            echo $args['after_widget'];
        echo '</div>';
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}


?>
