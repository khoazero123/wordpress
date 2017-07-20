<!-- START MENU -->
<?php
$args = [
    'container' => 'div',
    'container_id' => '',
    'container_class' => 'menu-block-wrapper menu-block-1 menu-name-menu-section-menu parent-mlid-0 menu-level-1',
    'menu_id' => '',
    'menu_class' => 'menu',
    //'items_wrap'      => '<ul class="site-nav__list">%3$s</ul>',
    'walker' => new My_Walker_Nav_Menu_Footer(),
];
wp_nav_menu($args);
?>
