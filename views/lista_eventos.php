<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e se é do tipo organizador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: login.php");
    exit;
}

require_once '../config/conexao.php';

// Busca todos os eventos
$query = "SELECT * FROM eventos";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
</head>

<body>
    <h2>Lista de Eventos</h2>
    <p>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</p>

    <form action="../controllers/eventosController.php" method="POST">
    <h3>Criar Novo Evento</h3>
    <input type="hidden" name="acao" value="criar_evento">
    
    <label for="titulo">Nome do Evento:</label>
    <input type="text" id="titulo" name="titulo" required><br><br>

    <label for="data_evento">Data:</label>
    <input type="date" id="data_evento" name="data_evento" required><br><br>

    <label for="local">Local:</label>
    <input type="text" id="local" name="local" required><br><br>

    <label for="limite_inscricoes">Limite de Inscrições:</label>
    <input type="number" id="limite_inscricoes" name="limite_inscricoes" required><br><br>

    <button type="submit">Criar Evento</button>
</form>

    <h3>Eventos Criados</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome do Evento</th>
            <th>Data</th>
            <th>Local</th>
            <th>Limite de inscrições</th>
            <th>Ações</th>
        </tr>
        <?php while ($evento = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $evento['id']; ?></td>
                <td><?php echo $evento['titulo']; ?></td>
                <td><?php echo date('Y-m-d', strtotime($evento['data_evento'])); ?></td>
                <td><?php echo $evento['local']; ?></td>
                <td><?php echo (isset($evento['num_inscricoes']) ? $evento['num_inscricoes'] : '0') . '/' . $evento['limite_inscricoes']; ?></td>
                <td>
                    <form action="../views/editar_evento_view.php" method="GET" style="display:inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <button type="submit">Editar</button>
                    </form>

                    <form action="../controllers/excluirEventoController.php" method="POST" style="display:inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="../controllers/logoutController.php">Sair</a></p>
</body>

</html>

<?php
$stmt->close();
?>
