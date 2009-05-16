<?php
/*
Plugin Name: multilingual-comments-number
Plugin URI: http://simplelib.co.cc/?p=128
Description: Adds correct multilingual comments numbering to wordpress blog. Visit <a href="http://simplelib.co.cc/">SimpleLib blog</a> for more details.
Version: 0.2.6
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
		function __construct() {
			//load language
			$plugin_dir = basename( dirname( __FILE__ ) );
			if ( function_exists( 'load_plugin_textdomain' ) ) 
				load_plugin_textdomain( 'multilingual-comments-number', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );
			
			//Actions and Filters
			add_filter( 'comments_number', array(&$this, 'commentsNumber'), 8, 2);
		}
		
		function getText( $htmlString ) {
			$search = array ("'<script[^>]*?>.*?</script>'si",  
											 "'<[\/\!]*?[^<>]*?>'si", 
                 			 "'([\r\n])[\s]+'",
                 			 "'&(quot|#34);'i",
                 			 "'&(amp|#38);'i",
                 			 "'&(lt|#60);'i",
                 			 "'&(gt|#62);'i",
                 			 "'&(nbsp|#160);'i",
                 			 "'&(iexcl|#161);'i",
                 			 "'&(cent|#162);'i",
                 			 "'&(pound|#163);'i",
                 			 "'&(copy|#169);'i",
                 			 "'&#(\d+);'e");

			$replace = array ("",
      			            "",
            			      "\\1",
                  			"\"",
                  			"&",
                  			"<",
                  			">",
                  			" ",
                  			chr(161),
                  			chr(162),
                  			chr(163),
                  			chr(169),
                  			"chr(\\1)");
			return preg_replace( $search, $replace, $htmlString );
		}
		
		function commentsNumber( $output, $number ) {
			$text = $this->getText( $output );
			$filterNeeded = (strlen( $text ) != strspn( $text, "1234567890" )); 
			
			if ( true === $filterNeeded ) {
				switch ($number) {
					case 0: $mcnOutput = str_replace( $text, __( 'No Comments', 'multilingual-comments-number' ), $output ); 
					break;
				
					case 1: $mcnOutput = str_replace( $text, __( 'One Comment', 'multilingual-comments-number' ), $output );
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