<?php
/*
Plugin Name: IE9 Pinning
Plugin URI: http://www.thanerd.net/ie9-pinning
Description: Adds some of the new Internet explorer 9 features to your wordpress blog
Version: 1.1.0
Author: David Collin
Author URI: http://www.thanerd.net/
*/

/*  Copyright 2010  David Collin (email : thanerd@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action( 'wp_head', 'ie9_pinning_head' );
add_action( 'init', 'ie9_pinning_init' );
add_action( 'admin_menu', 'ie9_pinning_admin_menu' );

function ie9_pinning_init() {
	global $z;
    $currentLocale = get_locale();
    if(!empty($currentLocale)) {
        $moFile = dirname( __FILE__ ) . '/'. $z . '-' . $currentLocale . ".mo";
        if( @file_exists( $moFile ) && is_readable( $moFile ) )
			load_textdomain( $z, $moFile );
    }
	add_option( 'ie9_pinning_toolbar_color', '#FF0000', null, 'yes' );
}

function ie9_pinning_head() {
	global $z;
	if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) === FALSE ) {
		return;
	}

	echo '<meta name="application-name" content="' . get_bloginfo( 'name' ) . '" />';
	echo '<meta name="msapplication-tooltip" content="' . get_bloginfo( 'description' ) . '" />';
	echo '<meta name="msapplication-starturl" content="' . get_bloginfo( 'url' ) . '"/>';
	echo '<meta name="msapplication-window" content="width=device-width;height=device-height" />';
	if( current_user_can( 'publish_posts' ) ) {
		echo '<meta name="msapplication-task" content="name=' . __( 'Write a post', $z ) . ';action-uri=' . admin_url() . 'post-new.php;icon-uri=' . plugins_url('post.ico', __FILE__) . '" />'."\r\n";
	}
	if( current_user_can( 'moderate_comments' ) ) {
		echo '<meta name="msapplication-task" content="name=' . __( 'Moderate comments', $z ) . ';action-uri=' . admin_url() . 'edit-comments.php?comment_status=moderated;icon-uri=' . plugins_url('comment.ico', __FILE__) . '" />'."\r\n";
	}
	if( current_user_can( 'upload_files' ) ) {
		echo '<meta name="msapplication-task" content="name=' . __( 'Upload new media', $z ) . ';action-uri=' . admin_url() . 'media-new.php;icon-uri=' . plugins_url('media.ico', __FILE__) . '" />'."\r\n";
	}
	echo '<meta name="msapplication-navbutton-color" content="' . strtoupper( get_option( 'ie9_pinning_toolbar_color' ) ) . '"/>';
	echo '<script type="text/javascript">' . "\r\n";
	echo 'window.external.msSiteModeCreateJumplist("' . __( 'Recent posts', $z ) . '");'."\r\n";

	$lastposts = get_posts('numberposts=5');
	$lastposts = array_reverse( $lastposts, true );
	foreach($lastposts as $post) {
		echo 'window.external.msSiteModeAddJumpListItem("' . $post->post_title . '", "' . get_permalink( $post->ID ) . '", "' . plugins_url('post.ico', __FILE__) . '");'."\r\n";
	}
	echo 'window.external.msSiteModeShowJumplist();'."\r\n";
	echo '</script>'."\r\n";
}

function ie9_pinning_admin_menu() {
	global $z;
	$page = add_options_page( __( 'IE9 Pinning Options', $z ), __( 'IE9 Pinning Options', $z ), 'manage_options', 'ie9_pinning', 'ie9_pinning_options' );
	add_action( 'admin_print_scripts-' . $page, 'ie9_pinning_options_head_script' );
	add_action( 'admin_print_styles-' . $page, 'ie9_pinning_options_head_style' );
}

function ie9_pinning_options_head_script() {
	wp_enqueue_script( 'farbtastic' );
}

function ie9_pinning_options_head_style() {
	wp_enqueue_style( 'farbtastic' );
}

function ie9_pinning_options() {
	global $z;

	if( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', $z) );
	}
    if (isset($_POST['ie9_pinning_save'])) {
        check_admin_referer('ie9_pinning_save');
		update_option( 'ie9_pinning_toolbar_color', trim( $_POST['ie9_pinning_toolbar_color_input'] ) );
	}
	$ie9_pinning_toolbar_color = get_option( 'ie9_pinning_toolbar_color' );

	echo '<div class="wrap">';
    echo '<div class="icon32"><img src="' . plugins_url('ie9-icon.png', __FILE__) . '" /></div>';
    echo '<h2>' . __( 'Internet Explorer 9 Pinning settings', $z ) . '</h2>';
	echo '<h3>' . __( 'Pick the color you want for your visitor\'s browser buttons', $z ) . '</h3>';
	echo '<div style="width: 495px; margin-left: auto; margin-right: auto;">';
	echo '<div id="ie9_pinning_toolbar_color" style="position: relative; 10px; top: 65px; left: 10px; width: 98px; height: 56px; background-color: $ie9_pinning_toolbar_color;"></div>';
	echo '<img style="position: relative; top: 1px; left: 1px;" src="' . plugins_url('ie9window.png', __FILE__) . '"/>';
	echo '<div style="float:right; border: 1px solid transparent;" id="ie9picker"></div>';
	echo '<iframe src="' . get_bloginfo( 'url' ) . '" style="float: left;position: relative; top: -139px; left: 9px; width: 289px; height: 139px; overflow: hidden;"></iframe>';
	echo <<<endJS
<script type="text/javascript">
var f;
function changeColor(color) {
	jQuery("#ie9_pinning_toolbar_color").css("background-color", color);
	jQuery("#ie9_pinning_toolbar_color_input").val(color);
}
jQuery(document).ready(
	function() {
		var f = jQuery.farbtastic("#ie9picker", function(color) { changeColor(color); } );
		f.setColor( "$ie9_pinning_toolbar_color" );
	}
);
</script>
endJS;
	echo '</div>';
	echo '<div class="clear"></div>';
    echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
    wp_nonce_field('ie9_pinning_save');
	echo '<input type="hidden" name="ie9_pinning_toolbar_color_input" id="ie9_pinning_toolbar_color_input" value=""/>';
	echo '<input type="submit" name="ie9_pinning_save" value="' . __( 'Save options',$z ) . '" class="button-primary" id="ie9_pinning_save"/>';
	echo '</form>';
	echo '<p>' . __( 'Please note you must de-pin and re-pin your website for the color feature to be applied!', $z ) . '</p>';
	echo '<h3>' . __( 'What else does this do?', $z) . '</h3>';
	echo '<p>' . __( 'This will add a few other features beyond the navigation buttons color.', $z );
	echo '<ul>';
	echo '<li>' . __( 'Add a link to the 5 most recent blog posts in the context menu of the taskbar link.', $z ) . '</li>';
	echo '<li>' . sprintf(__( 'Add a <em>%s</em> item for users who are allowed to', $z ), __( 'Write a post', $z ) ) . '</li>';
	echo '<li>' . sprintf(__( 'Add a <em>%s</em> item for users who are allowed to', $z ), __( 'Moderate comments', $z ) ) . '</li>';
	echo '<li>' . sprintf(__( 'Add a <em>%s</em> item for users who are allowed to', $z ), __( 'Upload new media', $z ) ) . '</li>';
	echo '<li>' . sprintf(__( 'Add <em>%s</em> as the <em>name</em> of the site.', $z ), get_bloginfo( 'name' ) ) . '</li>';
	echo '<li>' . sprintf(__( 'Add <em>%s</em> as the tooltip when hovering the site\'s button on taskbar or start menu.', $z ), get_bloginfo( 'description' ) ) . '</li>';
	echo '</ul>';
	echo '</div>';
}

$z = 'IE9-Pinning';