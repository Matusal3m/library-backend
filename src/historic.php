<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Conexão com o banco de dados
require_once 'conexao.php';

// Verificar conexão
if ($conn->connect_error) {
    echo json_encode(["error" => "Erro na conexão com o banco de dados: " . $conn->connect_error]);
    exit;
}

// Verificar se foi passado o parâmetro 'matricula'
$matricula = isset($_GET['matricula']) ? $conn->real_escape_string($_GET['matricula']) : null;

// Consulta SQL
$sql = "SELECT 
            Historic.id, 
            Aluno.nome AS ome_aluno,
            Aluno.numero_matricula AS Matricula, 
            Livro.titulo AS Nome_livro, 
            Livro.autor AS autor,
            Livro.categoria AS categoria
        FROM Historic
        INNER JOIN Aluno ON Historic.aluno_id = Aluno.numero_matricula
        INNER JOIN Livro ON Historic.livro_id = Livro.id";

// Se a matrícula foi passada, adicionar um filtro na consulta
if ($matricula) {
    $sql .= " WHERE Aluno.numero_matricula = '$matricula'";
}

$result = $conn->query($sql);

// Verificar se a consulta foi bem-sucedida
if (!$result) {
    echo json_encode(["error" => "Erro ao executar a consulta: " . $conn->error]);
    exit;
}

// Verificar se há resultados
if ($result->num_rows > 0) {
    $alugueis = [];
    // Loop para buscar todos os aluguéis e armazená-los em um array
    while ($row = $result->fetch_assoc()) {
        $alugueis[] = $row;
    }
    // Retornar os dados como JSON
    echo json_encode([
        "message" => "Aluguéis encontrados.",
        "total" => $result->num_rows,
        "data" => $alugueis
    ]);
} else {
    // Caso não haja resultados, retorna um array vazio com mensagem
    echo json_encode([
        "message" => "Nenhum aluguel encontrado.",
        "data" => []
    ]);
}

// Liberar os recursos do resultado
$result->free();

// Fechar a conexão com o banco de dados
$conn->close();
?>
