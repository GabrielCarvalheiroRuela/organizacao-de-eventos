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

        h2, h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        p {
            color: #555;
            margin-bottom: 1.5rem;
        }

        form {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
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

        td form {
            display: inline;
        }

        td button {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
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
        <h2>Lista de Eventos</h2>
        <p>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</p>

        <form action="../controllers/eventosController.php" method="POST">
            <h3>Criar Novo Evento</h3>
            <input type="hidden" name="acao" value="criar_evento">

            <label for="titulo">Nome do Evento:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="data_evento">Data:</label>
            <input type="date" id="data_evento" name="data_evento" required>

            <label for="local">Local:</label>
            <input type="text" id="local" name="local" required>

            <label for="limite_inscricoes">Limite de Inscrições:</label>
            <input type="number" id="limite_inscricoes" name="limite_inscricoes" required>

            <button type="submit">Criar Evento</button>
        </form>

        <h3>Eventos Criados</h3>
        <table>
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
                        <form action="../views/editar_evento_view.php" method="GET">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <button type="submit">Editar</button>
                        </form>

                        <form action="../controllers/excluirEventoController.php" method="POST">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <p><a href="../controllers/logoutController.php">Sair</a></p>
    </div>
</body>

</html>

<?php
$stmt->close();
?>
