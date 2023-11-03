<?php
$username = 'root';
$password = '';
$host = 'localhost';
$db = 'users';

//Tratamento de erros: Try é o bloco que tenta conectar com o banco de dados. Se a conexao com o banco de dados falha, ele lança uma nova exceção (throw new Exception) para ser tratada no bloco catch, que pega essa exceção e executa o die() com a msg de erro.
try {
    $conn = new mysqli($host, $username, $password, $db);
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Erro na conexao: " . $e->getMessage());
}
