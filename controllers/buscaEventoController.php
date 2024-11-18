<?php
require_once '../config/conexao.php';

function getEventos() {
    global $conn;

    // ID do usuário logado
    $usuario_id = $_SESSION['usuario_id'];

    $query = "SELECT e.*, 
                     (SELECT COUNT(*) 
                      FROM inscricoes i 
                      WHERE i.evento_id = e.id AND i.usuario_id = ?) AS inscrito
              FROM eventos e";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }

    $stmt->close();
    return $eventos;
}

