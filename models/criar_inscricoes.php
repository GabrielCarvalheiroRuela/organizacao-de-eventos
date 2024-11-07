<?php
require_once '../config/conexao.php';

$sqlInscricoes = "CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT,
    usuario_id INT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
)";

if ($conn->query($sqlInscricoes) === TRUE) {
    echo "Tabela 'inscricoes' criada com sucesso!<br>";
} else {
    echo "Erro ao criar a tabela 'inscricoes': " . $conn->error . "<br>";
}
?>
