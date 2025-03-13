<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Conexão com o banco de dados
require_once 'conexao.php';

// Consulta SQL ajustada conforme as tabelas fornecidas
$sql = "SELECT 
            Aluguel.id, 
            Aluno.nome AS Nome_aluno, 
            Aluno.turma AS turma, 
            Aluno.numero_matricula AS Matricula, 
            Livro.titulo AS Nome_livro, 
            Aluguel.data_devolucao 
        FROM Aluguel
        INNER JOIN Aluno ON Aluguel.aluno_id = Aluno.numero_matricula
        INNER JOIN Livro ON Aluguel.livro_id = Livro.id";

$result = $conn->query($sql);

// Verificar se há resultados
if ($result->num_rows > 0) {
    $alugueis = []; 
    // Loop para buscar todos os aluguéis e armazená-los em um array
    while ($row = $result->fetch_assoc()) {
        $alugueis[] = $row;
    }
    // Retornar os dados como JSON
    echo json_encode($alugueis);
} else {
    // Caso não haja resultados, retorna um array vazio
    echo json_encode([]);
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
