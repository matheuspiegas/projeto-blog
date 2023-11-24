<?php
 require 'connection.php';
 session_start();
 if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['autenticado'] == true) {
    $comentario = $_POST['comentario'];
    $postId = $_POST['postId'];
    if(!empty($comentario)){
        $sql = 'INSERT INTO comentarios(user_id, comentario, post_id) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isi', $_SESSION['userId'], $comentario, $postId);
        if($stmt->execute()) {
            header('Location: home.php');
        }
    } else {
        header('Location: home.php');
    }
 } else {
    header('Location: login.php');
 }