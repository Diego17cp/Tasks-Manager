<?php 
    session_start();
    require '../Modelo/conexion.php';
    if (isset($_SESSION['usuarioid'])) {
        $id = $_SESSION['usuarioid'];
        $link = "../Modelo/cerrar_sesion.php";
        $mensaje = "Cerrar sesión";
        $clase = "btnLogout";
        $evento = "cerrarSesion()";
    
        $query = "SELECT t.*, u.username 
                FROM tarea t 
                INNER JOIN usuario u ON t.usuarioid = u.usuarioid 
                WHERE t.usuarioid = :usuarioid 
                ORDER BY t.fCreacion DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':usuarioid', $id, PDO::PARAM_STR);
        $stmt->execute();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        
        $link = "../Vista/login_usuario.html";
        $mensaje = "Iniciar sesión";
        $clase = "btnLogin";
        $evento = "";
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castareas</title>
    <link rel="stylesheet" href="../Vista/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <nav class="navbar">
        <a href="../Vista/index.php" class="logo">Castareas</a>
        <div class="botonesNav">
            <a href="<?php echo $link; ?>" class="btnNav <?php echo $clase; ?>" onclick="<?php echo $evento; ?>"><?php echo $mensaje; ?></a>
        </div>
    </nav>
    <main class="main">
    <div class="container">
        <?php if (isset($_SESSION['usuarioid'])): ?>
        <div class="forms">
            <h1>Bienvenido, <?php echo $_SESSION['username']; ?></h1>
            <button onclick="showAddForm()" class="btnAdd">+</button>
            <div id="addForm" class="hidden">
                <div class="title">
                    <h3>Nueva Tarea</h3>
                </div>
                <div class="btnCerrar">
                    <button type="button" onclick="hideAddForm()">X</button>
                </div>
                <form action="" id="addTaskForm">
                    <label for="titulo">Titulo</label>
                    <input type="text" name="titulo" id="titulo" required>
                    <label for="descripcion">Descripcion</label>
                    <textarea name="descripcion" id="descripcion" required></textarea>
                    <label for="fecha">Fecha Limite</label>
                    <input type="date" name="fecha_limite" id="fecha" required>
                    <button type="button" onclick="addTask()">Añadir</button>
                </form>
            </div>
            <div id="editForm" class="hidden">
                <div class="title">
                    <h3>Editar Tarea</h3>
                </div>
                <div class="btnCerrar">
                    <button type="button" onclick="hideEditForm()">X</button>
                </div>
                <form action="" id="updateTaskForm">
                    <input type="hidden" name="tareaid" id="tareaid">
                    <label for="editTitulo">Titulo</label>
                    <input type="text" name="titulo" id="editTitulo" required>
                    <label for="editDescripcion">Descripcion</label>
                    <textarea name="descripcion" id="editDescripcion" required></textarea>
                    <label for="editFecha">Fecha Limite</label>
                    <input type="date" name="fecha_limite" id="editFecha" required">
                    <button type="button" onclick="updateTask()">Actualizar</button>
                </form>
            </div> 
        </div>
        <div class="info">
            <h2>Tareas</h2>
            <div id="taskContainer">
                <?php foreach ($tareas as $tarea): ?>
                    <div id="task-<?php echo $tarea['tareaid']; ?>" class="task <?php echo $tarea['estado'] === 'Completada' ? 'completed' : ''; ?>">
                        <h3><?php echo htmlspecialchars($tarea['titulo']) ?></h3>
                        <div class="eliminar">
                            <button class="btn-delete" onclick="deleteTask('<?php echo $tarea['tareaid']; ?>', this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <div class="contenidoExpandido" >
                            <p><?php echo htmlspecialchars($tarea['descripcion']) ?></p>
                            <p>Fecha Creacion: <?php echo $tarea['fCreacion']; ?></p>
                            <p>Fecha Limite: <?php echo $tarea['fExp']; ?></p>
                            <p>Estado: <?php echo $tarea['estado']; ?></p>
                            <p>Creado por: <?php echo $tarea['username']; ?></p>
                            <div class="accion">
                                <button class="btn-editar" onclick="showEditForm('<?php echo $tarea['tareaid']; ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-complete" onclick="markAsCompleted('<?php echo $tarea['tareaid']; ?>')">
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                                <button class="btn-pdf" onclick="generatePDF('<?php echo $tarea['tareaid']; ?>', '<?php echo $id; ?>')">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>                                                                                                                          
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
            <div class="container-bienvenida">
                <h1 class="title bienvenida">Bienvenido a Castareas</h1>
                <p class="subtitle">Un proyecto para organizar tus tareas, todo desde la misma página. <br>
                    Crea, Edita y puedes controlar cuando ya estén completadas. <br><br>
                    Por favor, inicia sesión para acceder a todas las funcionalidades.</p>
            </div>
            
        <?php endif; ?>
    </div>
    </main>
    <script src="../Controlador/script.js"></script>
</body>
</html>