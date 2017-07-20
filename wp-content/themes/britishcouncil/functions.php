<?php 
class My_Walker_Nav_Menu extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"\">\n";
	}
}
class My_Walker_Nav_Menu_Footer extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = Array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"menu\">\n";
	}
}
function wpdocs_special_nav_class( $classes, $item ) {
	$new_class = [];
	if($item->menu_item_parent=='0') {
		$new_class[] = 'sf-depth-1';
	} else $new_class[] = 'sf-depth-2';
	if(in_array('menu-item-has-children',$item->classes)) {
		$new_class[] = 'menuparent';
	} else {
		$new_class[] = 'sf-no-children';
	}
	//var_dump($item);echo "\n\n\n\n";
	//if ( is_single() && 'Blog' == $item->title ) {
        // Notice you can change the conditional from is_single() and $item->title
        //$classes[] = "special-class";
    //}
    return $new_class;
}
add_filter( 'd' , 'wpdocs_special_nav_class' , 10, 2 );



function custom_body_class( $classes ) {
	$new_class = ['html front not-logged-in one-sidebar sidebar-second i18n-en page-views'];
    if ( in_array( 'category-games',$classes ) ) {
        $new_class[] = 'html not-front not-logged-in one-sidebar sidebar-second page-taxonomy page-taxonomy-term page-taxonomy-term- page-taxonomy-term-2395 i18n-en section-games page-views';
    }
    return $new_class;
}
add_filter( 'body_class', 'custom_body_class' );
?>