<?php
session_start();
require 'connection.php';
if (isset($_SESSION['userId'])) {
  $data = json_decode(file_get_contents('php://input'), true);
  if ($data['confirmado'] === true) {
    $sql = 'delete from posts where posts.id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $data['id']);
    $stmt->execute();
    echo json_encode(['apagado' => true]);
    exit();
  }
} else {
  header('Location: login.php');
}
