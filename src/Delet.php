<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $conn->real_escape_string($data['id']);

    // Seleciona o id do livro associado ao aluguel
    $sqlSelect = "SELECT livro_id FROM Aluguel WHERE id = '$id'";
    $resultSelect = $conn->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $livroId = $row['livro_id'];
        // Deleta o registro do aluguel
        $sqlDelete = "DELETE FROM Aluguel WHERE id = '$id'";
        if ($conn->query($sqlDelete) === TRUE) {
            // Incrementa a quantidade do livro na tabela Livro
            $sqlUpdate = "UPDATE Livro SET quantidade = quantidade + 1 WHERE id = '$livroId'";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo json_encode(["message" => "Devolução confirmada."]);
            } else {
                echo json_encode(["error" => "Erro ao atualizar a quantidade do livro: " . $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Erro ao excluir o aluguel: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Aluguel não encontrado"]);
    }
} else {
    echo json_encode(["error" => "ID do aluguel não fornecido"]);
}

$conn->close();
?>