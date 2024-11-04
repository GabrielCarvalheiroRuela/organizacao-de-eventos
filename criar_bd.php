<?php
require_once 'conexao.php';

// Criando tabela de usuarios
$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('organizador', 'participante') NOT NULL
)";

// Criando tabelas de eventos
$sqlEventos = "CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_evento DATETIME NOT NULL,
    local VARCHAR(255) NOT NULL,
    limite_inscricoes INT NOT NULL,
    num_inscricoes INT DEFAULT 0
)";

// Criando tabela das inscrições de cada evento
$sqlInscricoes = "CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT,
    usuario_id INT,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
)";

// Executa as queries para criar as tabelas
$conn->query($sqlUsuarios);
$conn->query($sqlEventos);
$conn->query($sqlInscricoes);

?>