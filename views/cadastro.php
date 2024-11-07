<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <h2>Cadastro</h2>
    <?php if (isset($_GET['mensagem'])): ?>
        <p><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>
    <!-- Formulário de cadastro -->
    <form action="../controllers/cadastroController.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="tipo_usuario">Tipo de Usuário:</label>
        <select id="tipo_usuario" name="tipo_usuario" required>
            <option value="participante">Participante</option>
            <option value="organizador">Organizador</option>
        </select><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem uma conta? <a href="login.php">Voltar para o Login</a></p>
</body>
</html>
