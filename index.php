<?php include 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema de Citas - Miguel Netti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Agendar Nueva Cita Técnica</h2>

        <div class="card shadow p-4">
            <form action="guardar_cita.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del Cliente</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Hora</label>
                    <input type="time" name="hora" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Servicio</label>
                    <select name="servicio_id" id="select_servicio" class="form-select" onchange="actualizarPrecio()">
                        <option value="" data-precio="0">-- Selecciona un servicio --</option>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM servicios");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Guardamos el precio en un atributo 'data-precio'
                            echo "<option value='{$row['id']}' data-precio='{$row['precio']}'>{$row['nombre_servicio']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio del Servicio ($)</label>
                    <input type="number" step="0.01" name="precio_final" id="precio_final" class="form-control" required>
                </div>

                <script>
                    function actualizarPrecio() {
                        const select = document.getElementById('select_servicio');
                        const precioInput = document.getElementById('precio_final');
                        // Obtenemos el precio del atributo data del servicio seleccionado
                        const precioSugerido = select.options[select.selectedIndex].getAttribute('data-precio');
                        precioInput.value = precioSugerido;
                    }
                </script>

                <button type="submit" class="btn btn-primary w-100">Registrar Cita</button>
            </form>
        </div>
    </div>
</body>

</html>