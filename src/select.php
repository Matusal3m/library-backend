<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *"); // Permite requisições de qualquer origem
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


require_once 'conexao.php';

try {
    // Lista todos os alunos
    $sql = "SELECT nome, numero_matricula, turma, telefone, email FROM Aluno";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    } else {
        echo json_encode(['message' => 'Nenhum aluno encontrado.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
} finally {
    // Fecha a conexão
    $conn->close();
}
?>
