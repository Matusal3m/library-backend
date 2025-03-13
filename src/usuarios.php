<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'conexao.php';

$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuarios = [];
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode($usuarios);
} else {
    echo json_encode([]);
}

$conn->close();

?>
