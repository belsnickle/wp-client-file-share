<?php
/*
Plugin Name: WP Client File Share
Plugin URI: http://sideways8.com/plugins/wp-client-file-share
Description: Share files between Admins and clients (users).  Users receive their "private" page to upload, and Admins can post files for the client to download.
Author: Aaron Reimann & Adam Walker
Version: 1.1.0
Author URI: http://www.sideways8.com
License: GPL3

Copyright 2011 Sideways 8 Interactive

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

register_activation_hook(__FILE__,"s8_wpcfs_activate");
register_deactivation_hook(__FILE__,"s8_wpcfs_deactivate");

##########
## Adding style sheet for front end
function s8_wpcfs_add_css() {
	if (!is_admin()) {
		wp_enqueue_style('wpf_css', plugins_url('/style.css', __FILE__) );
	}
}
add_action('wp_print_styles', 's8_wpcfs_add_css');
##
##########

##########
## Initializing vars to save
function s8_wpcfs_init()
{
	register_setting('s8_wpcfs_options', 's8_wpcfs_use_download_protect');
}
add_action('admin_init', 's8_wpcfs_init');
##
##########

##########
## random functions, probably will contain more as this grows, then * will rename this
function s8_wpcfs_check_dlprotect()
{
	if ( 
		(get_option('s8_wpcfs_use_download_protect') == true) &&
		(function_exists('dlprotect_func')) 
		) {
		return true;
	}
}
##
##########

##########
## ADMIN
function s8_wpcfs_get_users_with_role($role)
{
	$wp_user_search = new WP_User_Search($usersearch, $userspage, $role);
	return $wp_user_search->get_results();
}

function s8_wpcfs_option_page()
{
?>
	<div class="wrap"><?php screen_icon(); ?>
	<h2>WP Client File Share Option Page</h2>
	<p>Welcome to the WP Client File Share Plugin.  This plugin is at version 1.1.0.</p>
	<form action="options.php" method="post" id="s8-wpcfs-options-form">
		<?php settings_fields('s8_wpcfs_options'); ?>
		<h4><label for="s8-wpcfs-options-use-download-protect">Do you want to use Download Protect?</label>
		<input type="checkbox" id="s8_wpcfs_use_download_protect" 
			name="s8_wpcfs_use_download_protect" value="true" 
			<?php if (get_option('s8_wpcfs_use_download_protect') == TRUE) { print 'checked="yes"'; } ?>
			<?php if (!function_exists('dlprotect_func')) { print " disabled "; } ?>/>
		</h4>
			<?php if (function_exists('dlprotect_func')) { ?>
				<p>
				By enabling this we assume you have read how to use Download Protect.  Make sure that your "Protected Download Directory" in "Download Protect" settings match WordPress's Settings -> Media -> Uploading Files, and that "Organize my uploads into month- and year-based folders" is not checked.
				</p>
			<?php } else { ?>
				<p>
				To use this feature you must have the <a href="http://wordpress.org/extend/plugins/download-protect/">Download Protect</a> plugin installed.
				</p>
			<?php } ?>
		<p><input type="submit" name="submit" value="Update Settings" /></p>
	</form>
	<?php
	$editors = s8_wpcfs_get_users_with_role('file_sharer');

	if ($editors)
	{
		echo '<ul>';
		foreach($editors as $editor)
		{
			$s8_wpcfs_main_page_id = get_option('WP_Client_File_Share');
			$user_info = get_userdata($editor);
			$user_page_title = $user_info->user_login . '\'s File Share Page';
			$page = get_page_by_title($user_page_title);
			?>
	
			<?php
			echo '<li>';
			echo $user_info->user_login, 
				' / <a href="', $page->guid, '">View Page</a>',
				' / <a href="mailto:', $user_info->user_email, '">Email User</a>';
			echo '</li>';
		}
		echo '</ul>';
	}
	else
	{
		echo '<p>No "File Sharers" found.  To start sharing you need to create a new user with the role "File Sharer".';
	}
	?>
	</div>
<?php
}

function s8_wpcfs_plugin_menu()
{
	add_menu_page('WP Client File Share', 'WP Client File Share', 'manage_options', 's8_client_file_share_plugin', 's8_wpcfs_option_page');
}
add_action('admin_menu', 's8_wpcfs_plugin_menu');
##
##########

##########
## Activation Code
function s8_wpcfs_activate() 
{
	add_role('file_sharer', 'File Sharer', array(
		'delete_posts'				=>	false,
		'delete_published_posts'	=>	false,
		'edit_posts'				=>	false,
		'edit_published_posts'		=>	true,
		'publish_posts'				=>	true,
		'read'						=>	true,
		'upload_files'				=>	false,
		)
	);
	s8_wpcfs_create_file_share_page();
}

function s8_wpcfs_create_file_share_page()
{
	if (!get_page_by_title('WP Client File Share'))
	{
		$post = array(
			'post_content'	=> '',
			'post_name'		=>  'WP Client File Share',
			'post_status' 	=> 'private',
			'post_title' 	=> 'WP Client File Share',
			'post_type' 	=> 'page',
			'post_parent' 	=> 0,
			'menu_order' 	=> 0,
		);
		wp_insert_post($post);
	}
	$page = get_page_by_title('WP Client File Share');
	update_option('WP_Client_File_Share', $page->ID);
}
##
##########

##########
## User creation code
do_action('user_register', $user_id);
function s8_wpcfs_create_user_page($user_id)
{
	$user_info = get_userdata($user_id);

	if ($user_info->wp_capabilities['file_sharer'] == TRUE)
	{
		$s8_wpcfs_main_page_id = get_option('WP_Client_File_Share');

		$title = $user_info->user_login . '\'s File Share Page';

		$post = array(
		  'post_author' => $user_info->ID,
		  'post_content' => '',
		  'post_name' =>  $title,
		  'post_status' => 'private',
		  'post_title' => $title,
		  'post_type' => 'page',
		  'post_parent' => $s8_wpcfs_main_page_id,
		  'menu_order' => 0,
		  'to_ping' =>  '',
		  'pinged' => '',
		);
		wp_insert_post($post);
	}
}
add_action ('user_register', "s8_wpcfs_create_user_page");
##
##########

##########
## Putting it all together on the front end here
function insert_attachment($file_handler,$post_id,$setthumb='false') {
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$attach_id = media_handle_upload($file_handler, $post_id);

	wp_update_post( array(
	  'ID' => $attach_id,
	  'post_title' => $_POST['title'],
	  'post_content' => $_POST['description'] )
	);
	
	return $attach_id;
}

function s8_wpcfs_show_form()
{
	echo '<div id="s3-wpcfs-form">';
	echo '<form method="POST" action="" enctype="multipart/form-data">';
		echo '<fieldset>';
		echo '<legend>Upload a File</legend>';
			echo '<label for="title">Title</label>';
			echo '<input type="text" name="title" id="title">';
			echo '<br />';
			echo '<label for="title">Description</label>';
			echo '<textarea name="description" id="description"></textarea>';
			echo '<br />';
			echo '<label for="uploaded_attachment">Attachment</label>';
			echo '<input type="file" name="uploaded_attachment" id="uploaded_attachment">';
			echo '<br />';
			echo '<input type="submit" value="Upload" id="submit" name="submit" />';
		echo '</fieldset>';	
		wp_nonce_field('client-file-upload', 'client-file-upload');
	echo '</form>';
	echo '</div>';
	echo '<div id="s3-wpcfs-form-clear"></div>';
}

function s8_wpcfs_show_files($post)
{
	$args = array(
		'post_type'		=> 'attachment',
		'numberposts'	=> null,
		'post_status'	=> null,
		'post_parent'	=> $post->ID
	);
	$attachments = get_posts($args);

	if ($attachments) {
		foreach ($attachments as $attachment) {
			echo '<div id="s8-wpcfs-attachment-'.$attachment->ID.'" class="s8-wpcfs-attachment">';
				
				echo '<h2>';
				echo apply_filters('the_title', $attachment->post_title);
				echo '</h2>';
				
				echo '<div>';
				echo '<p>';
				echo apply_filters('post_content', $attachment->post_content);
				echo '</p>';

				if (s8_wpcfs_check_dlprotect()) {
					$file = basename($attachment->guid);
					print '<p>[dlprotect file="'.$file.'"]Download[/dlprotect]</p>';
				} else {
					echo '<p><a href="'.$attachment->guid.'" class="forced-download">Download</a></p>';					
				}

				echo '<p>';
					$attach_auth = get_userdata($attachment->post_author);
					echo 'Author: '.$attach_auth->user_login;
				 	echo ' / Posted: '.$attachment->post_date;
				echo '</p>';
				echo '</div>';
			echo '</div>';
	    }
	}
}

function append_to_post($content)
{
    global $post;

	if (!is_admin())
	{
		$s8_wpcfs_main_page_id = get_option('WP_Client_File_Share');

		if ($post->post_parent == $s8_wpcfs_main_page_id)
		{
			if ($_FILES)
			{
				foreach ($_FILES as $file => $array)
				{
					$newupload = insert_attachment('uploaded_attachment',$post->ID);
				}
			}
			ob_start();
            s8_wpcfs_show_form();
			s8_wpcfs_show_files($post);
			$content = $content . ob_get_clean();
		}
	}
    return $content;
}
add_filter('the_content', 'append_to_post', 10, 3);
##
##########

##########
## Hiding things for the Role "file_sharer"
function s8_wpcfs_redirect_to_url() {
	if (current_user_can('file_sharer'))
	{
		$s8_wpcfs_main_page_id = get_option('WP_Client_File_Share');
		$user_id = get_current_user_id();

		$wp_query = new WP_Query();
		$all_pages = $wp_query->query(array('post_type' => 'page'));
		$pages = get_page_children($s8_wpcfs_main_page_id, $all_pages);

		foreach ($pages as $page) // refactor in non-beta
		{
			if ($page->post_author == $user_id) { $url = $page->guid; }
			else { $url = site_url(); }
		}

		wp_redirect($url);
		exit();
	}
}
add_action('admin_menu', 's8_wpcfs_redirect_to_url');

function s8_wpcfs_hide_admin_bar()
{
	if (is_user_logged_in()) {
		if (current_user_can('file_sharer'))
	 		return FALSE;
	 	else
	 		return TRUE;
	}
}
add_filter('show_admin_bar', 's8_wpcfs_hide_admin_bar');
##
##########

##########
## Deactivation Code
function s8_wpcfs_deactivate() 
{
	$s8_wpcfs_main_page_id = get_option('WP_Client_File_Share'); // getting this from options
	$s8_wpcfs_main_page = array(); // creating the array and re-Titling the plug-in's main page
	$s8_wpcfs_main_page['ID'] = $s8_wpcfs_main_page_id;
	$s8_wpcfs_main_page['post_title'] = 'WP Client File Share - Deactivated: ' . date("Y-m-d H:i:s");
	wp_update_post($s8_wpcfs_main_page);
	error_log("WP Client File Share deactivated");
}
##
##########
