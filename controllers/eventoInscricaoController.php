<?php
// eventoInscricaoController.php

session_start();

// Verifica se o usuário está logado e se é do tipo 'participante'
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'participante') {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$evento_id = $_POST['evento_id'];

$query = "SELECT * FROM inscricoes WHERE usuario_id = ? AND evento_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $usuario_id, $evento_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $query = "DELETE FROM inscricoes WHERE usuario_id = ? AND evento_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $evento_id);
    $stmt->execute();

    $query = "UPDATE eventos SET num_inscricoes = num_inscricoes - 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();

} else {
    $query = "INSERT INTO inscricoes (usuario_id, evento_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $evento_id);
    $stmt->execute();

    $query = "UPDATE eventos SET num_inscricoes = num_inscricoes + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header("Location: ../views/eventos_disponiveis_view.php");
exit;
?>
