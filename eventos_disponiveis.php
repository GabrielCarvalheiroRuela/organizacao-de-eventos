<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'participante') {
    header("Location: login.php");
    exit;
}

require_once 'conexao.php';

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

?>

<!-- Tela de eventos disponiveis do sistema -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Disponíveis</title>
    <style>
        .cancelar-inscricao {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Eventos Disponíveis</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome do Evento</th>
            <th>Data</th>
            <th>Local</th>
            <th>Inscrições</th>
            <th>Ação</th>
        </tr>
        <?php while ($evento = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $evento['id']; ?></td>
                <td><?php echo $evento['nome']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($evento['data'])); ?></td>
                <td><?php echo $evento['local']; ?></td>
                <td><?php echo $evento['num_inscricoes'] . '/' . $evento['limite_inscricoes']; ?></td>
                <td>
                    <form action="participar_evento.php" method="POST">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <?php if ($evento['inscrito'] > 0): ?>
                            <button type="submit" class="cancelar-inscricao">Cancelar inscrição</button>
                        <?php else: ?>
                            <?php if ($evento['num_inscricoes'] < $evento['limite_inscricoes']): ?>
                                <button type="submit">Inscrever-se</button>
                            <?php else: ?>
                                <button type="submit" disabled>Lotado</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="logout.php">Sair</a></p>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>