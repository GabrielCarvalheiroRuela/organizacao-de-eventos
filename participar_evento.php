<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'participante') {
    header("Location: login.php");
    exit;
}

require_once 'conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$evento_id = $_POST['evento_id'];

// Verifica se o usuário já se inscreveu naquele evento
$query = "SELECT * FROM inscricoes WHERE usuario_id = ? AND evento_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $usuario_id, $evento_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Desfazer inscrição caso esteja inscrito
    $query = "DELETE FROM inscricoes WHERE usuario_id = ? AND evento_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $evento_id);
    $stmt->execute();

    // Atualizar número de inscrições no evento
    $query = "UPDATE eventos SET num_inscricoes = num_inscricoes - 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();

} else {
    // Se inscrever no evento caso não esteja inscrito
    $query = "INSERT INTO inscricoes (usuario_id, evento_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $evento_id);
    $stmt->execute();

    // Atualizar número de inscrições no evento
    $query = "UPDATE eventos SET num_inscricoes = num_inscricoes + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// Atualiza a tela
header("Location: eventos_disponiveis.php");
exit;
?>
