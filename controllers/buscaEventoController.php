<?php
// Função para buscar todos os eventos
function getEventos() {
    require_once '../config/conexao.php';

    $query = "SELECT id, titulo, data_evento, local, limite_inscricoes, num_inscricoes FROM eventos";
    $result = $conn->query($query);

    if (!$result) {
        die("Erro ao executar a consulta: " . $conn->error);
    }

    $eventos = $result->fetch_all(MYSQLI_ASSOC);

    $conn->close();

    return $eventos;
}
?>
