<?php
require_once '../config/conexao.php';
require_once '../models/criar_eventos.php';
require_once '../models/criar_inscricoes.php';

session_start();

// Recebe e-mail e senha do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco de dados pelo e-mail
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            if ($_SESSION['tipo_usuario'] == 'participante') {
                header("Location: ../views/eventos_disponiveis_view.php");
                exit;
            } elseif ($_SESSION['tipo_usuario'] == 'organizador') {
                header("Location: ../views/lista_eventos.php");
                exit;
            } else {
                $mensagem = "Tipo de usuário inválido.";
            }
        } else {
            $mensagem = "Senha incorreta!";
        }
    } else {
        $mensagem = "Usuário não encontrado!";
    }

    $stmt->close();
    $conn->close();
}
?>
