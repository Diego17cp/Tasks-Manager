<?php 
    session_start();
    require '../Modelo/conexion.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuario WHERE email = :email AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['usuarioid'] = $usuario['usuarioid'];
        $_SESSION['username'] = $usuario['username'];
        echo "<script>
                alert('Login exitoso');
                window.location = '../Vista/index.php';
            </script>";
        exit();
    }
    else {
        echo "<script>
                alert('Login fallido');
                window.location = '../Vista/index.php';
            </script>";
        exit();
    }
?>