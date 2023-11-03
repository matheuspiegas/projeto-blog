<?php
session_start();
require 'connection.php';
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="home.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="criarpost.php">Novo post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.php">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php?sair">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container border-start border-end">
        <div class="container feedContainer mt-4">
            <?php
            $sqlSelect = 'SELECT usuarios.nome, posts.titulo, posts.content, posts.data_post, posts.id, usuarios.foto FROM usuarios INNER JOIN posts ON usuarios.id = posts.user_id ORDER BY data_post DESC';
            $stmt = $conn->prepare($sqlSelect);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_object()) {
                    $data = $row->data_post;
                    $timestamp = strtotime($data);
                    $dataFormatada = date('d/m/Y', $timestamp);
                    echo '<div class="card mb-3">';
                    echo '<div class = "card-header">';
                    echo '<div class="d-flex align-items-center">';
                    if(empty($row->foto)){
                        $imgDefault = '<img src="uploads/default_user.png" alt="" width="50" height="50" class="rounded"></img>';
                    } else {
                        $imgDefault = '<img src="'. $row->foto . '" width="50" height="50" style="border-radius: 50%;"></img>';
                    }
                    echo $imgDefault;
                    echo '<h6 class="card-title mx-1">' . $row->nome . '</h6>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<h4>' . $row->titulo . '</h4>';
                    echo '<p class="lead">' . $row->content . '</p>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<small class="mb-0" style="color: #6c757d;">' . $dataFormatada . '</small>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>