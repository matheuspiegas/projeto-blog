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
                            <a class="nav-link" aria-current="page" href="criarpost.php"><i class="fa-solid fa-plus mx-2"></i>Novo post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="perfil.php?id=<?php echo $_SESSION['userId']; ?>"><i class="fa-solid fa-user mx-2"></i>Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php?sair"><i class="fa-solid fa-arrow-right-from-bracket mx-2"></i>Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container border-start border-end main border-bottom">
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
                    $_SESSION['post_id'] = $row->id;
                    ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <?php if (empty($row->foto)) {
                                    $imgDefault = '<img src="uploads/default_user.png" alt="" width="50" height="50" class="rounded"></img>';
                                } else {
                                    $imgDefault = '<img src="' . $row->foto . '" class="imagem-perfil-redonda"></img>';
                                } ?>
                                <div class="rounded-circle overflow-hidden" style="width:50px; height:50px;"> <?php echo $imgDefault; ?></div>
                                <h6 class="card-title mx-1"><?php echo $row->nome; ?> <?php echo $_SESSION['post_id']; ?></h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4><?php echo $row->titulo ?></h4>
                            <p class="lead"><?php echo $row->content; ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="mb-0" style="color: #6c757d;"><?php echo $dataFormatada; ?></small>
                            <?php 
                                $sql = 'SELECT comentarios.comentario, usuarios.nome FROM comentarios JOIN usuarios ON comentarios.user_id = usuarios.id WHERE comentarios.post_id = ?';
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param('i', $_SESSION['post_id']);
                                $stmt->execute();
                                $stmt->bind_result($comentario, $nome);
                                while($stmt->fetch()){?>
                                    <div class="comentariosContainer">
                                        <p class="comentario"><strong><?php echo $nome ?></strong> <?php echo $comentario;?></p>
                                    </div>
                                <?php } ?>
                            <form action="comentarios.php" class="comentariosForm" method="post">
                                <label for="comentario"></label>
                                <input type="text" id="comentario" name="comentario" placeholder="Escreva um comentário...">
                                <input type="hidden" value="<?php echo $row->id; ?>" name="postId">
                                <button type="submit">Publicar</button>
                            </form>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>
    </main>

    <script src="https://kit.fontawesome.com/ce1e855864.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>