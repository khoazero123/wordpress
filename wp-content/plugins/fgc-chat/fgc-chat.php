<?php 
/**
 * Plugin Name: FGC Chat
 * Plugin URI: http://localhost
 * Description: Plugin manager point, required plugin wp-pro-quiz
 * Version: 1.0
 * Author: khoazero123
 * Author URI: http://localhost
 * License: GPLv2
 */

class FGC_Chat {
    function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'load_my_scripts'));
        add_action('wp_head', array(&$this, 'print_script_head'));

        add_action('admin_enqueue_scripts', array(&$this, 'load_my_scripts'));
        add_action('admin_head', array(&$this, 'print_script_head'));
    }
    function load_my_scripts() {
        wp_enqueue_script('pusher', 'https://js.pusher.com/4.1/pusher.min.js');
        wp_enqueue_script('fgc-chat', plugins_url( "js/main.js", __FILE__ ), null, filemtime(__DIR__ .'/js/main.js'), false );
    }
    function print_script_head() {
?>
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('7e863eb7e72ea08042e2', {
            cluster: 'ap1',
            encrypted: false
        });
        FGC_Chat.main(pusher);
    </script>
<?php
    }
}

new FGC_Chat();

?>