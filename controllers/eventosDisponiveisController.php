<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'participante') {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/conexao.php';

// ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// Busca os eventos e as inscrições do usuário
$query = "SELECT e.id, e.titulo AS nome, e.data_evento AS data, e.local, e.limite_inscricoes,
                 (SELECT COUNT(*) FROM inscricoes WHERE evento_id = e.id) AS num_inscricoes,
                 (SELECT COUNT(*) FROM inscricoes WHERE evento_id = e.id AND usuario_id = ?) AS inscrito
          FROM eventos e";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

include '../views/eventos_disponiveis_view.php';

$stmt->close();
$conn->close();
?>