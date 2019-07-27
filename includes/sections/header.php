<?php

  include 'includes/plugins.php';
  include 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    date_default_timezone_set('Asia/Manila');
}
?>


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
    body {
          background: #fff;
          color: #333333;
        }
    hr.style1{
    	border-top: 1px solid #8c8b8b;
    }

    /* no am pm  */
    .without_ampm::-webkit-datetime-edit-ampm-field {
     display: none;
   }
   input[type=time]::-webkit-clear-button {
     -webkit-appearance: none;
     -moz-appearance: none;
     -o-appearance: none;
     -ms-appearance:none;
     appearance: none;
     margin: -10px;
   }

   /* Hide HTML5 Up and Down arrows. */
    input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    </style>

    <title>Daila Herbals</title>

  </head>
  <body>
