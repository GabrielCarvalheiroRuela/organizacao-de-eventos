<?php
require_once '../config/conexao.php';

$sqlEventos = "CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    data_evento DATETIME NOT NULL,
    local VARCHAR(255) NOT NULL,
    limite_inscricoes INT NOT NULL,
    num_inscricoes INT DEFAULT 0
)";

if ($conn->query($sqlEventos) === TRUE) {
    echo "Tabela 'eventos' criada com sucesso!<br>";
} else {
    echo "Erro ao criar a tabela 'eventos': " . $conn->error . "<br>";
}
?>
