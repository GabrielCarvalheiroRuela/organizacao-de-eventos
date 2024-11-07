<?php
// conexao do banco
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'sistema_eventos';

$conn = new mysqli($servidor, $usuario, $senha);

// Verifica se conectou
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Cria o banco de dados se não existir
$sql = "CREATE DATABASE IF NOT EXISTS $banco";

// Executa a consulta para criar o banco de dados
if ($conn->query($sql) === TRUE) {
    $conn->select_db($banco);
} else {
    echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
}

// Verifica se conectou
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}
?>
