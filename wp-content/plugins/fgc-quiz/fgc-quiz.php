<?php
/**
 * Plugin Name: FGC Quiz
 * Plugin URI: http://localhost
 * Description: a plugin for FGC Quiz
 * Version: 1.0
 * Author: khoazero123
 * Author URI: http://localhost
 * License: GPLv2
 */
 define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ));

function register_mysettings() {
    register_setting( 'fgc-settings-group', 'fgc_option_name' );
}
 
function fgc_create_menu() {
    $menuSlug = __FILE__;
    add_menu_page('FGC Quiz Manager', 'FGC Quiz Manager', 'administrator', $menuSlug, 'fgc_print_manager_class',null,2);//fgc_settings_page
    add_submenu_page($menuSlug, "Class manager", "Class manager", 'manage_options', $menuSlug . '-list-class','fgc_print_manager_class');
    add_submenu_page($menuSlug, "Timetable", "Timetable", 'manage_options', $menuSlug . '-timetable','fgc_print_manager_timetable');

    add_action( 'admin_init', 'register_mysettings' );
}
add_action('admin_menu', 'fgc_create_menu'); 
 
function fgc_print_manager_timetable() {
    include(PLUGIN_DIR.'timetable.php');
    $timetable = new Quiz_timetable;
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    switch ($action) {
        case 'add':
            $timetable->add_timetable();
            break;
        case 'edit':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $timetable->edit_timetable($classname);
            break;
        case 'view':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $timetable->view_timetable($classname);
            break;
        case 'delete':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $timetable->delete_timetable($classname);
            break;
        
        case 'remove':
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $timetable->remove_from_timetable($user_id,$classname);
            break;
        
        default:
            $timetable->list_class();
            break;
    }
    //$timetable->list_class();

}
function fgc_print_manager_class() {
    include(PLUGIN_DIR.'class.php');
    $class = new Quiz_class;

    $action = isset($_GET['action']) ? $_GET['action'] : null;
    switch ($action) {
        case 'add':
            $class->add_class();
            break;
        case 'edit':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $class->edit_class($classname);
            break;
        case 'view':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $class->view_class($classname);
            break;
        case 'delete':
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $class->delete_class($classname);
            break;
        
        case 'remove':
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            $classname = isset($_GET['classname']) ? $_GET['classname'] : null;
            $class->remove_from_class($user_id,$classname);
            break;
        
        default:
            $class->list_class();
            break;
    }
    //$class->list_class();
}

// add meta box in page Add new post
function fgc_register_class_meta_box() {
    add_meta_box( 'class-name', 'Lớp', 'fgc_print_box_class_name');
}
add_action('add_meta_boxes','fgc_register_class_meta_box'); 

// print html meta box enter class name
function fgc_print_box_class_name($post) {
    $list_classname = get_option('quiz_options_course', []);
    $classpost = get_post_meta($post->ID,'_classname',true);

    wp_nonce_field( 'nonce_meta_box_classname', 'nonce_meta_box_classname');
    echo '<label for="class_name">This post belong to class: </label>';
    //echo '<input type="text" name="classname" value="'.$classname.'" />';
    if ( is_admin() ) {
        echo '<select name="classname">
            <option value="">-- Select class --</option>';
            //foreach ($list_classname as $classname => $member) {
            foreach ($list_classname as $class) {
                $classname = $class['name'];
                echo '<option value="'.$classname.'"'.($classpost == $classname ? ' selected' : '').'>'.$classname.'</option>';
            }
            echo '</select>';
    }
}

// add meta box helper in page Add new post
function fgc_register_helper_meta_box() {
    add_meta_box( 'fgc-quiz-helper', 'Hưỡng dẫn dùng shortcode', 'fgc_print_box_helper');
}
add_action('add_meta_boxes','fgc_register_helper_meta_box'); 

// print html meta box enter class name
function fgc_print_box_helper($post) {

    echo '<p><code>[timetable classname="B"]</code> to print timetable of class B, leave empty classname to auto select by user login.</p>';
    echo '<p><code>[video url="http://..." width="560px" height="315px"]</code> to insert video player. Support youtube.com and voatiengviet.com</p>';

}

function fgc_save_class_name($post_id) {
    if(!isset($_POST['nonce_meta_box_classname'])) return;
    if(!wp_verify_nonce($_POST['nonce_meta_box_classname'],'nonce_meta_box_classname')) return;
    
    if(!empty($_POST['classname'])) {
        $classname = sanitize_text_field($_POST['classname']);
        update_post_meta( $post_id, '_classname', $classname);
    }
}
add_action('save_post','fgc_save_class_name');


add_action( 'show_user_profile', 'fgc_show_profile_class_field' );
add_action( 'edit_user_profile', 'fgc_show_profile_class_field' );

function fgc_show_profile_class_field( $user ) {
	$list_classname = get_option('quiz_options_course', []);
 ?>
	<h3>Extra profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="twitter">Class name:</label></th>
			<td>
			<?php if ( ! is_admin() ) { ?>
				<input type="text" value="<?php echo esc_attr( get_the_author_meta( '_classname', $user->ID ) ); ?>" class="regular-text" disabled /><br />
				<span class="description">Your class name.</span>
			<?php } else {
				echo '<select name="classname">
					<option value="">-- Select class --</option>';
					//foreach ($list_classname as $classname => $member) {
					foreach ($list_classname as $class) {
                        $classname = $class['name'];
                        $member = $class['members'];
						echo '<option value="'.$classname.'"'.(esc_attr(get_the_author_meta('_classname', $user->ID )) == $classname ? ' selected' : '').'>'.$classname.' ('.$member.' members)</option>';
					}
					echo '</select>';
			} ?>
			</td>
		</tr>
	</table>
<?php }

/*
Save field class name
 */

add_action( 'personal_options_update', 'fgc_save_profile_class_field' );
add_action( 'edit_user_profile_update', 'fgc_save_profile_class_field' );

function fgc_save_profile_class_field( $user_id ) {
    if ( ! is_admin() ) return false;
    $list_classname = get_option('quiz_options_course', []);
    $classname = sanitize_text_field($_POST['classname']);
	$list_classname[$classname]['members'] = $list_classname[$classname]['members'] + 1;
	update_option('quiz_options_course', $list_classname);
	update_usermeta( $user_id, '_classname', $_POST['classname'] );
}

// add shortcode to print timetable for page and post
add_shortcode( 'timetable', 'fgc_shortcode_timetable');

function fgc_shortcode_timetable($args,$content=null) {
    global $current_user;
    extract(shortcode_atts(array(
        'classname' => null,
    ), $args));

    include(PLUGIN_DIR.'timetable.php');
    $timetable = new Quiz_timetable;
    //$classname = isset($args['classname']) ? $args['classname'] : null;
    if($classname && !array_key_exists($classname,$timetable->timetable)) return 'Class '.$classname.' doesn\'t have timetable!';
    if(!$classname && is_user_logged_in()) {
        $user = wp_get_current_user();
        $classname = get_the_author_meta('_classname', $user->ID );
        if(!array_key_exists($classname,$timetable->timetable)) return 'No timetable for '.$user->user_nicename;
    } elseif(!is_user_logged_in()) {
        return 'Please login to view your timetable!';
    }
    // if is admin -> print all timetable of all class
    if (current_user_can('administrator')) {
        $list_class = get_option('quiz_options_course', []);
        if(!empty($list_class)) {
            $html = '';
            foreach ($list_class as $classname => $class) {
                //$html .= '<h2>Timetable of class '.$classname.'</h2>';
                $html .= $timetable->view_timetable($classname,true);
            }
            return $html;
        }
    } else {
        return $timetable->view_timetable($classname,true);
    }
}

// add shortcode to print timetable for page and post
add_shortcode( 'video', 'fgc_shortcode_video');

function fgc_shortcode_video($args,$content=null) {
    global $current_user;
    extract(shortcode_atts(array(
        'url' => null,
        'width' => '100%',//640,
        'height' => '360px',//360,
    ), $args));
    if(!preg_match('/(%|px)$/i',$width))
        $width .= 'px';
    
    if(!preg_match('/(%|px)$/i',$height))
        $height .= 'px';
    
    if(preg_match("/(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/", $url, $matches)) {
        $videoid = $matches[0];
        $url = 'https://www.youtube.com/embed/'.$videoid;
    } else if(preg_match("/https?:\/\/(?:www.)?voatiengviet.com\/a\/(\d+)/", $url, $matches)) {
        $videoid = $matches[1];
        //'<iframe src="https://www.voatiengviet.com/embed/player/0/3893960.html?type=video" frameborder="0" scrolling="no" width="640" height="363" allowfullscreen></iframe>'
        $url = 'https://www.voatiengviet.com/embed/player/0/'.$videoid.'.html?type=video';
    }
    $html = '<div style="width:'.$width.';height:'.$height.';"><iframe style=width:100%;height:100%;"" src="'.$url.'" frameborder="0" scrolling="no" allowfullscreen></iframe></div>';
    return $html;
}




// Not use
function fgc_settings_page() {
    ?>
    <div class="wrap">
    <h2>Demo Tạo trang cài đặt cho plugin</h2>
    <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>
    <?php } ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'fgc-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row">Tùy chọn cài đặt</th>
            <td><input type="text" name="fgc_option_name" value="<?php echo get_option('fgc_option_name'); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    </div>
    <?php
}
?>