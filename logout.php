<?php
include 'includes/sections/header.php';
session_destroy();
header('location:login.php');
exit();
