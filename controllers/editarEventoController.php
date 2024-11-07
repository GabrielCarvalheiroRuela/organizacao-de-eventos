<?php
session_start();

// Verifica se o usuário está logado e se é do tipo organizador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: login.php");
    exit;
}

require_once '../config/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['evento_id'])) {
    $evento_id = $_POST['evento_id'];
    $titulo = $_POST['titulo'];
    $data_evento = $_POST['data_evento'];
    $local = $_POST['local'];
    $limite_inscricoes = $_POST['limite_inscricoes'];

    $query = "UPDATE eventos SET titulo = ?, data_evento = ?, local = ?, limite_inscricoes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("sssii", $titulo, $data_evento, $local, $limite_inscricoes, $evento_id);

        if ($stmt->execute()) {
            $mensagem = "Evento atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar evento: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensagem = "Erro na preparação da consulta: " . $conn->error;
    }
} else {
    $mensagem = "ID do evento não foi informado.";
}

$query = "SELECT * FROM eventos";
$result = $conn->query($query);

include '../views/lista_eventos.php';
$conn->close();
?>
