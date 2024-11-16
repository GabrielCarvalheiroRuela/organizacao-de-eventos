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
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 0.75rem;
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

        p {
            margin-top: 1rem;
            color: #555;
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
        <h2>Editar Evento</h2>

        <?php if (isset($erro)): ?>
            <p><?php echo htmlspecialchars($erro); ?></p>
        <?php else: ?>
            <form action="../controllers/editarEventoController.php" method="POST">
                <label for="titulo">Nome do Evento:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>

                <label for="data_evento">Data:</label>
                <input type="date" id="data_evento" name="data_evento" value="<?php echo htmlspecialchars($evento['data_evento']); ?>" required>

                <label for="local">Local:</label>
                <input type="text" id="local" name="local" value="<?php echo htmlspecialchars($evento['local']); ?>" required>

                <label for="limite_inscricoes">Limite de Inscrições:</label>
                <input type="number" id="limite_inscricoes" name="limite_inscricoes" value="<?php echo htmlspecialchars($evento['limite_inscricoes']); ?>" required>

                <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($evento['id']); ?>">
                <button type="submit">Atualizar Evento</button>
            </form>
        <?php endif; ?>

        <p><a href="lista_eventos.php">Voltar para a lista de eventos</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>
