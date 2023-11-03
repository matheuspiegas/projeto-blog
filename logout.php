<?php 
session_start();
require 'connection.php';
if (isset($_GET['sair'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
} 
?>