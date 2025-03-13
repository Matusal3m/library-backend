<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexao.php';

$sql = "SELECT  numero_matricula, nome, email,turma, telefone FROM Aluno";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $alunos = [];
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
    echo json_encode($alunos);
} else {
    echo json_encode([]);
}

$conn->close();
?>
