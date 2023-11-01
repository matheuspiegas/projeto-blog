<?php
session_start();
require 'connection.php';
$msgErro = null;
$msgSucesso = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE usuarios.nome = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ($count == 1 && !empty($pass) && !empty($user)) {
            $resetPass = 'update usuarios set usuarios.senha = ? where usuarios.nome = ?';
            $stmt = $conn->prepare($resetPass);
            $stmt->bind_param('ss', $pass, $user);
            $stmt->execute();
            $msgSucesso = 'Senha alterada com sucesso!';
        }
        if(empty($user) && empty($pass)){
            $msgErro = 'Preencha todos os campos!';
        }
        if(!empty($user) && empty($pass)){
            $msgErro = 'Senha não pode estar em branco!';
        }
        if(empty($user) && !empty($pass)){
            $msgErro = 'Usuário não pode estar em branco!';
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

<body>

    <main class="container m-auto mt-5">
        <form action="" method="post" class="form-container m-auto">
            <div class="container">
                <h1>Redefinir senha</h1>
                <div class="row">
                    <div class="mb-2">
                        <input type="text" name="user" class="form-control" placeholder="Digite seu usuário">
                    </div>
                    <div class="mb-2">
                        <input type="password" name="pass" class="form-control" placeholder="Nova senha">
                    </div>
                    <div class="mb-2">
                        <button class="btn bg-secondary-subtle mb-1" type="submit">Enviar</button>
                        <br>
                        <a href="login.php">Fazer Login</a>
                    </div>
                    <?php
                    if ($msgErro != null) { ?>
                        <p class="text-danger"><?php echo $msgErro ?></p>
                    <?php } elseif ($msgSucesso != null) { ?>
                        <p class="text-success"><?php echo $msgSucesso ?></p>
                    <?php }
                    ?>
                </div>
            </div>
        </form>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>