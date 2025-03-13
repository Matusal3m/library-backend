<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['AluguelTec_id'])) {
    $aluguelTecId = $conn->real_escape_string($data['AluguelTec_id']);

    // Seleciona o aluguel técnico pelo id
    $sqlSelectAluguelTec = "SELECT livro_tecnico_id FROM AluguelTec WHERE id = '$aluguelTecId'";
    $resultSelectAluguelTec = $conn->query($sqlSelectAluguelTec);

    if ($resultSelectAluguelTec->num_rows > 0) {
        $aluguelTec = $resultSelectAluguelTec->fetch_assoc();
        $livroTecnicoId = $aluguelTec['livro_tecnico_id'];

        // Atualiza a quantidade de livros na tabela LivroTecnico
        $sqlUpdate = "UPDATE LivroTecnico SET quantidade = quantidade + 1 WHERE id = '$livroTecnicoId'";
        if ($conn->query($sqlUpdate) === TRUE) {
            // Remover o aluguel técnico da tabela AluguelTec
            $sqlDelete = "DELETE FROM AluguelTec WHERE id = '$aluguelTecId'";
            if ($conn->query($sqlDelete) === TRUE) {
                echo json_encode(["message" => "Devolução confirmada."]);
            } else {
                echo json_encode(["error" => "Erro ao remover o aluguel técnico: " . $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Erro ao atualizar a quantidade do livro técnico: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Aluguel técnico não encontrado"]);
    }
} else {
    echo json_encode(["error" => "ID do aluguel técnico não fornecido"]);
}

$conn->close();
?>
