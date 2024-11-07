<?php
// Verifica se a sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'criar_evento') {
    $titulo = $_POST['titulo'];
    $data_evento = $_POST['data_evento'];
    $local = $_POST['local'];
    $limite_inscricoes = $_POST['limite_inscricoes'];

    // Insere o novo evento no banco de dados
    $query = "INSERT INTO eventos (titulo, data_evento, local, limite_inscricoes) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("sssi", $titulo, $data_evento, $local, $limite_inscricoes);

        // Executa a inserção no banco
        if ($stmt->execute()) {
            $mensagem = "Evento criado com sucesso!";
        } else {
            $mensagem = "Erro ao criar evento: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensagem = "Erro na preparação da consulta: " . $conn->error;
    }
}

// Consulta todos os eventos
$query = "SELECT * FROM eventos";
$result = $conn->query($query);

include '../views/lista_eventos.php';
?>
