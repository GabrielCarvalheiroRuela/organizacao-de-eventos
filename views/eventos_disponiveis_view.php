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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
        }

        th {
            background-color: #f4f4f9;
        }

        td {
            background-color: #fff;
        }

        button {
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button[disabled] {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .cancelar-inscricao {
            color: red;
        }

        p {
            text-align: center;
            margin-top: 1.5rem;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Eventos Disponíveis</h2>

        <table>
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
                            <?php if ($evento['inscrito']): ?>
                                <button type="submit" class="cancelar-inscricao">Cancelar Inscrição</button>
                            <?php elseif ($evento['num_inscricoes'] < $evento['limite_inscricoes']): ?>
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
    </div>
</body>
</html>
