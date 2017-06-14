<?php
class Quiz_class {
    private $list_class;
    function __construct() {
        $this->list_class = get_option('quiz_options_course', []);
    }

    public function list_class() {
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">List class</h1> | <a href="?<?php echo $_SERVER['QUERY_STRING']; ?>&action=add" class="page-title-action">Add Class</a>
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
                    <td> <a href="?'.$_SERVER['QUERY_STRING'].'&action=view&classname='.$classname.'">View</a> | <a href="?'.$_SERVER['QUERY_STRING'].'&action=delete&classname='.$classname.'">Delete</a> | <a href="?'.$_SERVER['QUERY_STRING'].'&action=edit&classname='.$classname.'">Edit</a> </td>
                </tr>';
            }
            ?>
        </tbody></table>
        </div>
        <?php 
    }
    public function view_class($classname) {
        echo '<div class="wrap">
                <h2>List member of class: '.$classname.'</h2>';
        if(!$classname) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('No class select!')); 
        if(!isset($this->list_class[$classname])) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!'));
        else {
            $args = array(
                'meta_key'     => '_classname',
                'meta_value'   => $classname,
                'orderby'      => 'nicename',
                'order'        => 'ASC',
            ); 
            $blogusers = get_users( $args );//'orderby=nicename'
            ?>
            <table class="wp-list-table widefat fixed striped posts" style="width:60%">
                <thead>
                    <tr>
                        <th scope="col" id="title" class="manage-column column-author">Name</th>
                        <th scope="col" id="count" class="manage-column column-author">Email</th>
                        <th scope="col" id="action" class="manage-column column-author">Action</th>
                    </tr>
                </thead>

                <tbody id="the-list">
                    <?php
                    foreach ( $blogusers as $user ) {
                        echo '<tr>';
                        echo '<td>' . esc_html( $user->user_nicename ) . '</td>';
                        echo '<td>' . esc_html( $user->user_email ) . '</td>';
                        //echo '<td>' . esc_html(get_the_author_meta('_classname', $user->ID )) . '</td>';
                        echo '<td> <a href="?'.$_SERVER['QUERY_STRING'].'&action=remove&user_id='.$user->ID.'&classname='.$classname.'">Remove from class</a> </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        echo '</div>';
    }
    public function edit_class($name) {
        echo '<div class="wrap">
                <h2>Edit class: '.$name.'</h2>';

        if(!$name) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please enter class name to edit!') ); 
        if(!isset($this->list_class[$name])) return printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$name.' doesn\'t exist!')); 

        if( isset($_POST['submit']) ) {
            $tmp_class = $this->list_class[$name];
            unset($this->list_class[$name]);

            $classname = sanitize_text_field($_REQUEST['classname']);
            $this->list_class[$classname] = $tmp_class;
            $this->list_class[$classname]['name'] = $classname;
            $this->list_class[$classname]['public'] = (isset($_POST['public']) && $_POST['public']==1) ? 1 : 0;
            if(!isset($this->list_class[$classname]['members'])) $this->list_class[$classname]['members'] = 0;
            
            update_option('quiz_options_course', $this->list_class);

             ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Class saved.') ?></strong></p>
            </div>
        <?php } ?>
        <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <?php wp_nonce_field( 'nonce_edit_class', 'nonce_edit_class'); ?>
            <input type="hidden" name="class_key" value="<?php echo $name; ?>">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Class name: </th>
                    <td><input type="text" name="classname" value="<?php echo $this->list_class[$name]['name']; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Public class: </th>
                    <td><input type="checkbox" name="public" value="1" id="public"<?php if($this->list_class[$name]['public']==1) echo ' checked="checked"'; ?>></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
                <input type="submit" name="submit" class="button button-cancel" value="Delete">
            </p>
        </form>
        </div>
        <?php 
    }
    public function add_class() {
    echo '<div class="wrap">
                <h2>Add new class</h2>';
        if( isset($_POST['submit']) ) {
            $classname = sanitize_text_field($_REQUEST['classname']);
            if(!$classname) {
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Please enter class name!') );
            } elseif(isset($this->list_class[$classname]))
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$name.'  exist!'));
            else {
                $this->list_class[$classname] = [
                                            'name' => $classname,
                                            'members' => 0,
                                            'public' => (isset($_POST['public']) && $_POST['public']==1) ? 1 : 0,
                                        ];

                update_option('quiz_options_course', $this->list_class);
                echo '<div id="message" class="updated">
                    <p><strong>Add class '.$classname.' success.</strong></p>
                </div>';
            }
        } ?>
        <form action="admin.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <?php wp_nonce_field( 'nonce_add_class', 'nonce_add_class'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Class name: </th>
                    <td><input type="text" name="classname" value="" /></td>
                </tr>
                <tr>
                    <th scope="row">Public class: </th>
                    <td><input type="checkbox" name="public" value="1" id="public"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Add">
                <input type="submit" name="submit" class="button button-cancel" value="Cancel">
            </p>
        </form>
    </div>
    <?php 
    }
    public function remove_from_class($user_id,$classname) {
        $user = get_userdata($user_id);

        echo '<div class="wrap">';
        if(!$classname) {
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('No class select!') );
        } elseif(!isset($this->list_class[$classname])) {
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!'));
        } elseif(!$user)
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('User '.$user_id.' doesn\'t exist!'));
        else {
            update_usermeta( $user_id, '_classname', '');
            $this->list_class[$classname]['members'] = (int) $this->list_class[$classname]['members'] - 1;
            update_option('quiz_options_course', $this->list_class);
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-success'), esc_html('Remove '.$user->user_nicename.' from class '.$classname.' success.'));
        }
        echo '</div>';

    }
    public function delete_class($classname) {
        echo '<div class="wrap">';
        if(!$classname) {
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('No class to delete!') );
        } elseif(!isset($this->list_class[$classname]))
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html('Class '.$classname.' doesn\'t exist!'));
        else {
            unset($this->list_class[$classname]);
            update_option('quiz_options_course', $this->list_class);
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-success'), esc_html('Delete class '.$classname.' success!'));
        }
        echo '</div>';
    }
}