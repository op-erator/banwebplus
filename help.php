<?php

require_once(dirname(__FILE__)."/resources/globals.php");
require_once(dirname(__FILE__)."/resources/common_functions.php");
require_once(dirname(__FILE__)."/pages/install/install.php");

draw();

function draw() {
	global $o_project_installer;
	global $global_path_to_jquery;

	$block_style = "display:inline-block; border:1px solid black; padding:1em; background-color:rgba(0,0,0,0.05);";

	echo "<!DOCTYPE html>
	<head><title>Banwebplus help!</title>
	<script src='{$global_path_to_jquery}'></script></head>\n
	<body><div style='width:800px; margin:0 auto;'>\n\n";
	draw_jquery($block_style);
	draw_help_code($block_style);
	$b_all_green = draw_status_code($block_style);
	draw_link_to_login($block_style, $b_all_green);
	echo "\n\n</div></body>";
}

function draw_jquery($block_style) {
	global $global_path_to_jquery;

	if ($global_path_to_jquery === "") {
		echo "<div style='{$block_style}'>
			<div style='display:inline-block; color:red; font-size:2em; font-weight:bold;'>
				ALERT!!!
			</div><br />
			This page will not work very will until jquery has been set up.<br />
			To use jquery, create a file in
			<div style='display:inline-block; font-family:monospace;'>
				/some/path/banwebplus/server_config.ini
			</div>
			with the following line:<br />
			<div style='display:inline-block; font-family:monospace;'>
				global_path_to_jquery = \"/jquery/js/jquery-1.9.0.js\"
			</div>
		</div><br /><br />\n\n";
	}
}

function draw_help_code($block_style) {
	echo "
		<script type='text/javascript'>
		// from http://stackoverflow.com/questions/3024745/cross-browser-bookmark-add-to-favorites-javascript
		function bookmark() {
			if (window.sidebar) { // Mozilla Firefox Bookmark
				window.sidebar.addPanel(location.href,document.title,'');
			} else if(window.external && window.external.AddFavorite) { // IE Favorite
				window.external.AddFavorite(location.href,document.title);
			} else if(window.opera && window.print) { // Opera Hotlist
				this.title=document.title;
				return true;
			} else {
				alert('Bookmark could not be added automatically.');
			}
		}
		</script>\n\n";

	echo "<div style='{$block_style}'>
			<div style='font-size:2.0em'>HELP!</div>
			<b>Why am I here?</b><br />
			Either you
			<a href='#' onclick='bookmark();'>bookmarked</a>
			this page or you were redirected here because some of the necessary components
			to run this page are not properly installed.
		</div><br /><br />\n\n";
}

function draw_status_code($block_style) {
	global $o_project_installer;

	$success = "<div style='display:inline-block; color:green; font-weight:bold;'>Success:</div>";
	$error = "<div style='display:inline-block; color:red; font-weight:bold;'>Error:</div>";
	$info_div = "<div style='display:none; border:1px solid black; border-radius:0.5em; padding:1.5em; margin:0.8em; 0.5em; background-color:rgba(0,0,0,0.05);'";
	$codebox = "<div style='margin:0.5em; padding:0.5em; border:1px dashed black; font-family:monospace;'>";
	$b_all_green = TRUE;

	echo "<script type='text/javascript'>
		function show_block(parentid, blockname) {
			$.each($('#' + parentid).children(), function(k,v) {
				$(v).hide(500);
			});
			$('#' + parentid).children('.'+blockname).stop(true, true);
			$('#' + parentid).children('.'+blockname).show(500);
		}
		</script>\n\n";

	echo "<div style='{$block_style}'>\n";

	if (!$o_project_installer->check_install_database()) {
		$show_block1 = "<a href='#' style='inline-block;' onclick='show_block(\"database_status\", \"";
		$show_block2 = "\");'>";
		$show_block3 = "</a>";

		echo "{$error} either
			{$show_block1}MySQL{$show_block2}MySQL{$show_block3} is not installed or
			MySQL is not 
			{$show_block1}settings{$show_block2}set up{$show_block3} properly.<br /><br />\n\n";

		echo "<div id='database_status' style='padding:0; margin:0;'>\n";
		echo "${info_div} class='MySQL'>Please make sure that MySQL is installed before anything else.<br />
			More information on installing MySQL can be found
			<a href='http://dev.mysql.com/doc/refman/5.1/en/installing.html' target='_blank'>here</a>.
			</div>\n";
		echo "${info_div} class='settings'>There is a
			<a href='#' style='inline-block;' onclick='show_block(\"ini_status\", \"mysql_config\");'>settings file</a>
			which provides the credentials to the MySQL server.
			</div>\n";
		echo "</div>\n";
		$b_all_green = FALSE;
	} else {
		echo "{$success} MySQL installed and set up correctly.<br /><br />\n";
	}

	if (!$o_project_installer->check_ini_files()) {
		$show_block1 = "<a href='#' style='inline-block;' onclick='show_block(\"ini_status\", \"";
		$show_block2 = "\");'>";
		$show_block3 = "</a>";

		echo "{$error} either the
			{$show_block1}server_config{$show_block2}server_config.ini{$show_block3}
			file cannot be read or is malformed or the
			{$show_block1}mysql_config{$show_block2}mysql_config.ini{$show_block3}
			file cannot be read or is malformed.<br /><br />\n\n";

		echo "<div id='ini_status' style='padding:0; margin:0;'>\n";
		echo "${info_div} class='server_config'>Ensure that the file
			/some/path/banwebplus/resources/server_config.ini exists, is readable
			by the Apache server, and is formatted correctly.<br />
			Example file:
			{$codebox}
				maindb = \"banwebplus\"<br />
				global_path_to_jquery = \"/jquery/js/jquery-1.9.0.js\"<br />
				timezone = \"America/Denver\"<br />
			</div>
			Where <b>maindb</b> is the mysql database name,
			<b>global_path_to_jquery</b> is the path to the jquery file, and
			<b>timezone</b> is your timezone.
			</div>\n";
		echo "${info_div} class='mysql_config'>Ensure that the file
			/some/path/banwebplus/resources/mysql_config.ini exists, is readable
			by the Apache server, and is formatted correctly.<br />
			Example file:
			{$codebox}
				host = \"localhost\"<br />
				user = \"user\"<br />
				password = \"password\"
			</div>
			Where <b>host</b> is the host of the MySQL server relative to the Apache
			server, <b>user</b> is the name of the user that has access to the
			{$show_block1}server_config{$show_block2}maindb{$show_block3} database,
			and <b>password</b> is the password for that user.<br />
			<br />
			To set up an example MySQL database and user, you can run the following commands:
			{$codebox}
				CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';<br />
				CREATE DATABASE banwebplus;<br />
				GRANT ALL ON `banwebplus`.* to 'user'@'localhost';
			</div>
			More information on using MySQL can be found
			<a href='http://www.mysqltutorial.org/basic-mysql-tutorial.aspx' target='_blank'>here</a>.
			</div>\n";
		echo "</div>\n";
		$b_all_green = FALSE;
	} else {
		echo "{$success} server_config.ini and mysql_config.ini formatted correctly.<br /><br />\n";
	}

	if (!$o_project_installer->check_create_users()) {
		$show_block1 = "<a href='#' style='inline-block;' onclick='show_block(\"user_status\", \"";
		$show_block2 = "\");'>";
		$show_block3 = "</a>";

		echo "{$error} no
			{$show_block1}users{$show_block2}users{$show_block3} exist in database.<br /><br />\n\n";

		$root_url = dirname(curPageURL()) . "/resources/export_database_structure.php?action=";
		$step1 = $root_url . "load";
		$step2 = $root_url . "load_common_data";
		$step3 = $root_url . "initialize_user_data";
		echo "<div id='user_status' style='padding:0; margin:0;'>\n";
		echo "${info_div} class='users'>Run these steps, in order:
			<ol>
				<li><a href='{$step1}' target='_blank'>{$step1}</a>
				<li><a href='{$step2}' target='_blank'>{$step2}</a>
				<li><a href='{$step3}' target='_blank'>{$step3}</a>
			</ol></div>\n";
		echo "</div>\n";
		$b_all_green = FALSE;
	} else {
		echo "{$success} users exist.<br /><br />\n";
	}

	if (!$o_project_installer->check_classes_availability()) {
		$show_block1 = "<a href='#' style='inline-block;' onclick='show_block(\"classes\", \"";
		$show_block2 = "\");'>";
		$show_block3 = "</a>";

		echo "{$error}
			{$show_block1}data{$show_block2}class data{$show_block3} is not available.<br /><br />\n\n";

		$monospace = "<div style='display:inline-block; font-family:monospace;'>";
		$root_dir = dirname(__FILE__) . "/scraping/";
		$terms_fname = $root_dir . "banweb_terms.py";
		$banweb_fname = $root_dir . "banweb.py";
		$nmt_fname = $root_dir . "new_mexico_tech_banweb.py";
		$translate_fname = $root_dir . "php_to_mysql.php";
		echo "<div id='classes' style='padding:0; margin:0;'>\n";
		echo "${info_div} class='data'>Run these steps, in order:
			<ol>
				<li>Create the file	{$monospace}{$terms_fname}</div> with the following line:<br />\n
					{$codebox}terms = []</div>
				<li>Create a symbolic link to the version of the scraping file necessary for
					gather class data about your school. For example:
					{$codebox}ln -s {$nmt_fname} {$banweb_fname} 
				<li>Run the file {$monospace}{$banweb_fname}</div> with python 2.x.<br />\n
					{$codebox}python2.7 {$banweb_fname} --path {$root_dir}</div>
				<li>Run the file {$monospace}{$translate_fname}</div> with php.<br />\n
					{$codebox}php {$translate_fname}</div>
			</ol></div>\n";
		echo "</div>\n";
		$b_all_green = FALSE;
	} else {
		echo "{$success} class data available.<br />\n";
	}

	echo "\n</div><br /><br />\n\n";

	return $b_all_green;
}

function draw_link_to_login($block_style, $b_all_green) {
	$root_url = dirname(curPageURL()) . "/";
	$login = $root_url . "index.php";
	if ($b_all_green) {
		echo "<div style='{$block_style}'>
				You're all set! Go to the
				<a href='{$login}'>login page</a>
				to start using Banwebplus!
			</div><br /><br />\n\n";
	}
}

?>