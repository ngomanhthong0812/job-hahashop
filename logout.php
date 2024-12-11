<?php
include('config/init.php');
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
header('location: index.php');
