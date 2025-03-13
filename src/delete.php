<?php
header("Content-Type: application/json");

// Permite que qualquer origem faça requisições
header("Access-Control-Allow-Origin: *");  // Substitua pelo endereço do seu frontend
header("Access-Control-Allow-Methods: POST, OPTIONS");  // Permite POST e OPTIONS (para o preflight)
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Permite cabeçalhos necessários

// Verifica se a requisição é do tipo OPTIONS (pré-vôo)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Se for uma requisição OPTIONS, apenas retorne 200 OK
    http_response_code(200);
    exit;
}

// Inclui o arquivo de conexão
require_once 'conexao.php';

// Lê os dados enviados no corpo da requisição
$input = json_decode(file_get_contents("php://input"), true);

// Verifica se o numero_matricula foi fornecido
if (empty($input['numero_matricula'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Campo obrigatorio numero_matricula está faltando.']);
    exit;
}

// Obtém o número da matrícula
$numero_matricula = $input['numero_matricula'];

// Exclui o aluno com base no numero_matricula
$query = "DELETE FROM Aluno WHERE numero_matricula = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    // Verifica se a query foi preparada corretamente
    $stmt->bind_param("i", $numero_matricula);

    // Executa a query
    if ($stmt->execute()) {
        // Verifica se algum registro foi excluído
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Aluno excluído com sucesso!']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Nenhum aluno encontrado com o numero_matricula fornecido.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao executar a query de exclusão.']);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na preparação da query.']);
}

$conn->close();
?>
