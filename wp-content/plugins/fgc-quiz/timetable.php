<?php
class Quiz_timetable {
    private $list_class;
    public $timetable;
    private $days = [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday',
                ];
    function __construct() {
        $this->list_class = get_option('quiz_options_course', []);
        $this->timetable = get_option('quiz_options_timetable', []);
        $timetable0 = [
            'B' => [
                'members' => [
                    1 => 'Khoa',
                    2 => 'Bao',
                    8 => 'Toan',
                ],
                'day' => [
                    'monday' => '9h-9h30',
                    'tuesday' => '9h-9h30',
                    'wednesday' => '9h-9h30',
                    'thursday' => '9h-9h30',
                    'friday' => '9h-9h30',
                    'saturday' => '9h-9h30',
                ]
            ],
        ];
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
                <th scope="col" id="count" class="manage-column column-author">Members</th>
                <th scope="col" id="public" class="manage-column column-author">Public</th>
                <th scope="col" id="action" class="manage-column column-author">Action</th>
            </tr>
        </thead>

        <tbody id="the-list">
            <?php 
            //var_dump($list_classname);
            //foreach ($list_classname as $classname => $member) {
            foreach ($this->list_class as $class) {
                $classname = $class['name'];
                $member = $class['members'];
                $public = $class['public'];
                echo '<tr>
                    <td>'.$classname.'</td>
                    <td> '.$member.' </td>
                    <td> '.($public===1 ? 'Public' : 'Private').' </td>
                    <td> <a href="?'.$_SERVER['QUERY_STRING'].'&action=view&classname='.$classname.'">View</a> | <a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&classname='.$classname.'">Edit</a> </td>
                </tr>';
            }
            ?>
        </tbody></table>
        </div>
        <?php 
    }
    public function edit_timetable($classname) {
        echo '<div class="wrap">
                <h2>Edit timetable of class: '.$classname.'</h2>';

        if(!$classname) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please enter class name to edit!') ); 
        if(!isset($this->list_class[$classname])) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!')); 

        if(isset($_POST['submit']) && !empty($_POST['time'])) {
            foreach ($_POST['time'] as $day => $time) {
                $this->timetable[$classname]['day'][$day] = $time;
            }
            update_option('quiz_options_timetable', $this->timetable);
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-success'), esc_html('Update timetable of class '.$classname.' success!') ); 
        }
        ?>
        <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <?php wp_nonce_field( 'nonce_edit_timetable', 'nonce_edit_timetable'); ?>
            <table class="wp-list-table widefat fixed striped posts" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" id="title" class="manage-column column-author">Member</th>
                        <?php 
                        //foreach($this->timetable[$classname]['day'] as $day => $time) {
                        foreach($this->days as $day) {
                            echo '<th scope="col" id="count" class="manage-column column-author">'.ucfirst($day).'</th>';
                        } ?>
                    </tr>
                </thead>

                <tbody id="the-list">
                    <?php
                    $args = array(
                        'meta_key'     => '_classname',
                        'meta_value'   => $classname,
                        'orderby'      => 'nicename',
                        'order'        => 'ASC',
                    );
                    $blogusers = (array) get_users( $args );
                    $i = 0;
                    //foreach ($this->timetable[$classname]['members'] as $user_id => $name) {
                    foreach ( $blogusers as $user ) {
                        $name = $user->user_nicename;
                        echo '<tr>
                            <td>'.$name.'</td>';
                        //if($i==0) foreach($this->timetable[$classname]['day'] as $day => $time) {
                        if($i==0) foreach($this->days as $day) {
                            $time = isset($this->timetable[$classname]['day'][$day]) ? $this->timetable[$classname]['day'][$day] : '';
                            echo '<th scope="col" class="manage-column column-author" rowspan="'.count($blogusers).'">
                                    <textarea name="time['.$day.']" style="width:100%">'.$time.'</textarea>
                                </th>';
                        }
                        echo '</tr>';
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
            </p>
        </form>
        </div>
        <?php 
    }
    public function view_timetable($classname,$return=false) {
        $html = '<div class="wrap">
                <h2>View timetable of class: '.$classname.'</h2>';

        if(!$classname) {
            $html .= '<div class="notice notice-error"><p>Please enter class name to edit!</p></div>';
            //return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please enter class name to edit!') ); 
        } elseif(!isset($this->list_class[$classname])) {
             $html .= '<div class="notice notice-error"><p>Class '.$classname.' doesn\'t exist!</p></div>';
            //return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!')); 
        }elseif(!isset($this->timetable[$classname])) {
            $html .= '<div class="notice notice-error"><p>Timetable of class '.$classname.' is empty!</p></div>';
            $html .= '<a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&classname='.$classname.'" class="page-title-action">Edit timetable this class</a>';
            //printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Timetable of class '.$classname.' is empty!'));
            //echo '<a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&classname='.$classname.'" class="page-title-action">Edit timetable this class</a>';
            //return;
        } else {
            ob_start();
        ?>
        <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <?php wp_nonce_field( 'nonce_edit_timetable', 'nonce_edit_timetable'); ?>
            <table class="wp-list-table widefat fixed striped posts" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" id="title" class="manage-column column-author">Member</th>
                        <?php 
                        if(isset($this->timetable[$classname]))
                        foreach($this->timetable[$classname]['day'] as $day => $time) {
                            echo '<th scope="col" id="count" class="manage-column column-author">'.ucfirst($day).'</th>';
                        } ?>
                    </tr>
                </thead>

                <tbody id="the-list">
                    <?php
                    $args = array(
                        'meta_key'     => '_classname',
                        'meta_value'   => $classname,
                        'orderby'      => 'nicename',
                        'order'        => 'ASC',
                    );
                    $blogusers = (array) get_users( $args );
                    $i = 0;
                    if(isset($this->timetable[$classname]))
                    //foreach ($this->timetable[$classname]['members'] as $user_id => $name) {
                    foreach ( $blogusers as $user ) {
                        $name = $user->user_nicename;
                        echo '<tr>
                            <td>'.$name.'</td>';
                        if($i==0) foreach($this->timetable[$classname]['day'] as $day) {
                            echo '<th scope="col" class="manage-column column-author" rowspan="'.count($blogusers).'">'.$day.'</th>';
                        }
                        echo '</tr>';
                        $i++;
                    }
                echo '</tbody>
            </table>
            <p class="submit">
                <a class="button button-cancel" href="?'.$_SERVER['QUERY_STRING'].'&action=edit&classname='.$classname.'">Edit</a>
            </p>
        </form>';
        $html .= ob_get_clean();
        }
       $html .= '</div>';
       if($return) return $html;
       else echo $html;
    }
    
}