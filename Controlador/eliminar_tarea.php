<?php
    header('Content-Type: application/json');
    require '../Modelo/conexion.php';

    $input = json_decode(file_get_contents('php://input'), true);
    if(isset($input['tareaid'])) {
        $tareaid = $input['tareaid'];

        try{
            $sql="DELETE FROM tarea WHERE tareaid = :tareaid";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tareaid', $tareaid, PDO::PARAM_STR);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Tarea eliminada correctamente']);
            }
            else{
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la tarea']);
            }
        }
        catch(Exception $e){
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la tarea: ' . $e->getMessage()]);
        }
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Datos incorrectos']);
    }
?>