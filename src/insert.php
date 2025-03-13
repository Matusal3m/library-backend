<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
include 'conexao.php';

// Recebe os dados JSON do frontend
$data = json_decode(file_get_contents('php://input'), true);

// Valida se os dados estão presentes
if (
    isset($data['numero_matricula'], $data['email'], $data['nome'], $data['turma'], $data['telefone'])
) {
    $numero_matricula = $data['numero_matricula'];
    $email = $data['email'];
    $nome = $data['nome'];
    $turma = $data['turma'];
    $telefone = $data['telefone'];

    // Insere os dados no banco de dados
    $stmt = $conn->prepare("INSERT INTO Aluno (numero_matricula, email, nome, turma, telefone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $numero_matricula, $email, $nome, $turma, $telefone);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao inserir aluno."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos."]);
}

$conn->close();
?>
