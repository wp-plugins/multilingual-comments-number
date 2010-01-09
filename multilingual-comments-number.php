<?php
/*
Plugin Name: Multilingual Comments Number
Plugin URI: http://simplelib.co.cc/?p=128
Description: Adds correct multilingual comments numbering to wordpress blog. Visit <a href="http://simplelib.co.cc/">SimpleLib blog</a> for more details.
Version: 1.0.10
Author: minimus
Author URI: http://blogovod.co.cc
*/

/*  Copyright 2009, minimus  (email : minimus.blogovod@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('MultilingualCommentsNumber')) {
	class MultilingualCommentsNumber {
		var $adminOptionsName = "MultilingualCommentsNumberAdminOptions";
		var $mcnInitOptions = array('commentStringZero' => '', 'commentStringOne' => '');
		function MultilingualCommentsNumber() {
			//load language
			$plugin_dir = basename( dirname( __FILE__ ) );
			if ( function_exists( 'load_plugin_textdomain' ) ) 
				load_plugin_textdomain( 'multilingual-comments-number', PLUGINDIR . $plugin_dir, $plugin_dir );
			
			//Actions and Filters
			add_action('admin_menu', array(&$this, 'onAdminPage'));
			add_action('activate_multilingual-comments-number/multilingual-comments-number.php',  array(&$this, 'onActivate'));
			add_action('deactivate_multilingual-comments-number/multilingual-comments-number.php',  array(&$this, 'onDeactivate'));
			add_filter( 'comments_number', array( &$this, 'commentsNumber' ), 8, 2);
		}
		
		function onActivate() {
			$mcnAdminOptions = $this->getOptions();
			update_option($this->adminOptionsName, $mcnAdminOptions);
		}
		
		function onDeactivate() {
			delete_option($this->adminOptionsName);
		}
		
		function getOptions() {
			$mcnAdminOptions = $this->mcnInitOptions;
			$mcnOptions = get_option($this->adminOptionsName);
			if (!empty($mcnOptions)) {
				foreach ($mcnOptions as $key => $option) {
					$mcnAdminOptions[$key] = $option;
				}
			}
			if($mcnAdminOptions['commentStringZero'] === '')
				$mcnAdminOptions['commentStringZero'] = __( 'There are no comments', 'multilingual-comments-number' );
			if($mcnAdminOptions['commentStringOne'] === '')
				$mcnAdminOptions['commentStringOne'] = __( 'One Comment', 'multilingual-comments-number' );
			return $mcnAdminOptions;
		}
		
		function onAdminPage() {
			if (function_exists('add_options_page')) {
				$plugin_page = add_options_page(__('Comments Numbering', 'multilingual-comments-number'), __('Comments Numbering', 'multilingual-comments-number'), 8, basename(__FILE__), array(&$this, 'printAdminPage'));
				/*add_action('admin_print_styles-'.$plugin_page, array(&$this, 'adminHeaderStyles'));
				add_action('admin_print_scripts-'.$plugin_page, array(&$this, 'adminHeaderScripts'));*/
			}
		}
		
		function printAdminPage() {
			$mcnOptions = $this->getOptions();
			$options = array(
				array(	
					"name" => __('General Settings', 'multilingual-comments-number'),
					//"desc" => __('You must define IDs for both accounts, FeedBurner and Twitter, for properly work of plugin.', 'multilingual-comments-number'),
					"disp" => "startSection" ),
					
				array(	
					"name" => __("Define empty comments string", "multilingual-comments-number"),
					"desc" => __("This is phrase for posts without comments.", 'multilingual-comments-number'),
					"id" => "commentStringZero",
					"disp" => "text",
					"textLength" => '250'),
					
				array(	
					"name" => __("Define one comment string", "multilingual-comments-number"),
					"desc" => __("This is phrase for posts with one comment.", 'multilingual-comments-number'),
					"id" => "commentStringOne",
					"disp" => "text",
					"textLength" => '250'),
					
				array(
					"disp" => "endSection" )
			);
			
			if (isset($_POST['update_multilingualCommentsNumberSettings'])) {
				foreach ($options as $value) {
					if (isset($_POST[$value['id']])) 
						$mcnOptions[$value['id']] = $_POST[$value['id']];
				}
				update_option($this->adminOptionsName, $mcnOptions);
				?>
<div class="updated"><p><strong><?php _e("Multilingual Comments Number Settings Updated.", "multilingual-comments-number");?></strong></p></div>        
				<?php
			}
			 ?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<div id="icon-options-general" class="icon32"></div>
<h2><?php _e("Multilingual Comments Number Settings", "multilingual-comments-number"); ?></h2>

			<?php foreach ($options as $value) {
				switch ( $value['disp'] ) {
					case 'startSection':
						?>
<div id="poststuff" class="ui-sortable">
<div class="postbox opened">
<h3><?php echo $value['name']; ?></h3>
	<div class="inside">
						<?php
						if (!is_null($value['desc'])) echo '<p>'.$value['desc'].'</p>';
						break;
						
					case 'endSection':
						?>
</div>
</div>
</div>
						<?php
						break;
						
					case 'text':
						if ( is_null($value['textLength']) ) $textLengs = '55';
						else $textLengs = $value['textLength'];
						?>
<p><strong><?php echo $value['name']; ?></strong>
<br/><?php echo $value['desc']; ?></p>
<p><input type="text" style="height: 22px; font-size: 11px; width: <?php echo $textLengs;?>px" value="<?php echo $mcnOptions[$value['id']] ?>" name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" /></p>
						<?php
						break;
						
					case 'radio':
						?>
<p><strong><?php echo $value['name']; ?></strong>
<br/><?php echo $value['desc']; ?></p><p>				
						<?php
						foreach ($value['options'] as $key => $option) { ?>
<label for="<?php echo $value['id'].'_'.$key; ?>"><input type="radio" id="<?php echo $value['id'].'_'.$key; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php if ($mcnOptions[$value['id']] == $key) { echo 'checked="checked"'; }?> /> <?php echo $option;?></label>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php }
						?>
</p>			
						<?php 
						break;
						
					case 'select':
						?>
<p><strong><?php echo $value['name']; ?></strong>
<br/><?php echo $value['desc']; ?></p>
<p><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
						<?php foreach ($value['options'] as $option) { ?>
<option value="<?php echo $option; ?>" <?php if ( $mcnOptions[$value['id']] == $option) { echo ' selected="selected"'; }?> ><?php echo $option; ?></option>
						<?php } ?>
</select></p>
						<?php
						break;
					
					default:
						
						break;
				}
			}
			?>

<div class="submit">
	<input type="submit" class='button-primary' name="update_multilingualCommentsNumberSettings" value="<?php _e('Update Settings', 'multilingual-comments-number') ?>" />
</div>
</form>
</div>      
      <?php
		}
				
		function commentsNumber( $output, $number ) {
			$mcnOptions = $this->getOptions();
			$text = strip_tags( $output );
			$filterNeeded = !ctype_digit( $text ); 
			
			if ( $filterNeeded ) {
				switch ( $number ) {
					case 0: $mcnOutput = str_replace( $text, $mcnOptions['commentStringZero'], $output ); 
					break;
				
					case 1: $mcnOutput = str_replace( $text, $mcnOptions['commentStringOne'], $output );
					break;
				
					default: $mcnOutput = str_replace( $text, sprintf( __ngettext( "%d Comment", "%d Comments", $number, "multilingual-comments-number" ), $number ), $output );
					break;
				}			
				echo $mcnOutput;
			} else echo $output;
		}
	} // End of class MultilingualCommentsNumber
} // End of If

if (class_exists('MultilingualCommentsNumber')) $minimus_comments_number = new MultilingualCommentsNumber();

?>