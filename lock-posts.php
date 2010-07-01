<?php
/*
Plugin Name: Lock Posts
Plugin URI: 
Description:
Author: Andrew Billits
Version: 1.0.1
Author URI:
*/ 

/* 
Copyright 2007-2009 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//------------------------------------------------------------------------//
//---Config-----------------------------------------------------------------//
//------------------------------------------------------------------------//

//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//

add_action('admin_menu', 'lock_posts_meta_box');
add_action('save_post', 'lock_posts_update');
add_action('init', 'lock_posts_check');
add_action('manage_posts_custom_column', 'lock_posts_status_output', 2, 2);
add_filter('manage_posts_columns', 'lock_posts_status_column');
add_action('manage_pages_custom_column', 'lock_posts_status_output', 2, 2);
add_filter('manage_pages_columns', 'lock_posts_status_column');

//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//

function lock_posts_status_column($columns) {
	$new_column = array('lock_status' => __('Lock Status'));
	$columns = array_merge($columns, $new_column);
	return $columns;
}

function lock_posts_meta_box() {
	if ( is_site_admin() ) {
		if ($_GET['action'] == 'edit'){
			add_meta_box('postlock', __('Post Status'), 'lock_posts_meta_box_output', 'post', 'advanced', 'high');
			add_meta_box('postlock', __('Post Status'), 'lock_posts_meta_box_output', 'page', 'advanced', 'high');
		}
	}
}

function lock_posts_update($post_id) {
	if ( !empty( $_POST['post_lock_status'] ) ) {
		update_post_meta($post_id, '_post_lock_status', $_POST['post_lock_status']); 
	}
}

function lock_posts_check() {
	if ( $_GET['action'] == 'edit' && !empty( $_GET['post'] ) && !is_site_admin() ) {
		$post_lock_status = get_post_meta($_GET['post'], '_post_lock_status');
		if ( is_array($post_lock_status) ) {
			$post_lock_status = $post_lock_status[0];
		}
		if ( $post_lock_status == 'locked' ) {
			wp_redirect('locked.php');
		}
	}
}

//------------------------------------------------------------------------//
//---Output Functions-----------------------------------------------------//
//------------------------------------------------------------------------//

function lock_posts_status_output($column, $id) {
	global $post;
	if ( $column == 'lock_status' ) {
		$post_lock_status = get_post_meta($id, '_post_lock_status');
		if ( is_array($post_lock_status) ) {
			$post_lock_status = $post_lock_status[0];
		}
		if ( $post_lock_status == 'locked' ) {
			echo __('Locked');
		} else {
			echo __('Unlocked');
		}
	}
}

function lock_posts_meta_box_output($post) {
	$post_lock_status = get_post_meta($post->ID, '_post_lock_status');
	if ( is_array($post_lock_status) ) {
		$post_lock_status = $post_lock_status[0];
	}
	if ( empty( $post_lock_status ) ) {
		$post_lock_status = 'unlocked';
	}
	?>
	<div id="postlockstatus">
	<label class="hidden" for="excerpt">Post Status</label>
	<select name="post_lock_status">
	<option value="locked" <?php if ($post_lock_status == 'locked') echo 'selected="selected"'; ?>><?php _e('Locked') ?></option>
	<option value="unlocked" <?php if ($post_lock_status == 'unlocked') echo 'selected="selected"'; ?>><?php _e('Unlocked') ?></option>
	</select>
	<p><?php _e('Locked posts cannot be edited by anyone other than site admins.'); ?></p>
	</div>
	<?php
}


//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

function lock_posts_locked() {
	if (isset($_GET['updated'])) {
		?><div id="message" class="updated fade"><p><?php _e(urldecode($_GET['updatedmsg'])) ?></p></div><?php
	}
	echo '<div class="wrap">';
	switch( $_GET[ 'action' ] ) {
		//---------------------------------------------------//
		default:
			?>
            <h2><?php _e('Post Locked') ?></h2>
            <p><?php _e('This post has been locked by a site administrator.') ?></p>
            <?php
		break;
		//---------------------------------------------------//
	}
	echo '</div>';
}

?>