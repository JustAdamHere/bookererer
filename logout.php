<?php
  $redirect_page = isset($_GET["redirect_page"])?urldecode($_GET["redirect_page"]):"/login.php";

  require_once($_SERVER['DOCUMENT_ROOT']."/includes/functions.php");

  do_logout();

  header("Location: ".$config["base_url"].$redirect_page);
?>