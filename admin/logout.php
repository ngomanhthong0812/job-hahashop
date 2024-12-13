<?php
include('./config/init.php');
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['login_message']);
header('location: ./login.php');
