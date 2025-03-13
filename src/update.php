<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite requisições de qualquer origem
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permite POST e OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos necessários

// Verifica se é uma requisição OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Responde com status 200 para requisições OPTIONS
    http_response_code(200);
    exit();
}

require_once 'conexao.php';

// Usando a conexão mysqli
global $conn;

try {
    // Lê os dados enviados no corpo da requisição
    $input = json_decode(file_get_contents("php://input"), true);

    // Validação dos dados recebidos
    $requiredFields = ['nome', 'telefone', 'numero_matricula', 'email', 'turma'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Campo obrigatório '$field' está faltando."]);
            exit;
        }
    }

    // Atualiza os dados no banco com mysqli
    $stmt = $conn->prepare("UPDATE Aluno SET 
        nome = ?, 
        telefone = ?, 
        email = ?, 
        turma = ? 
        WHERE numero_matricula = ?");

    $stmt->bind_param("ssssi", 
        $input['nome'], 
        $input['telefone'], 
        $input['email'], 
        $input['turma'], 
        $input['numero_matricula'] // Usando matrícula como chave primária
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Aluno atualizado com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar aluno.']);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
