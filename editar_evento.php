<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: login.php");
    exit;
}

require_once 'conexao.php';

// Verifica ID do evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['evento_id'])) {
    $evento_id = $_POST['evento_id'];

    // Busca os dados do evento no SQL
    $query = "SELECT * FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $evento = $resultado->fetch_assoc();

    // Verifica se encontrou o evento
    if (!$evento) {
        echo "<p>Evento não encontrado!</p>";
        exit;
    }

    // Verifica se as alterações foram enviadas
    if (isset($_POST['acao']) && $_POST['acao'] == 'atualizar_evento') {
        $titulo = $_POST['titulo'];
        $data_evento = $_POST['data_evento'];
        $local = $_POST['local'];
        $limite_inscricoes = $_POST['limite_inscricoes'];

        // Atualiza o evento no SQL
        $query = "UPDATE eventos SET titulo = ?, data_evento = ?, local = ?, limite_inscricoes = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $titulo, $data_evento, $local, $limite_inscricoes, $evento_id);

        if ($stmt->execute()) {
            echo "<p>Evento atualizado com sucesso!</p>";
            // Volta para tela de eventos
            header("Location: lista_eventos.php");
            exit;
        } else {
            echo "<p>Erro ao atualizar evento: " . $conn->error . "</p>";
        }
    }
} else {
    echo "<p>ID do evento não especificado!</p>";
    exit;
}
?>

<!-- Tela de edição de eventos do sistema -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
</head>
<body>
    <h2>Editar Evento</h2>

    <form action="" method="POST">
        <label for="titulo">Nome do Evento:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required><br><br>

        <label for="data_evento">Data:</label>
        <input type="date" id="data_evento" name="data_evento" value="<?php echo htmlspecialchars($evento['data_evento']); ?>" required><br><br>

        <label for="local">Local:</label>
        <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($evento['local']); ?>" required><br><br>

        <label for="limite_inscricoes">Limite de Inscrições:</label>
        <input type="number" id="limite_inscricoes" name="limite_inscricoes" value="<?php echo htmlspecialchars($evento['limite_inscricoes']); ?>" required><br><br>

        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
        <input type="hidden" name="acao" value="atualizar_evento">
        <button type="submit">Atualizar Evento</button>
    </form>

    <p><a href="lista_eventos.php">Voltar para a lista de eventos</a></p>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>