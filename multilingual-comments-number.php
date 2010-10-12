<?php
/*
Plugin Name: Multilingual Comments Number
Plugin URI: http://www.simplelib.com/?p=128
Description: Adds correct multilingual comments numbering to wordpress blog. Visit <a href="http://www.simplelib.com/">SimpleLib blog</a> for more details.
Version: 2.0.13
Author: minimus
Author URI: http://blogcoding.ru
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
		var $mcnInitOptions;
		function __construct() {
			//load language
			$plugin_dir = basename( dirname( __FILE__ ) );
			if ( function_exists( 'load_plugin_textdomain' ) ) 
				load_plugin_textdomain( 'multilingual-comments-number', false, $plugin_dir );
			
			$this->mcnInitOptions = array('commentStringZero' => __( 'There are no comments', 'multilingual-comments-number' ),
																		'commentStringOne' => __( 'One Comment', 'multilingual-comments-number' ));
			
			//Actions and Filters
			add_action('admin_init', array(&$this, 'initSettings'));
			add_action('activate_multilingual-comments-number/multilingual-comments-number.php',  array(&$this, 'onActivate'));
			add_action('deactivate_multilingual-comments-number/multilingual-comments-number.php',  array(&$this, 'onDeactivate'));
			add_filter( 'comments_number', array( &$this, 'commentsNumber' ), 8, 2);
		}
		
		function onActivate() {
			$mcnAdminOptions = $this->getOptions();
			update_option('mcnZeroString', $mcnAdminOptions['commentStringZero']);
			update_option('mcnOneString', $mcnAdminOptions['commentStringOne']);
		}
		
		function onDeactivate() {
			delete_option('mcnZeroString');
			delete_option('mcnOneString');
		}
		
		function getOptions() {
			$zeroString = get_option('mcnZeroString', '');
			$oneString = get_option('mcnOneString', '');
			
			$mcnAdminOptions = $this->mcnInitOptions;
			if ($zeroString !== '') $mcnAdminOptions['commentStringZero'] = $zeroString;
			if ($oneString !== '') $mcnAdminOptions['commentStringOne'] = $oneString;
			
			if (get_option($this->adminOptionsName, false)) $mcnAdminOptions = $this->updateOldOptions();
			
			return $mcnAdminOptions;
		}
		
		function updateOldOptions() {
			$options = get_option($this->adminOptionsName, '');
			delete_option($this->adminOptionsName);
			return $options;
		}
		
		function initSettings() {
			add_settings_section("mcn_section", __("Comments Numbering", 'multilingual-comments-number'), array(&$this, "drawSection"), "discussion");
			add_settings_field('mcnZeroString', __("Define empty comments string", "multilingual-comments-number"), array(&$this, 'drawZeroSetting'), 'discussion', 'mcn_section');
			add_settings_field('mcnOneString', __("Define one comment string", "multilingual-comments-number"), array(&$this, 'drawOneSetting'), 'discussion', 'mcn_section');
			register_setting('discussion','mcnZeroString');
			register_setting('discussion','mcnOneString');
		}
		
		function drawSection() {
			echo __('These are settings of Multilingual Comments Number plugin. Here you can define strings for one comment and absence of comments.', 'multilingual-comments-number');
		}
		
		function drawZeroSetting() {
			$option = get_option('mcnZeroString');
			echo "<input type='text' class='regular-text' style='height: 22px; font-size: 11px; margin: 5px;' name='mcnZeroString' id='mcnZeroString' value='{$option}' />".
						"<span class='description'>".__("This is phrase for posts without comments.", 'multilingual-comments-number')."</span>";
		}
		
		function drawOneSetting() {
			$option = get_option('mcnOneString');
			echo "<input type='text' class='regular-text' style='height: 22px; font-size: 11px; margin: 5px;' name='mcnOneString' id='mcnOneString' value='{$option}' />".
						"<span class='description'>".__("This is phrase for posts with one comment.", 'multilingual-comments-number')."</span>";
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