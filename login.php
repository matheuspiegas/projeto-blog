<?php
session_start();
require_once 'connection.php';
$mensagemErro = null;
$_SESSION['userId'] = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['password'];
    $sql = 'SELECT senha, id FROM usuarios WHERE BINARY nome = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($senhaHash, $userId);
        if ($stmt->fetch() && password_verify($password, $senhaHash)) {
            $stmt->close();
            $_SESSION['autenticado'] = true;
            $_SESSION['user'] = $username;
            $sqlid = 'SELECT usuarios.id FROM usuarios WHERE BINARY nome = ?';
            $stmt = $conn->prepare($sqlid);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($userId);
            $stmt->fetch();
            $stmt->close();
            $_SESSION['userId'] = $userId;
            header('Location: home.php');
        } elseif (empty($username) || empty($password)) {
            $mensagemErro = 'Por favor, preencha todos os campos!';
        } elseif (!password_verify($password, $senhaHash)) {
            $mensagemErro = 'Senha incorreta!';
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
    <?php if (!empty($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso') { ?>
        <main class="m-auto">
            <div class="d-flex flex-column">
                <h1 class="text-start h1">Faça Login</h1>
                <form action="login.php" method="post" class="container form-container p-0">
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <input type="text" id="user" name="user" class="form-control" placeholder="Digite seu usuário">
                        </div>
                        <div class="col-12">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex col-12 mb-2"><button type="submit" class="btn bg-secondary-subtle flex-grow-1">Entrar</button></div>
                        <div class="d-flex col-12 mb-3"><a class="btn bg-secondary-subtle flex-grow-1" href="cadastro.php">Cadastrar</a></div>
                        <p class="text-success">Usuário cadastrado com sucesso!</p>
                    </div>
                    <a href="resetpass.php" class="">Esqueceu sua senha?</a>
                </form>
            </div>
        </main> <?php } ?>
    <?php if ($_SERVER['REQUEST_URI'] == '/projetoBlog/login.php') { ?>
        <main class="m-auto">
            <div class="d-flex flex-column">
                <h1 class="text-start h1">Faça Login</h1>
                <form action="login.php" method="post" class="container form-container p-0">
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <input type="text" id="user" name="user" class="form-control" placeholder="Digite seu usuário">
                        </div>
                        <div class="col-12">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex col-12 mb-2"><button type="submit" class="btn bg-secondary-subtle flex-grow-1">Entrar</button></div>
                        <div class="d-flex col-12 mb-3"><a class="btn bg-secondary-subtle flex-grow-1" href="cadastro.php">Cadastrar</a></div>
                        <?php if (!empty($mensagemErro)) { ?>
                            <p class="text-danger"><?php echo $mensagemErro; ?></p>
                        <?php } ?>
                    </div>
                    <a href="resetpass.php" class="resetpass">Esqueceu sua senha?</a>
                </form>
            </div>
        </main> <?php } ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>