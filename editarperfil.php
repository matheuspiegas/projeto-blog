<?php
session_start();
require 'connection.php';
$_SESSION['foto_dir'] = null;
if (isset($_SESSION['userId']) && $_SESSION['userId'] == $_GET['id'] && $_SESSION['autenticado'] == true) {
    $sql = "SELECT usuarios.nome FROM usuarios WHERE usuarios.id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION['userId']);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();


    if (isset($_POST['enviar'])) {
        $nome_arquivo = $_FILES['foto']['name'];
        $nome_temporario = $_FILES['foto']['tmp_name'];
        $pasta_destino = "uploads/";
        $novo_nome_arquivo = $pasta_destino . uniqid() . '_' . $nome_arquivo;
        if (move_uploaded_file($nome_temporario, $novo_nome_arquivo)) {
            $sqlfoto = 'UPDATE usuarios SET foto = ? WHERE usuarios.id = ?';
            $stmt = $conn->prepare($sqlfoto);
            $stmt->bind_param('si', $novo_nome_arquivo, $_SESSION['userId']);
            if ($stmt->execute()) {
                $linhas_afetadas = $stmt->affected_rows;
                $stmt->close();
                if ($linhas_afetadas == 1) {
                    $sql_foto_dir = 'SELECT usuarios.foto FROM usuarios WHERE usuarios.foto = ? AND usuarios.id = ?';
                    $stmt = $conn->prepare($sql_foto_dir);
                    $stmt->bind_param('si', $novo_nome_arquivo, $_SESSION['userId']);
                    $stmt->execute();
                    $stmt->bind_result($resultado_dir);
                    $stmt->fetch();
                }
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

<body>
    <main>
        <h1>Editar Perfil</h1>
        <h3><?php echo $_SESSION['user']?></h3>
        <?php if (empty($resultado_dir)) { ?>
            <p>Nenhuma foto de perfil</p>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="foto">
                <input type="submit" value="Enviar" name="enviar">
            </form>
        <?php } else { ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="foto">
                <input type="submit" value="Enviar" name="enviar">
            </form>
        <?php } ?>
    </main>
</body>

</html>