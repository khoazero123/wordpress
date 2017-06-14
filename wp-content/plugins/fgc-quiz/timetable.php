<?php
class Quiz_timetable {
    private $table_class;
    private $table_timetable;
    private $table_game;

    public $list_class;
    public $list_timetable;

    private $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

    function __construct() {
        global $wpdb;
        $this->table_class = $wpdb->prefix . "fgc_class";
        $this->table_timetable = $wpdb->prefix . "fgc_timetable";
        $this->table_game = $wpdb->prefix . "fgc_game";

        $this->list_class = $wpdb->get_results( "SELECT * FROM $this->table_class ", ARRAY_A);
        $this->list_timetable = $wpdb->get_results( "SELECT * FROM $this->table_timetable ", ARRAY_A);

    }

    public function list_class() {
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">List class</h1>
        <?php if( isset($_GET['settings-updated']) ) { ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Settings saved.') ?></strong></p>
            </div>
        <?php } ?>
        <table class="wp-list-table widefat fixed striped posts" style="width:60%">
        <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-author">Class name</th>
                <th scope="col" id="action" class="manage-column column-author">Action</th>
            </tr>
        </thead>

        <tbody id="the-list">
            <?php 
            foreach ($this->list_class as $class) {
                echo '<tr>
                    <td>'.$class['name'].'</td>
                    <td> <a href="?'.$_SERVER['QUERY_STRING'].'&action=view&id='.$class['id'].'">View</a> | <a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&id='.$class['id'].'">Edit</a> </td>
                </tr>';
            }
            ?>
        </tbody></table>
        </div>
        <?php 
    }
    public function edit_timetable($class_id) {
        global $wpdb;
        echo '<div class="wrap">';
        if(!$class_id) printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('No class select!'));
        else {
            $class = (array) $wpdb->get_row("SELECT * FROM $this->table_class WHERE id = ".$class_id);
            if(!$class) printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$class_id.' doesn\'t exist!'));
            else {
                $timetable = (array) $wpdb->get_row("SELECT * FROM $this->table_timetable WHERE class_id = ".$class_id);
                if(isset($_POST['submit']) && !empty($_POST['time'])) {
                    $update = [];
                    foreach ($_POST['time'] as $day => $time) {
                        if(!empty($time)) $update[$day] =  $time;
                    }
                    if(!empty($update)) {
                        $wpdb->update($this->table_timetable, $update, ['class_id'=>$class_id]);
                        $timetable = (array) $wpdb->get_row("SELECT * FROM $this->table_timetable WHERE class_id = ".$class_id);
                    }

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-success'), esc_html('Update timetable of class '.$class['name'].' success!') ); 
                }
                ?>
                <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
                    <?php wp_nonce_field( 'nonce_edit_timetable', 'nonce_edit_timetable'); ?>
                    <table class="wp-list-table widefat fixed striped posts" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" id="title" class="manage-column column-author">Member</th>
                                <?php 
                                foreach($this->days as $day) {
                                    echo '<th scope="col" id="count" class="manage-column column-author">'.ucfirst($day).'</th>';
                                } ?>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <?php
                            $args = array(
                                'meta_key'     => '_class_id',
                                'meta_value'   => $class_id,
                                'orderby'      => 'nicename',
                                'order'        => 'ASC',
                            ); 
                            $list_users = get_users( $args );
                            if(empty($list_users)) $list_users = array(1);
                            $i = 0;
                            foreach ( $list_users as $user ) {
                                $name = isset($user->user_nicename) ? $user->user_nicename : 'No member';
                                echo '<tr>
                                    <td>'.$name.'</td>';
                                if($i==0) foreach($this->days as $day) {
                                    $time = $timetable[$day];
                                    echo '<th scope="col" class="manage-column column-author" rowspan="'.count($list_users).'">
                                            <textarea name="time['.$day.']" style="width:100%">'.$time.'</textarea>
                                        </th>';
                                }
                                echo '</tr>';
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
                </form>
                <?php 
            }
        }
        echo '</div>';
    }
    public function view_timetable($class_id,$return=false) {
        global $wpdb;
        $html = '<div class="wrap">';
        
        if(!$class_id) $html .= '<div class="notice notice-error"><p>No class select!</p></div>';
        else {
            if(preg_match('/^(\d+)$/',$class_id))
                $class = (array) $wpdb->get_row("SELECT * FROM $this->table_class WHERE id = ".$class_id);
            else {
                $class = (array) $wpdb->get_row("SELECT * FROM $this->table_class WHERE name = '".$class_id."'");
                if($class) $class_id = $class['id'];
            }
            if(!$class) $html .= '<div class="notice notice-error"><p>Class '.$class_id.' doesn\'t exist!</p></div>';
            else {
                $html .= '<h1 class="wp-heading-inline">Timetable of class '.$class['name'].'</h1>';

                $timetable = (array) $wpdb->get_row("SELECT * FROM $this->table_timetable WHERE class_id = ".$class_id);
                ob_start();
                ?>
                    <table class="wp-list-table widefat fixed striped posts" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" id="title" class="manage-column column-author">Member</th>
                                <?php 
                                foreach($this->days as $day) {
                                    echo '<th scope="col" id="count" class="manage-column column-author">'.ucfirst($day).'</th>';
                                } ?>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <?php
                            $args = array(
                                'meta_key'     => '_class_id',
                                'meta_value'   => $class_id,
                                'orderby'      => 'nicename',
                                'order'        => 'ASC',
                            ); 
                            $list_users = get_users( $args );
                            if(empty($list_users)) $list_users = array(1);
                            $i = 0;
                            foreach ( $list_users as $user ) {
                                $name = isset($user->user_nicename) ? $user->user_nicename : 'No member';
                                echo '<tr>
                                    <td>'.$name.'</td>';
                                if($i==0) foreach($this->days as $day) {
                                    $time = $timetable[$day];
                                    echo '<th scope="col" class="manage-column column-author" rowspan="'.count($list_users).'">'.$time.'</th>';
                                }
                                echo '</tr>';
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php 
                $html .= ob_get_clean();
            }
        }
        $html .= '</div><br />';
        if($return) return $html;
        else echo $html;
    }
    
}