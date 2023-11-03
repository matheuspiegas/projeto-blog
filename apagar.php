<?php
session_start();
 require 'connection.php';
 if($_SESSION['autenticado'] == true){
    $sql = 'delete from posts where posts.id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    header('Location: perfil.php');
 }  else {
    header('Location: login.php');
 }