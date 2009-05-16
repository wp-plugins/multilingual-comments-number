=== multilingual-comments-number ===
Contributors: minimus
Donate link: http://simplelib.co.cc/
Tags: comments, comments_number
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 0.2.6

This plugin corrects output of comments_number for languages having more than one form of plurals (All Slavic languages for example).

== Description ==

The output strings of standard Wordpress function comments-number can not be correct for languages having more than one form of plurals (All Slavic languages for example). __multilingual-comments-number__ plugin corrects this problem.

Available languages:

  * English (of course)
  * Russian
  * German
  * Polish
  * Belorussian
  * Ukrainian

If you have created your own language pack, or have an update of an existing one, you can send __.po__ and __.mo files__ to me so that I can bundle it into __multilingual-comments-number__.

= Version History =

* 0.2.6
	* Initial upload
  
More info you can see on the [plagin page](http://simplelib.co.cc/?p=128)

== Installation ==

1. Upload plugin dir to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That is all.

== Frequently Asked Questions ==

= What about "numeric only" strings? =

"Numeric only" strings are not filtering by __miltilingual-comments-number__ and will be send to result document AS IS.

= What about HTML tagged strings? =

All HTML tags are saved. Only text will be translated. 

For example: __comments-number__ send to result page this code `<a href="ext-comments-editor.php">21 comments</a>`

resulting string after __multilingual-comments-number__ filtering for ru_RU: `<a href="ext-comments-editor.php">21 �����������</a>`

== Screenshots ==

1. Outputs of comments_number are correct not always
2. Tags and numeric only strings
3. Plurals