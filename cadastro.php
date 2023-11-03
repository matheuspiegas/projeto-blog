<?php
session_start();
require 'connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        header('Location: cadastro.php?erro=vazios');
        exit();
    }

    $checkQuery = "SELECT COUNT(*) FROM usuarios WHERE nome = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch(); //fetch() armazena o resultado do bind_result() na variavel passada por parametro. é obrigatorio sempre ter um fetch logo apos um bidn_result().
    $stmt->close(); // é essencial se tiver mais de uma query sempre fechar uma antes de iniciar a outra.
    if ($count > 0) {
        header('Location: cadastro.php?erro=existente');
    } else {
        $sql = 'INSERT INTO usuarios(nome, senha, data_cadastro) VALUES (?, ?, NOW())';
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $username, $password);
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: login.php?cadastro=sucesso');
            }
        }
    }
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

<body class="d-flex align-items-center">
    <?php if (!empty($_GET['erro']) && $_GET['erro'] == 'vazios') { ?>
        <main class="m-auto">
            <div class="d-flex container gap-3">
                <div class="imagem d-none d-md-block">
                    <img src="assets/sign_in.svg" alt="" class="img-fluid">
                </div>
                <div class="align-self-center">
                    <form action="" method="post" class="container form-container p-0">
                        <h1 class="text-start h1">Faça seu cadastro</h1>
                        <div class="row mb-3">
                            <div class="col-12 mb-2">
                                <input type="text" id="user" name="user" class="form-control" placeholder="Digite seu usuário">
                            </div>
                            <div class="col-12">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-12 mb-2"><button type="submit" class="btn bg-secondary-subtle flex-grow-1">Cadastrar</button></div>
                            <p class="text-danger">Preencha todos os campos!</p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    <?php } ?>



    <?php if (!empty($_GET['erro']) && $_GET['erro'] == 'existente') { ?>
        <main class="m-auto">
            <div class="d-flex container gap-3">
                <div class="imagem d-none d-md-block">
                    <img src="assets/sign_in.svg" alt="" class="img-fluid">
                </div>
                <div class="align-self-center">
                    <form action="" method="post" class="container form-container p-0">
                        <h1 class="text-start h1">Faça seu cadastro</h1>
                        <div class="row mb-3">
                            <div class="col-12 mb-2">
                                <input type="text" id="user" name="user" class="form-control" placeholder="Digite seu usuário">
                            </div>
                            <div class="col-12">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-12 mb-2"><button type="submit" class="btn bg-secondary-subtle flex-grow-1">Cadastrar</button></div>
                            <p class="text-danger">Esse usuário ja existe!</p>
                        </div>
                    </form>
                </div>
            </div>
        </main> <?php } ?>


    <?php if ($_SERVER['REQUEST_URI'] == '/projetoBlog/cadastro.php') { ?>
        <main class="m-auto">
            <div class="d-flex container gap-3">
                <div class="imagem d-none d-md-block">
                    <img src="assets/sign_in.svg" alt="" class="img-fluid">
                </div>
                <div class="align-self-center">
                    <form action="" method="post" class="container form-container p-0">
                        <h1 class="text-start h1">Faça seu cadastro</h1>
                        <div class="row mb-3">
                            <div class="col-12 mb-2">
                                <input type="text" id="user" name="user" class="form-control" placeholder="Digite seu usuário">
                            </div>
                            <div class="col-12">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-12 mb-2"><button type="submit" class="btn bg-secondary-subtle flex-grow-1">Cadastrar</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </main> <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>