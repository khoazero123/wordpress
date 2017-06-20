<?php 
class My_Walker_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = Array()) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"\">\n";
  }
}
function wpdocs_special_nav_class( $classes, $item ) {
    //if ( is_single() && 'Blog' == $item->title ) {
        // Notice you can change the conditional from is_single() and $item->title
        //$classes[] = "special-class";
        $classes[] = "middle even sf-item-2 sf-depth-1 sf-total-children-13 sf-parent-children-2 sf-single-children-11 menuparent";
   // }
    return $classes;
}
//add_filter( 'nav_menu_css_class' , 'wpdocs_special_nav_class' , 10, 2 );

?>