<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/kernel.php"); ?>
<?php $db_connection = db_connect(); ?>

<?php
  if (login_valid())
  {
    ?>
    <!doctype html>
    <html lang="en">
      <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/includes/head.php"); ?>
        <meta name="robots" content="noindex,nofollow">
        <title><?=$title;?></title>
      </head>
    </html>
    <?php
  }
  else
  {
    output_restricted_page();
  }
?>

<?php db_disconnect($db_connection); ?>