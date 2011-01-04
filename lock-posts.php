<?php
/*
Plugin Name: Lock Posts
Plugin URI: http://premium.wpmudev.org/project/lock-posts
Description: This plugin allows site admin to lock down posts on any blog so that regular ol' users just can't edit them - for example, with a school assignment - stop it from being edited after submission.
Author: Andrew Billits, Ulrich Sossou
Version: 1.0.2
Text Domain: lock_posts
Author URI: http://premium.wpmudev.org/
WDP ID: 83
*/

/*
Copyright 2007-2011 Incsub (http://incsub.com)

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

class Lock_Posts {

	/**
	 * PHP4 constructor
	 *
	 */
	function Lock_Posts() {
		__construct();
	}

	/**
	 * PHP5 constructor
	 *
	 */
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'meta_box' ) );
		add_action( 'admin_menu', array( &$this, 'admin_page' ) );
		add_action( 'save_post', array( &$this, 'update' ) );
		add_action( 'init', array( &$this, 'check' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'status_output' ), 2, 2 );
		add_filter( 'manage_posts_columns', array( &$this, 'status_column' ) );
		add_action( 'manage_pages_custom_column', array( &$this, 'status_output' ), 2, 2 );
		add_filter( 'manage_pages_columns', array( &$this, 'status_column' ) );

		// load text domain
		if ( defined( 'WPMU_PLUGIN_DIR' ) && file_exists( WPMU_PLUGIN_DIR . '/lock-posts.php' ) ) {
			load_muplugin_textdomain( 'lock_posts', 'lock-posts-files/languages' );
		} else {
			load_plugin_textdomain( 'lock_posts', false, dirname( plugin_basename( __FILE__ ) ) . '/lock-posts-files/languages' );
		}
	}

	/**
	 * Add column to posts management panel
	 *
	 */
	function status_column( $columns ) {
		$columns['lock_status'] = __( 'Lock Status', 'lock_posts' );
		return $columns;
	}

	/**
	 * Display columns content on posts management panel
	 *
	 */
	function status_output( $column, $id ) {
		if( $column == 'lock_status' ) {
			$post_lock_status = get_post_meta( $id, '_post_lock_status' );

			if( is_array( $post_lock_status ) && isset( $post_lock_status[0] ) )
				$post_lock_status = $post_lock_status[0];

			if( 'locked' == $post_lock_status )
				echo __( 'Locked', 'lock_posts' );
			else
				echo __( 'Unlocked', 'lock_posts' );
		}
	}

	/**
	 * Add metabox to posts edition panel
	 *
	 */
	function meta_box() {
		if( is_super_admin() && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) {
			add_meta_box( 'postlock', __( 'Post Status', 'lock_posts' ), array( &$this, 'meta_box_output' ), 'post', 'advanced', 'high' );
			add_meta_box( 'postlock', __( 'Post Status', 'lock_posts' ), array( &$this, 'meta_box_output' ), 'page', 'advanced', 'high' );
		}
	}

	/**
	 * Post status metabox
	 *
	 */
	function meta_box_output( $post ) {
		if ( !is_super_admin() )
			return;

		$post_lock_status = get_post_meta( $post->ID, '_post_lock_status' );
		if( is_array( $post_lock_status ) && isset( $post_lock_status[0] ) )
			$post_lock_status = $post_lock_status[0];

		if( empty( $post_lock_status ) )
			$post_lock_status = 'unlocked';
		?>
		<div id="postlockstatus">
			<label class="hidden" for="excerpt">Post Status</label>
			<select name="post_lock_status">
				<option value="locked" <?php selected( $post_lock_status, 'locked' ) ?>><?php _e( 'Locked', 'lock_posts' ) ?></option>
				<option value="unlocked" <?php selected( $post_lock_status, 'unlocked' ) ?>><?php _e( 'Unlocked', 'lock_posts' ) ?></option>
			</select>
			<p><?php _e( 'Locked posts cannot be edited by anyone other than site admins.', 'lock_posts' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Update post status
	 *
	 */
	function update( $post_id ) {
		if ( !empty( $_POST['post_lock_status'] ) && is_super_admin() )
			update_post_meta( $post_id, '_post_lock_status', $_POST['post_lock_status'] );
	}

	/**
	 * Check post status and redirect if the user is not super admin and post is locked
	 *
	 */
	function check() {
		if ( !is_super_admin() && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] && !empty( $_GET['post'] ) ) {
			$post_lock_status = get_post_meta( $_GET['post'], '_post_lock_status' );

			if ( is_array($post_lock_status) )
				$post_lock_status = $post_lock_status[0];

			if ( $post_lock_status == 'locked' )
				wp_redirect( admin_url( 'edit.php?page=post-locked&post=' . $_GET['post'] ) );
		}
	}

	/**
	 * Displayed 'locked' message
	 *
	 */
	function locked() {
		$post = get_post( $_GET['post'] );
		echo '<div class="wrap">';
		echo '<h2>' . __( 'Post Locked', 'lock_posts' ) . '</h2>';
		echo '<p>' . sprintf( __( 'The post "%s" has been locked by a site administrator.', 'lock_posts' ), $post->post_title ) . '</p>';
		echo '<p><a href="' . admin_url( 'edit.php?post_type=' . $post->post_type ) . '">&laquo; ' . __( 'Back to Posts List', 'lock_posts' ) . '</a></p>';
		echo '</div>';
	}

	/**
	 * Add admin page
	 *
	 */
	function admin_page() {
		global $submenu;

		add_submenu_page( 'edit.php', 'Post Locked', 'Post Locked', 'edit_posts', 'post-locked', array( &$this, 'locked' ) );

		foreach( $submenu['edit.php'] as $key => $menu_item ) {
			if( isset( $menu_item[2] ) && $menu_item[2] == 'post-locked' )
				unset( $submenu['edit.php'][$key] );
		}
	}

}

$lock_posts = new Lock_Posts();
