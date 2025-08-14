<?
session_start(); 
if (!isset($_SESSION['user_is_logged_in']) || $_SESSION['user_is_logged_in'] !== true) { 
    header('Location: login.php'); 
    exit; 
} ?>