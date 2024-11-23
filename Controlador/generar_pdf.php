<?php 
        require '../Modelo/conexion.php';
        require '../fpdf/fpdf.php';
        $tareaid=isset($_GET['tareaid']) ? $_GET['tareaid'] : null;
        $id=isset($_GET['id']) ? $_GET['id'] : null;

        if ($tareaid && $id) {
        $sql="SELECT t.*, u.username 
                FROM tarea t 
                INNER JOIN usuario u ON t.usuarioid = u.usuarioid 
                WHERE t.usuarioid = :usuarioid AND t.tareaid = :tareaid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tareaid', $tareaid, PDO::PARAM_STR);
        $stmt->bindParam(':usuarioid', $id, PDO::PARAM_STR);
        $stmt->execute();
        $tarea = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($tarea) {
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16); // Fuente Arial, Negrita, tamaño 16
                $pdf->SetTextColor(0, 51, 102); // Color azul oscuro
                $pdf->Cell(0, 10, 'Detalle de la Tarea', 0, 1, 'C'); // Título centrado
                $pdf->Ln(10); // Salto de línea
                // Separador
                $pdf->SetDrawColor(0, 51, 102); // Color azul oscuro para el borde
                $pdf->SetLineWidth(0.5); // Grosor de línea
                $pdf->Line(10, 25, 200, 25); // Línea horizontal
                $pdf->Ln(10);
                // Datos de la tarea
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor(33, 33, 33); // Color gris oscuro
                // Título
                $pdf->Cell(50, 10, 'Titulo:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, $tarea['titulo'], 0, 1, 'L');
                $pdf->Ln(5);
                // Descripción
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(50, 10, 'Descripcion:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(0, 10, $tarea['descripcion'], 0, 'L');
                $pdf->Ln(5);
                // Fecha de Creación
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(50, 10, 'Fecha de Creacion:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, $tarea['fCreacion'], 0, 1, 'L');
                $pdf->Ln(5);
                // Fecha Límite
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(50, 10, 'Fecha Limite:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, $tarea['fExp'], 0, 1, 'L');
                $pdf->Ln(5);
                // Estado
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(50, 10, 'Estado:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, $tarea['estado'], 0, 1, 'L');
                $pdf->Ln(5);
                // Usuario creador
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(50, 10, 'Creado por:', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, $tarea['username'], 0, 1, 'L');
                $pdf->Ln(10);
                // Footer
                $pdf->SetY(-50); // Posiciona el footer a 30px del borde inferior
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->SetTextColor(100, 100, 100); // Color gris claro
                $pdf->Cell(0, 10, 'Generado por Castareas | ' . date('d/m/Y H:i:s'), 0, 0, 'C');
                $pdf->Output();
        }
        else{
                echo "<script>
                        alert('Tarea no encontrada');
                        window.location = '../Vista/index.php';
                </script>";
                exit();
        }
        }
        else{
        echo "<script>
                alert('PArámetros invalidos');
                window.location = '../Vista/index.php';
            </script>";
        exit();
    }
?>