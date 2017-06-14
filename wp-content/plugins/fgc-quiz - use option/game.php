<?php
class Quiz_game {
    private $games = [];
    function __construct() {
        $this->games = get_option('quiz_options_game', []);

        $this->games= [
            'game1' => [ // md5(url)
                'name' => 'Game 1',
                'url' => 'http://english.training.fgct.net/images/games/game1/f-661.swf',
                'public' => 1,
            ],
            'game2' => [
                'name' => 'Game 2',
                'url' => 'http://english.training.fgct.net/images/games/game2/b_verbs.swf',
                'public' => 1,
            ],
        ];
    }

    public function list_game() {
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">List game</h1>
        <?php if( isset($_GET['settings-updated']) ) { ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Settings saved.') ?></strong></p>
            </div>
        <?php } ?>
        <table class="wp-list-table widefat fixed striped posts" style="width:60%">
        <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-author">Name</th>
                <th scope="col" id="count" class="manage-column column-author">Url</th>
                <th scope="col" id="public" class="manage-column column-author">Public</th>
                <th scope="col" id="action" class="manage-column column-author">Action</th>
            </tr>
        </thead>

        <tbody id="the-list">
            <?php 
            foreach ($this->games as $slug => $game) {
                echo '<tr>
                    <td>'.$game['name'].'</td>
                    <td> '.$game['url'].'</td>
                    <td> '.($game['public']===1 ? 'Public' : 'Private').' </td>
                    <td> <a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&slug='.$slug.'">Edit</a> | <a href="?'.$_SERVER['QUERY_STRING'].'&action=delete&slug='.$slug.'">Delete</a> </td>
                </tr>';
            }
            ?>
        </tbody></table>
        </div>
        <?php 
    }
    public function edit_game($slug='Unknown') {
        echo '<div class="wrap">
                <h2>Edit game: '.$slug.'</h2>';

        if(!$slug) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please give a game to edit!') ); 
        if(!isset($this->games[$slug])) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Game '.$slug.' doesn\'t exist!')); 

        if(isset($_POST['submit']) && !empty($_POST['time'])) {
            if(!empty($_POST['name'])) printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please give a game!') ); 
            elseif(!empty($_POST['url'])) printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please give URL of game!') ); 
            else {
                $this->games[$slug] = [
                    'name' => sanitize_text_field($_REQUEST['name']),
                    'url' => $_REQUEST['url'],
                    'public' => (isset($_POST['public']) && $_POST['public']==1) ? 1 : 0,
                ];

                update_option('quiz_options_game', $this->games);
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-success'), esc_html('Update game '.$slug.' success!') ); 
            }
        }
        ?>
        <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <?php wp_nonce_field( 'nonce_edit_game', 'nonce_edit_game'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Name: </th>
                    <td><input type="text" name="name" value="<?php echo $this->games[$slug]['name']; ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">URL: </th>
                    <td><input style="width:500px" type="text" name="url" value="<?php echo $this->games[$slug]['url']; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Public: </th>
                    <td><input type="checkbox" name="public" value="1" id="public"<?php if($this->games[$slug]['public']==1) echo ' checked="checked"'; ?>></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
                <input type="button" name="submit" onclick="location.href='?<?php echo $_SERVER['QUERY_STRING']; ?>&action=delete&slug=<?php echo $slug; ?>';" class="button button-cancel" value="Delete">
            </p>
        </form>
        </div>
        <?php 
    }
    public function view_game($classname,$return=false) {
        $html = '<div class="wrap">
                <h2>View timetable of class: '.$classname.'</h2>';

        if(!$classname) {
            $html .= '<div class="notice notice-error"><p>Please enter class name to edit!</p></div>';
            //return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please enter class name to edit!') ); 
        } elseif(!isset($this->list_class[$classname])) {
             $html .= '<div class="notice notice-error"><p>Class '.$classname.' doesn\'t exist!</p></div>';
            //return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!')); 
        }elseif(!isset($this->timetable[$classname])) {
            $html .= '<div class="notice notice-error"><p>Timetable of class '.$classname.' is empty! <a href="'.site_url().'/wp-admin/admin.php?page=fgc-quiz%2Ffgc-quiz.php-timetable&action=edit&classname='.$classname.'" class="page-title-action">Edit timetable this class</a></p></div>';

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
                    if(empty($blogusers)) $blogusers = array(1);
                    $i = 0;
                    if(isset($this->timetable[$classname]))
                    //foreach ($this->timetable[$classname]['members'] as $user_id => $name) {
                    foreach ( $blogusers as $user ) {
                        $name = isset($user->user_nicename) ? $user->user_nicename : 'No member';
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
       $html .= '</div><br />';
       if($return) return $html;
       else echo $html;
    }
    
}