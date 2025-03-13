<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
// Incluir o arquivo de conexão
include('conexao.php');

// Ler os dados do corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);

// Verificar se a leitura foi bem-sucedida e se os dados não são nulos
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados não recebidos corretamente']);
    exit();
}

// Verificar se os campos estão presentes
if (!isset($data['titulo'], $data['autor'], $data['categoria'], $data['quantidade'])) {
    echo json_encode(['success' => false, 'message' => 'Campos ausentes no corpo da requisição']);
    exit();
}

// Extrair os dados
$titulo = $data['titulo'];
$autor = $data['autor'];
$categoria = $data['categoria'];
$quantidade = $data['quantidade'];

// Validar os dados
if (empty($titulo) || empty($autor) || empty($categoria) || !isset($quantidade)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit();
}

// Inserir os dados na tabela Livro
$sql = "INSERT INTO Livro (titulo, autor, categoria, quantidade) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $titulo, $autor, $categoria, $quantidade);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Livro adicionado com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar livro.']);
}

$stmt->close();
$conn->close();
?>
