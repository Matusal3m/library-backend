<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Conexão com o banco de dados
require_once 'conexao.php';

// Recebe os dados JSON do frontend
$data = json_decode(file_get_contents("php://input"));

// Verificar se os dados estão presentes
if (isset($data->Nome_aluno, $data->Matricula, $data->turma, $data->Nome_livro, $data->data_devolucao)) {
    $nomeAluno = $data->Nome_aluno;
    $matricula = $data->Matricula;
    $turma = $data->turma;
    $nomeLivro = $data->Nome_livro;
    $dataDevolucao = $data->data_devolucao;

    // Iniciar transação
    $conn->begin_transaction();

    try {
        // Inserir os dados no banco
        $sqlAluguel = "INSERT INTO Aluguel (aluno_id, livro_id, data_devolucao) 
                SELECT Aluno.numero_matricula, Livro.id, ? 
                FROM Aluno, Livro
                WHERE Aluno.nome = ? AND Livro.titulo = ?";
        
        // Preparar a consulta
        $stmtAluguel = $conn->prepare($sqlAluguel);
        if ($stmtAluguel === false) {
            throw new Exception('Erro na preparação da consulta: ' . $conn->error);
        }

        $sqlHistoric = "INSERT INTO Historic (aluno_id, livro_id)
                        SELECT Aluno.numero_matricula, Livro.id
                        FROM Aluno, Livro
                        WHERE Aluno.nome = ? AND Livro.titulo = ?";
        $stmtHistoric = $conn->prepare($sqlHistoric);
        $stmtHistoric->bind_param("ss", $nomeAluno, $nomeLivro);

        if (!$stmtHistoric->execute()) {
            throw new Exception("Erro ao registrar informações na tabela Historic.");
        }
            


        // Vincular parâmetros
        $stmtAluguel->bind_param("sss", $dataDevolucao, $nomeAluno, $nomeLivro);

        // Executar a consulta
        if (!$stmtAluguel->execute()) {
            throw new Exception("Erro ao registrar aluguel.");
        }

        // Atualizar a quantidade do livro
        $updateSql = "UPDATE Livro 
                      SET quantidade = quantidade - 1 
                      WHERE titulo = ? AND quantidade > 0";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $nomeLivro);

        if (!$updateStmt->execute()) {
            throw new Exception("Erro ao atualizar quantidade do livro.");
        }

        // Confirma a transação
        $conn->commit();

        echo json_encode(["message" => "Aluguel registrado com sucesso."]);

    } catch (Exception $e) {
        // Em caso de erro, faz rollback
        $conn->rollback();
        echo json_encode(["error" => $e->getMessage()]);
    }

    // Fechar a declaração
    $stmtAluguel->close();
    $updateStmt->close();
} else {
    echo json_encode(["error" => "Dados incompletos."]);
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
