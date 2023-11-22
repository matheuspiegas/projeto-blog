<?php
require 'connection.php';
session_start();
if ($_SESSION['autenticado'] == true) {
    $userId = $_GET['id'];
    $postId = $_GET['idpost'];
    $sql = 'SELECT posts.titulo, posts.content from posts where posts.user_id = ? and posts.id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userId, $postId);
    $stmt->execute();
    $stmt->bind_result($titulo, $content);
    $stmt->fetch();
    $stmt->close();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['conteudo'])) {
        $sqlUpdate = 'UPDATE posts SET titulo = ?, content = ? WHERE id = ? AND user_id = ?';
        $stmtUpdate = $conn->prepare($sqlUpdate);

        if (!$stmtUpdate) {
            die("Erro na preparação da instrução SQL de atualização: " . $conn->error);
        }

        $stmtUpdate->bind_param('ssii', $_POST['titulo'], $_POST['conteudo'], $postId, $userId);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows >= 0) {
            echo "Atualização bem-sucedida!";
            header('Location: perfil.php?id=' . $userId);
        } 

        $stmtUpdate->close();
    }
} else {
    header('Location: login.php');
} ?>

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
                <a class="navbar-brand" href="home.php"> <i class="fa-solid fa-arrow-left mx-2"> </i>Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
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
    <main class="m-auto mt-5 container form-container">
        <form action="" method="post">
            <div class="container">
                <h1>Atualizar post</h1>
                <div class="row">
                    <div class="col-12 mb-1">
                        <label for="titulo">Título: </label>
                        <input class="form-control" type="text" name="titulo" id="titulo" value="<?php echo $titulo ?>">
                    </div>
                    <div class="col-12 mb-1">
                        <label for="conteudo">Conteúdo: </label>
                        <input class="form-control" type="text" name="conteudo" id="conteudo" value="<?php echo $content ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn bg-secondary-subtle">Atualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="https://kit.fontawesome.com/ce1e855864.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>