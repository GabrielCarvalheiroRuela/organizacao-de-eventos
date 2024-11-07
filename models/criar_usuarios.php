<?php
require_once '../config/conexao.php';

$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('organizador', 'participante') NOT NULL
)";

if ($conn->query($sqlUsuarios) === TRUE) {
    echo "Tabela 'usuarios' criada com sucesso!<br>";
} else {
    echo "Erro ao criar a tabela 'usuarios': " . $conn->error . "<br>";
}
?>
