<?php
    session_start(); 
    require '../Modelo/conexion.php';
    $data = json_decode(file_get_contents('php://input'), true);
    $titulo = $data['titulo'] ?? null;
    $descripcion = $data['descripcion'] ?? null;
    $fecha_limite = $data['fecha_limite'] ?? null;
    function generarIDTarea() {
        $prefijo = "T";
        $fecha = date('ymd');
        $random = rand(0, 9); //Para asegurar que el ID sea único
        $id = $prefijo . $fecha . $random;
        return substr($id, 0, 8);
    }
    $id = generarIDTarea();
    if (!isset($_SESSION['usuarioid'])) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesion']);
        exit();
    }

    $idUser = $_SESSION['usuarioid'];
    if($titulo && $descripcion && $fecha_limite) {
        $sql = "INSERT INTO tarea (tareaid, usuarioid, titulo, descripcion, estado, fExp, fCreacion) VALUES (:tareaid, :usuarioid, :titulo, :descripcion, 'Pendiente', :fExp, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tareaid', $id, PDO::PARAM_STR);
        $stmt->bindParam(':usuarioid', $idUser, PDO::PARAM_STR);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':fExp', $fecha_limite, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tarea añadida correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al añadir la tarea']);
        }
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Datos incorrectos']);
    }
?>