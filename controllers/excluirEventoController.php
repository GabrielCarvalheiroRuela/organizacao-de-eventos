<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/conexao.php';

// Verifica ID do evento a ser excluido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['evento_id'])) {
    $evento_id = $_POST['evento_id'];

    // Exclui o evento do SQL
    $query = "DELETE FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);

    if ($stmt->execute()) {
        echo "<p>Evento excluído com sucesso!</p>";
        header("Location: ../views/lista_eventos.php");
        exit;
    } else {
        echo "<p>Erro ao excluir evento: " . $conn->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>ID do evento não especificado!</p>";
}

$conn->close();
?>
