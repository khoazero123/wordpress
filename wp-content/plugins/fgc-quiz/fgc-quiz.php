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
 define( 'PLUGIN_VERSION', '1.0');
 define( 'FORCE_INSTALL', true); // Lost old data

class FGC_Quiz {
    private $table_class;
    private $table_timetable;
    private $table_game;
    function __construct() {
        global $wpdb;
        $this->table_class = $wpdb->prefix . "fgc_class";
        $this->table_timetable = $wpdb->prefix . "fgc_timetable";
        $this->table_game = $wpdb->prefix . "fgc_game";
        add_action('admin_menu', array( $this, 'create_menu'));
        add_action('add_meta_boxes',array( $this, 'register_meta_box_class'));
        add_action('save_post',array( $this, 'save_post_meta_class'));

        add_action( 'show_user_profile', array( $this, 'show_profile_class_field' ));
        add_action( 'edit_user_profile', array( $this, 'show_profile_class_field' ));

        add_action( 'personal_options_update', array( $this, 'save_profile_class_field' ));
        add_action( 'edit_user_profile_update', array( $this, 'save_profile_class_field' ));

        // add shortcode
        add_shortcode( 'timetable', array( $this, 'shortcode_timetable'));
        add_shortcode( 'video', array( $this, 'shortcode_video'));
        add_shortcode( 'game', array( $this, 'shortcode_game'));

        add_action('add_meta_boxes',array( $this, 'register_meta_box_helper')); 

    }
    function create_menu() {
        $menuSlug = __FILE__;
        add_menu_page('FGC Quiz Manager', 'FGC Quiz Manager', 'administrator', $menuSlug, array( $this, 'manager_class'),null,2);//fgc_settings_page
        add_submenu_page($menuSlug, "Class Manager", "Class", 'manage_options', $menuSlug . '-list-class',array( $this, 'manager_class'));
        add_submenu_page($menuSlug, "Timetable Manager", "Timetable", 'manage_options', $menuSlug . '-timetable',array( $this, 'manager_timetable'));
        add_submenu_page($menuSlug, "Game Manager", "Game", 'manage_options', $menuSlug . '-game',array( $this, 'manager_game'));
    }

    function manager_class() {
        include(PLUGIN_DIR.'class.php');
        $class = new Quiz_class;

        $action = isset($_GET['action']) ? $_GET['action'] : null;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        switch ($action) {
            case 'add':
                $class->add_class();
                break;
            case 'edit':
                $class->edit_class($id);
                break;
            case 'view':
                $class->view_class($id);
                break;
            case 'delete':
                $class->delete_class($id);
                break;
            
            case 'remove':
                $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
                $class->remove_from_class($user_id,$id);
                break;
            
            default:
                $class->list_class();
                break;
        }
        //$class->list_class();
    }
    function manager_timetable() {
        include(PLUGIN_DIR.'timetable.php');
        $timetable = new Quiz_timetable;
        $action = isset($_GET['action']) ? $_GET['action'] : null;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        switch ($action) {
            case 'add':
                $timetable->add_timetable();
                break;
            case 'edit':
                $timetable->edit_timetable($id);
                break;
            case 'view':
                $timetable->view_timetable($id);
                break;
            default:
                $timetable->list_class();
                break;
        }
        //$timetable->list_class();

    }
    
    function manager_game() {
        include(PLUGIN_DIR.'game.php');
        $game = new Quiz_game;

        $action = isset($_GET['action']) ? $_GET['action'] : null;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        switch ($action) {
            case 'add':
                $game->add_game();
                break;
            case 'edit':
                $game->edit_game($id);
                break;
            case 'view':
                //$game->view_game($id);
                break;
            case 'delete':
                $game->delete_game($id);
                break;
            
            default:
                $game->list_game();
                break;
        }
    }
    // add meta box in page Add new post
    function register_meta_box_class() {
        add_meta_box( 'class-name', 'Class', array($this,'print_box_class_name'));
    }
     
    // print html meta box enter class name
    function print_box_class_name($post) {
        global $wpdb;
        $list_class = $wpdb->get_results( "SELECT * FROM $this->table_class ", ARRAY_A);
        $post_class_id = get_post_meta($post->ID,'_class_id',true);

        wp_nonce_field( 'nonce_meta_box_classname', 'nonce_meta_box_classname');
        echo '<label for="class_name">This post belong to class: </label>';
        //echo '<input type="text" name="classname" value="'.$classname.'" />';
        if ( is_admin() ) {
            echo '<select name="class_id">
                <option value="">-- Select class --</option>';
                //foreach ($list_classname as $classname => $member) {
                foreach ($list_class as $class) {
                    echo '<option value="'.$class['id'].'"'.($class['id'] == $post_class_id ? ' selected' : '').'>'.$class['name'].'</option>';
                }
                echo '</select>';
        }
    }
    function save_post_meta_class($post_id) {
        if(!isset($_POST['nonce_meta_box_classname'])) return;
        if(!wp_verify_nonce($_POST['nonce_meta_box_classname'],'nonce_meta_box_classname')) return;
        
        if(!empty($_POST['class_id'])) {
            $class_id = sanitize_text_field($_POST['class_id']);
            update_post_meta( $post_id, '_class_id', $class_id);
        }
    }
    function show_profile_class_field( $user ) {
	    global $wpdb;
        $list_class = $wpdb->get_results( "SELECT * FROM $this->table_class ", ARRAY_A);
        $class_id = (int) get_the_author_meta('_class_id', $user->ID );
        if($class_id) $class = (array) $wpdb->get_row("SELECT * FROM $this->table_class WHERE id = ".$class_id);
        else $class = array('id'=>null,'name'=>'','member'=>0);

        echo '<h3>Extra profile information</h3>
            <table class="form-table">
                <tr>
                    <th><label for="twitter">Class name:</label></th>
                    <td>';
			if ( ! current_user_can('administrator')) {
				echo '<input type="text" value="'.$class['name'].'" class="regular-text" disabled /><br />
				<span class="description">Your class name.</span>';
			} else {
                echo '<input type="hidden" name="class_old" value="'.$class_id.'" />
				<span class="description">Your class name.</span>';
				echo '<select name="class_id">
					<option value="">-- Select class --</option>';
					foreach ($list_class as $class) {
						echo '<option value="'.$class['id'].'"'.($class_id == $class['id'] ? ' selected' : '').'>'.$class['name'].' ('.$class['members'].' members)</option>';
					}
					echo '</select>';
			}
			echo '</td>
                </tr>
            </table>';
    }

    function save_profile_class_field( $user_id ) {
        global $wpdb;
        //$list_class = $wpdb->get_results( "SELECT * FROM $this->table_class ", ARRAY_A);

        $class_old = (int) sanitize_text_field($_POST['class_old']);
        $class_id = (int) sanitize_text_field($_POST['class_id']);
        if($class_id != $class_old) {
            update_usermeta( $user_id, '_class_id', $class_id );
            if($class_old) {
                $sql = "UPDATE $this->table_class SET members = members-1 WHERE id = $class_old;";
                $wpdb->query($wpdb->prepare($sql));
            }
            if($class_id) {
                $sql = "UPDATE $this->table_class SET members = members+1 WHERE id = $class_id;";
                $wpdb->query($wpdb->prepare($sql));
            }
            //$message = "Class old: $class_old , class new: $class_id . <br />".$sql;
            //printf(  $message ); exit;
        }
    }

    function shortcode_timetable($args,$content=null) {
        global $wpdb,$current_user;
        extract(shortcode_atts(array(
            'classname' => null,
        ), $args));

        //$timetable = null;
        //$classname = 'A1';
        include(PLUGIN_DIR.'timetable.php');
        $timetable = new Quiz_timetable;

        $html = '';
        if (!$classname && current_user_can('administrator')) {
            //$timetable = $wpdb->get_results( "SELECT * FROM $this->table_timetable ", ARRAY_A);
            $list_class = $wpdb->get_results( "SELECT * FROM $this->table_class ", ARRAY_A);
            foreach ($list_class as $class) {
                //$html .= '<h2>Timetable of class '.$classname.'</h2>';
                $html .= $timetable->view_timetable($class['id'],true);
            }
        } else {
            if($classname) {
                //$sql = "SELECT * FROM $this->table_timetable INNER JOIN $this->table_class ON $this->table_timetable .class_id = $this->table_class .id WHERE $this->table_class .name = '$classname'";
                //$timetable = $wpdb->get_row($sql);
                $html .= $timetable->view_timetable($classname,true);
            }
            if(!$timetable && is_user_logged_in()) {
                $current_user = wp_get_current_user();
                $class_id = get_the_author_meta('_class_id', $current_user->ID );
                if($class_id) {
                    //$sql = "SELECT * FROM $this->table_timetable WHERE class_id = ".$class_id ;
                    //$timetable = $wpdb->get_row($sql);
                    $html .= $timetable->view_timetable($class_id,true);
                }
            } else {
                $html .= 'Please login to view your timetable!';
            }
        }
        if(empty($html)) $html = 'No have data timetable';
        return $html;

    }

    // add shortcode to print timetable for page and post
    function shortcode_video($args,$content=null) {
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

    function shortcode_game($args,$content=null) {
        extract(shortcode_atts(array(
            'url' => null,
            'width' => '550px',
            'height' => '400px',
        ), $args));

        ob_start();
        ?>
        <div style="<?php echo 'width:100%;height:'.$height.';'; ?>">
            <object id="flashcontent" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
                <param name="movie" value="<?php echo $url; ?>" />
                
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" 
                        data="<?php echo $url; ?>" 
                        width="<?php echo $width; ?>" 
                        height="<?php echo $height; ?>">
                <!--<![endif]-->
                
                    <p>
                    Fallback or 'alternate' content goes here.
                    This content will only be visible if the SWF fails to load.
                    </p>
                
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->

                </object>
            </div>
            <?php
        $html = ob_get_clean();
        return $html;
    }

    // add meta box helper in page Add new post
    function register_meta_box_helper() {
        add_meta_box( 'fgc-quiz-helper', 'Hưỡng dẫn dùng shortcode', array($this,'print_box_helper'));
    }

    // print html meta box enter class name
    function print_box_helper($post) {
        echo '<p><code>[timetable classname="B"]</code> to print timetable of class B, leave empty classname to auto select by user login.</p>';
        echo '<p><code>[video url="http://..." width="560px" height="315px"]</code> to insert video player. Support youtube.com and voatiengviet.com</p>';
        echo '<p><code>[game url="http://.../game.swf"]</code> to insert game flash.</p>';

    }
    static function install() {
        global $wpdb;
        $installed_ver = get_option( "fgc_quiz_version" );
        //$installed_ver = '2';
        if ( $installed_ver != PLUGIN_VERSION) {
            // $wpdb->dbname;
            $charset_collate = $wpdb->get_charset_collate();
            $table_class = $wpdb->prefix . "fgc_class";
            $table_timetable = $wpdb->prefix . "fgc_timetable";
            $table_game = $wpdb->prefix . "fgc_game";
            $insert_data_class = $insert_data_timetable = $insert_data_game = true;
            $sql = '';
            $message = [];

            if(FORCE_INSTALL==true) {
                $wpdb->query( "DROP TABLE IF EXISTS $table_timetable" );
                $wpdb->query( "DROP TABLE IF EXISTS $table_class" );
                $wpdb->query( "DROP TABLE IF EXISTS $table_game" );
            }
            // id INT(5) NOT NULL AUTO_INCREMENT,
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_class'") != $table_class) {
                $sql .= "CREATE TABLE ".$table_class ." (
                    id INT(5) NOT NULL AUTO_INCREMENT,
                    name varchar(50) NOT NULL UNIQUE,
                    members INT(5) UNSIGNED DEFAULT 0,
                    public 	tinyint(1) DEFAULT 1,
                    PRIMARY KEY (id)
                ) $charset_collate;";
                
            } else {
                $insert_data_class = false;
                $message[] = 'Table '.$table_class.' exist!';
            }
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_timetable'") != $table_timetable) {
                $sql .= "CREATE TABLE ".$table_timetable ." (
                    class_id INT(2) NOT NULL,
                    monday varchar(250),
                    tuesday varchar(250),
                    wednesday varchar(250),
                    thursday varchar(250),
                    friday varchar(250),
                    saturday varchar(250),
                    sunday varchar(250),
                    FOREIGN KEY (class_id) REFERENCES $table_class(id)
                ) $charset_collate;";
            } else {
                $insert_data_timetable = false;
                $message[] = 'Table '.$table_timetable.' exist!';
            }

            if ($wpdb->get_var("SHOW TABLES LIKE '$table_game'") != $table_game) {
                $sql .= "CREATE TABLE ".$table_game ." (
                    id INT(5) NOT NULL AUTO_INCREMENT,
                    name varchar(50) NOT NULL,
                    url text NOT NULL,
                    public 	tinyint(1) DEFAULT 1,
                    PRIMARY KEY (id)
                ) $charset_collate;";
            } else {
                $insert_data_game = false;
                $message[] = 'Table '.$table_game.' exist!';
            }

            if(!empty($sql)) {
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
                update_option( "fgc_quiz_version", PLUGIN_VERSION);

                if($insert_data_class==true) {
                    $wpdb->insert($table_class, array('name' => 'A1', 'members' => 0, 'public' => 1));
                    $wpdb->insert($table_class, array('name' => 'A2', 'members' => 0, 'public' => 1));
                    $wpdb->insert($table_class, array('name' => 'B', 'members' => 0, 'public' => 1));
                    $wpdb->insert($table_class, array('name' => 'C', 'members' => 0, 'public' => 1));

                    $message[] = 'Insert data class success!';
                }
                if($insert_data_timetable==true) {
                    $wpdb->insert($table_timetable, array('class_id' => 1));
                    $wpdb->insert($table_timetable, array('class_id' => 2));
                    $wpdb->insert($table_timetable, array('class_id' => 3));
                    $wpdb->insert($table_timetable, array('class_id' => 4));

                    $message[] = 'Insert data timetable success!';
                }
                if($insert_data_game==true) {
                    $wpdb->insert($table_game, array('name' => 'Game 1', 'url' => 'http://english.training.fgct.net/images/games/freedom_-spot-the-difference/Freedom.swf'));
                    $wpdb->insert($table_game, array('name' => 'Game 2', 'url' => 'http://english.training.fgct.net/images/games/fashion-girls_v586067/gcm_mochi.swf'));

                    $message[] = 'Insert data game success!';
                }
            } else {
                $message[] = 'No thing to query database!';
            }
            //$message = implode('<br />',$message);
            //echo "<div class='updated'><p>$message</p></div>";
            //exit();
        }
    }

}

register_activation_hook( __FILE__, array( 'FGC_Quiz', 'install' ) );
new FGC_Quiz();

?>