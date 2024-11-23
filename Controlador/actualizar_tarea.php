<?php 
    require '../Modelo/conexion.php';
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['tareaid'] ?? null;
    $titulo = $data['titulo'] ?? null;
    $descripcion = $data['descripcion'] ?? null;
    $fExp = $data['fechaLimite'] ?? null;
    $estado = $data['estado'] ?? null;
    if ($id && $titulo && $descripcion && $fExp) {
        $sql = "UPDATE tarea SET titulo = :titulo, descripcion = :descripcion, fExp = :fExp WHERE tareaid = :tareaid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tareaid', $id, PDO::PARAM_STR);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':fExp', $fExp, PDO::PARAM_STR);
    }
    elseif ($id && $estado) {
        $sql = "UPDATE tarea SET estado = :estado WHERE tareaid = :tareaid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tareaid', $id, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Datos incorrectos']);
        exit();
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Tarea actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la tarea']);
    }
?>