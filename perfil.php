<?php
session_start();
require 'connection.php';
if (isset($_SESSION['userId']) && $_SESSION['userId'] == $_GET['id'] && $_SESSION['autenticado'] == true) {
    $sqlselect = "SELECT usuarios.nome, usuarios.id, posts.titulo, posts.content, posts.data_post, posts.id FROM usuarios INNER JOIN posts ON posts.user_id = usuarios.id AND usuarios.nome = ?";

    $sql = "SELECT usuarios.nome, usuarios.foto FROM usuarios WHERE usuarios.id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION['userId']);
    $stmt->execute();
    $stmt->bind_result($result, $resultado_dir);
    $stmt->fetch();
    $stmt->close();
    if (isset($_POST['enviar'])) {
        $nome_arquivo = $_FILES['foto']['name'];
        $nome_temporario = $_FILES['foto']['tmp_name'];
        $pasta_destino = "uploads/";
        $novo_nome_arquivo = $pasta_destino . uniqid() . '_' . $nome_arquivo;
        if (move_uploaded_file($nome_temporario, $novo_nome_arquivo)) {
            $sqlfoto = 'UPDATE usuarios SET foto = ? WHERE id = ?';
            $stmt = $conn->prepare($sqlfoto);
            $stmt->bind_param('si', $novo_nome_arquivo, $_SESSION['userId']);
            if ($stmt->execute()) {
                $linhas_afetadas = $stmt->affected_rows;
                $stmt->close();
                $resultado_dir = $novo_nome_arquivo;
            }
        }
    }

    $stmt = $conn->prepare($sqlselect);
    $stmt->bind_param('s', $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
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
                            <a class="nav-link" aria-current="page" href="criarpost.php"><i class="fa-solid fa-plus mx-2"></i>Novo post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php?sair"><i class="fa-solid fa-arrow-right-from-bracket mx-2"></i>Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="m-auto mt-5">
        
        <div class="container d-md-flex">
            <div class="container">
                <h1 class="text-center">Editar Perfil</h1>
                <?php if (empty($resultado_dir)) { ?>
                    <div class="d-flex justify-content-center perfil-imagem m-auto mb-4">
                        <img src="uploads/default_user.png" alt="" class="imagem-perfil-redonda" style="max-width: 300px;">
                    </div>
                    <h3 class="text-center"><?php echo $_SESSION['user'] ?></h3>
                    <div class="d-flex justify-content-center">
                        <form action="" method="post" enctype="multipart/form-data" style="max-width: 300px;">
                            <input type="file" name="foto" class="form-control mb-2">
                            <input type="submit" value="Enviar" name="enviar" class="form-control btn btn-primary mb-2">
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="d-flex justify-content-center perfil-imagem m-auto mb-4">
                        <img src="<?php echo $resultado_dir; ?>" alt="" class="imagem-perfil-redonda rounded-circle" style="max-width: 300px;">
                    </div>
                    <div class="d-flex justify-content-center">
                        <form action="" method="post" enctype="multipart/form-data" style="max-width: 300px;">
                            <input type="file" name="foto" class="form-control mb-2">
                            <input type="submit" value="Enviar" name="enviar" class="form-control btn btn-primary mb-2">
                        </form>
                    </div>
                <?php } ?>
            </div>
            <div class="container teste">
                <h1>Suas postagens</h1>
                <?php if ($result->num_rows > 0) {
                    echo '<br>';
                    echo '<div class="overflow-y-scroll" style="height:70vh;">';
                    while ($row = $result->fetch_object()) {
                        $data = date('d/m/y', strtotime($row->data_post)); ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title m-0"><?php echo $row->nome; ?></h6>
                                    <a href="editarpost.php?idpost=<?php echo $row->id; ?>&id=<?php echo $_GET['id'] ?>">Editar</a>
                                    <a href="apagar.php?idpost=<?php echo $row->id; ?>" class="text-end text-danger"><i class="fa-solid fa-trash" style="color: #cb2020;"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4><?php echo $row->titulo ?></h4>
                                <p class="lead"><?php echo $row->content ?></p>
                            </div>
                            <div class="card-footer">
                                <small class="mb-0" style="color: #6c757d;"><?php echo $data ?></small>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        <?php } else {
                    echo '<p>Não há posts</p>';
                } ?>
        </div>
        </div>
    </main>

    <script src="https://kit.fontawesome.com/ce1e855864.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>