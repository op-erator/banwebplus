<?php

require_once(dirname(__FILE__)."/../resources/globals.php");

function init_schedule() {
	global $global_user;

	$retval = '
    <table class=\'table_title\'><tr><td>
    <div class=\'centered\'>Add By CRN</div>
</td></tr></table>
<div id=\'schedule_tab_add_by_crn\' class=\'centered\'>&nbsp;</div><br />
<table class=\'table_title\'><tr><td>
    <div class=\'centered\'>Selected Classes</div>
</td></tr></table>
<div id=\'schedule_tab_user_schedule\' class=\'centered\'>&nbsp;</div><br /><br />
<table class=\'table_title\'><tr><td>
    <div class=\'centered\'>Recently Selected</div>
</td></tr></table>
<div id=\'schedule_tab_user_recently_viewed_schedule\' class=\'centered\'>&nbsp;</div><br /><br />
<input type=\'button\' style=\'display:none;\' name=\'onselect\' />';

	if ($global_user->has_access("development")) {
			$retval .= '
<table class=\'table_title\'><tr><td>
    <div class=\'centered\'>Share Schedule</div>
</td></tr></table>
<div id=\'schedule_tab_share_schedule\' class=\'centered\'>&nbsp;</div><br /><br />';
	}

	return $retval;
}

$tab_init_function = 'init_schedule';

?>
