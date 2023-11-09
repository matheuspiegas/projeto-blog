<?php
session_start();
 require 'connection.php';
 if(isset($_SESSION['userId'])){
    $sql = 'delete from posts where posts.id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['idpost']);
    $stmt->execute();
    header("Location: perfil.php?id={$_SESSION['userId']}");
 }  else {
   header('Location: login.php');
 }