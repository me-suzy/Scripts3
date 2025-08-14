<?
session_start(); 
if (isset($_SESSION['admin_is_logged_in'])) { 
    unset($_SESSION['admin_is_logged_in']); 
} 
header('Location: login.php'); 
?> 