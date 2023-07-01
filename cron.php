<?php
	if(!isset($_SERVER['DOCUMENT_ROOT']))
	{
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
	}

	require_once($_SERVER['DOCUMENT_ROOT']."/cron/send_email.php");
?>