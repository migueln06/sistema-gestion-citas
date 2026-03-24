<?php include 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Citas - Miguel Netti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Citas Agendadas</h2>
            <a href="index.php" class="btn btn-outline-primary">Nueva Cita</a>
        </div>

        <div class="table-responsive shadow bg-white p-3 rounded">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_ganancias = 0;
                    // 1. Asegúrate de pedir 'precio_pactado' en el SELECT
                    $sql = "SELECT ci.id as id_cita, cl.nombre, s.nombre_servicio, ci.precio_pactado, ci.fecha, ci.hora, ci.estado 
                FROM citas ci
                INNER JOIN clientes cl ON ci.cliente_id = cl.id
                INNER JOIN servicios s ON ci.servicio_id = s.id
                ORDER BY ci.fecha ASC";

                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $total_ganancias += $row['precio_pactado']; // Usamos el pactado para el total

                        echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['nombre_servicio']}</td>
                    <td>" . date("d/m/Y", strtotime($row['fecha'])) . "</td>
                    <td>{$row['hora']}</td>
                    <td>\${$row['precio_pactado']}</td> 
                    <td>
                        <span class='badge " . ($row['estado'] == 'Completado' ? 'bg-success' : 'bg-warning text-dark') . "'>
                            {$row['estado']}
                        </span>
                    </td>
                    <td>
                        <a href='actualizar_estado.php?id={$row['id_cita']}' class='btn btn-sm btn-outline-success' title='Completar'>
                <i class='bi bi-check-circle-fill'></i>
                </a>
            
                        <a href='eliminar_cita.php?id={$row['id_cita']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"¿Borrar esta cita?\")' title='Eliminar'>
                <i class='bi bi-trash3-fill'></i>
                </a>
                    </td>
                  </tr>";
                    }
                    ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total Proyectado:</strong></td>
                        <td colspan="3"><strong>$<?php echo number_format($total_ganancias, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>