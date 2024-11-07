<?php
session_start();

// Verifica se o usuário está logado e se é do tipo organizador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: login.php");
    exit;
}

require_once '../config/conexao.php';

if (isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];

    $query = "SELECT * FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $evento = $resultado->fetch_assoc();

    if (!$evento) {
        $erro = "Evento não encontrado!";
    }

    $stmt->close();
} else {
    $erro = "ID do evento não foi informado.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
</head>
<body>
    <h2>Editar Evento</h2>

    <?php if (isset($erro)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php else: ?>
        <form action="../controllers/editarEventoController.php" method="POST">
            <label for="titulo">Nome do Evento:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required><br><br>

            <label for="data_evento">Data:</label>
            <input type="date" id="data_evento" name="data_evento" value="<?php echo htmlspecialchars($evento['data_evento']); ?>" required><br><br>

            <label for="local">Local:</label>
            <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($evento['local']); ?>" required><br><br>

            <label for="limite_inscricoes">Limite de Inscrições:</label>
            <input type="number" id="limite_inscricoes" name="limite_inscricoes" value="<?php echo htmlspecialchars($evento['limite_inscricoes']); ?>" required><br><br>

            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['id']); ?>">
            <button type="submit">Atualizar Evento</button>
        </form>
    <?php endif; ?>

    <p><a href="lista_eventos.php">Voltar para a lista de eventos</a></p>
</body>
</html>

<?php
$conn->close();
?>
