<?php 
    require '../Modelo/conexion.php';
    function generarIDUser() {
        $prefijo = "U";
        $fecha = date('ymd');
        $random = rand(0, 9); //Para asegurar que el ID sea único
        $id = $prefijo . $fecha . $random;
        return substr($id, 0, 8);
    }
    $id = generarIDUser();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "SELECT * FROM usuario WHERE email = :email"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "<script>
                alert('El correo ya se encuentra registrado');
                window.location = '../Vista/registro_usuario.html';
            </script>";
        exit();
    }

    $sql_insert = "INSERT INTO usuario (usuarioid, username, email, password) VALUES (:usuarioid, :username, :email, :password)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindParam(':usuarioid', $id, PDO::PARAM_STR);
    $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_insert->bindParam(':password', $password, PDO::PARAM_STR);
    if ($stmt_insert->execute()) {
        echo "<script>
                alert('Registro exitoso');
                window.location = '../Vista/index.php';
            </script>";
    }
    else {
        echo "<script>
                alert('Error al registrar');
                window.location = '../Vista/registro_usuario.html';
            </script>";
    }
?>