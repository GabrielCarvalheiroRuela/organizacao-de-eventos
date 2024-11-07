<?php
// eventos_disponiveis_view.php

// Inicia a sessão e verifica se o usuário está logado
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['tipo_usuario'], ['organizador', 'participante'])) {
    header("Location: login.php");
    exit;
}

// Inclui o controlador para buscar os eventos
require_once '../controllers/buscaEventoController.php';

// Busca os eventos usando a função do controlador
$eventos = getEventos();
?>

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
            <th>Título do Evento</th>
            <th>Data do Evento</th>
            <th>Local</th>
            <th>Inscrições</th>
            <th>Ação</th>
        </tr>
        <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?php echo htmlspecialchars($evento['id']); ?></td>
                <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($evento['data_evento'])); ?></td>
                <td><?php echo htmlspecialchars($evento['local']); ?></td>
                <td><?php echo htmlspecialchars($evento['num_inscricoes']) . '/' . htmlspecialchars($evento['limite_inscricoes']); ?></td>
                <td>
                    <form action="../controllers/eventoInscricaoController.php" method="POST">
                        <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['id']); ?>">
                        <?php if ($evento['num_inscricoes'] < $evento['limite_inscricoes']): ?>
                            <button type="submit">Inscrever-se</button>
                        <?php else: ?>
                            <button type="submit" disabled>Lotado</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="../controllers/logoutController.php">Sair</a></p>
</body>
</html>
