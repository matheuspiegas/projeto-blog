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
    <main class="m-auto">
        <div class="d-flex container gap-3">
            <div class="imagem d-none d-md-block">
                <img src="assets/sign_up.svg" alt="" class="img-fluid">
            </div>
            <div class="align-self-center">
                <h1 class="text-start h1 mb-5">Faça Login</h1>
                <form action="login.php" method="post" class="p-0">
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
                    </div>
                    <?php if (!empty($mensagemErro)) { ?>
                        <p class="text-danger p-0"><?php echo $mensagemErro; ?></p>
                    <?php } ?>
                    <a href="resetpass.php" class="resetpass">Esqueceu sua senha?</a>
                </form>
            </div>
        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>