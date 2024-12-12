<?php
include('config/init.php');
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
unset($_SESSION['cart']);
unset($_SESSION['login_message']);
unset($_SESSION['update_info_message']);
header('location: index.php');
