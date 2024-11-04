<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'organizador') {
    header("Location: login.php");
    exit;
}

require_once 'conexao.php';

// Verifica o formulário de criação de evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'criar_evento') {
    $titulo = $_POST['titulo'];
    $data_evento = $_POST['data_evento'];
    $local = $_POST['local'];
    $limite_inscricoes = $_POST['limite_inscricoes'];

    // Insere o novo evento no SQL
    $query = "INSERT INTO eventos (titulo, data_evento, local, limite_inscricoes) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("sssi", $titulo, $data_evento, $local, $limite_inscricoes);

        // Insere no banco
        if ($stmt->execute()) {
            echo "<p>Evento criado com sucesso!</p>";
        } else {
            echo "<p>Erro ao criar evento: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Erro na preparação da consulta: " . $conn->error . "</p>";
    }
}

// Consulta todos os eventos
$query = "SELECT * FROM eventos";
$result = $conn->query($query);
?>

<!-- Tela de ADM para criar e visualizar eventos do sistema-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
</head>
<body>
    <h2>Lista de Eventos</h2>

    <form action="" method="POST">
        <h3>Criar Novo Evento</h3>
        <label for="titulo">Nome do Evento:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="data_evento">Data:</label>
        <input type="date" id="data_evento" name="data_evento" required><br><br>

        <label for="local">Local:</label>
        <input type="text" id="local" name="local" required><br><br>

        <label for="limite_inscricoes">Limite de Inscrições:</label>
        <input type="number" id="limite_inscricoes" name="limite_inscricoes" required min="0"><br><br>

        <input type="hidden" name="acao" value="criar_evento">
        <button type="submit">Criar Evento</button>
    </form>

    <h3>Eventos Disponíveis</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome do Evento</th>
            <th>Data</th>
            <th>Local</th>
            <th>Inscrições</th>
            <th>Ações</th>
        </tr>
        <?php while ($evento = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $evento['id']; ?></td>
                <td><?php echo $evento['titulo']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($evento['data_evento'])); ?></td>
                <td><?php echo $evento['local']; ?></td>
                <td><?php echo (isset($evento['num_inscricoes']) ? $evento['num_inscricoes'] : '0') . '/' . $evento['limite_inscricoes']; ?></td>
                <td>
                    <form action="editar_evento.php" method="POST" style="display:inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form action="excluir_evento.php" method="POST" style="display:inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="logout.php">Sair</a></p>
</body>
</html>

<?php
$conn->close();
?>