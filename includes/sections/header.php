<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="Shortcut Icon" type="image/ico" href="images/logo.ico">

    <style media="screen">
    .content-section {
        background: #ffffff;
        padding: 10px 20px;
        border: 1px solid #dddddd;
        border-radius: 3px;
        margin-bottom: 20px;
        }
    a{
      color: #fff;
    }
    body {
          background: #fafafa;
          color: #333333;
        }
    hr.style1{
    	border-top: 1px solid #8c8b8b;
    }
    </style>

    <title>Daila Herbals</title>
    <?php include 'includes/plugins.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        date_default_timezone_set('Asia/Manila');
    }
    ?>
  </head>
  <body>
