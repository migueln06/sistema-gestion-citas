<!-- <?php
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capturamos TODAS las variables del formulario
    $nombre = $_POST['nombre'];
    $servicio_id = $_POST['servicio_id'];
    $precio_pactado = $_POST['precio_final']; // Capturamos el precio modificado
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    try {
        $pdo->beginTransaction();

        // 1. VERIFICAR SI EL CLIENTE YA EXISTE
        $stmtCheck = $pdo->prepare("SELECT id FROM clientes WHERE nombre = ?");
        $stmtCheck->execute([$cliente_id, $fecha, $hora]);
        $cliente_existente = $stmtCheck->fetch();

        if ($cliente_existente) {
            // Si ya existe, tomamos su ID para la cita
            $cliente_id = $cliente_existente['id'];
        } else {
            // Si no existe, lo creamos por primera vez
            $stmtCliente = $pdo->prepare("INSERT INTO clientes (nombre) VALUES (?)");
            $stmtCliente->execute([$nombre]);
            $cliente_id = $pdo->lastInsertId();
        }

        // 2. INSERTAR LA CITA (Esta parte ya la tienes, asegúrate de que use el $cliente_id correcto)
        $stmtCita = $pdo->prepare("INSERT INTO citas (cliente_id, servicio_id, fecha, hora, precio_pactado) VALUES (?, ?, ?, ?, ?)");
        $stmtCita->execute([$cliente_id, $servicio_id, $fecha, $hora, $precio_pactado]);

        $pdo->commit();

        echo "<div style='text-align:center; margin-top:50px; font-family: sans-serif;'>
                <h2 style='color: green;'>✅ ¡Cita registrada con éxito!</h2>
                <p>El cliente <b>$nombre</b> ha sido agendado por un valor de <b>$$precio_pactado</b>.</p>
                <a href='index.php'>Volver al formulario</a> | <a href='ver_citas.php'>Ver listado</a>
              </div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al guardar: " . $e->getMessage();
    }
}
?> -->

<?php
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos asegurándonos de que existan
    $nombre = $_POST['nombre'] ?? '';
    $servicio_id = $_POST['servicio_id'] ?? '';
    $precio_pactado = $_POST['precio_final'] ?? 0;
    $fecha = $_POST['fecha'] ?? ''; 
    $hora = $_POST['hora'] ?? '';

    // Si falta fecha o hora, detenemos para no guardar datos vacíos
    if (empty($fecha) || empty($hora)) {
        die("Error: La fecha y la hora son obligatorias.");
    }

    try {
        $pdo->beginTransaction();

        // 1. VERIFICAR SI EL CLIENTE YA EXISTE
        $stmtCheck = $pdo->prepare("SELECT id FROM clientes WHERE nombre = ?");
        $stmtCheck->execute([$nombre]);
        $cliente_existente = $stmtCheck->fetch();

        if ($cliente_existente) {
            $cliente_id = $cliente_existente['id'];
        } else {
            $stmtCliente = $pdo->prepare("INSERT INTO clientes (nombre) VALUES (?)");
            $stmtCliente->execute([$nombre]);
            $cliente_id = $pdo->lastInsertId();
        }

        // 2. NUEVA VALIDACIÓN: VERIFICAR SI LA CITA YA EXISTE
        // Buscamos si el mismo cliente ya tiene algo a esa misma hora y fecha
        $stmtCheckCita = $pdo->prepare("SELECT id FROM citas WHERE cliente_id = ? AND fecha = ? AND hora = ?");
        $stmtCheckCita->execute([$cliente_id, $fecha, $hora]);

        if ($stmtCheckCita->fetch()) {
            // Si encuentra algo, cancelamos todo y avisamos al usuario
            $pdo->rollBack();
            echo "<div style='text-align:center; margin-top:50px; font-family: sans-serif;'>
                    <h2 style='color: orange;'>⚠️ Cita Duplicada</h2>
                    <p>El cliente <b>$nombre</b> ya tiene una cita agendada para el <b>$fecha</b> a las <b>$hora</b>.</p>
                    <a href='index.php'>Regresar e intentar otra hora</a>
                  </div>";
            exit; // Detenemos la ejecución aquí
        }

        // 3. SI TODO ESTÁ BIEN, INSERTAMOS LA CITA
        $stmtCita = $pdo->prepare("INSERT INTO citas (cliente_id, servicio_id, fecha, hora, precio_pactado) VALUES (?, ?, ?, ?, ?)");
        $stmtCita->execute([$cliente_id, $servicio_id, $fecha, $hora, $precio_pactado]);

        $pdo->commit();

        echo "<div style='text-align:center; margin-top:50px; font-family: sans-serif;'>
                <h2 style='color: green;'>✅ ¡Cita registrada con éxito!</h2>
                <p>Cita para <b>$nombre</b> guardada correctamente.</p>
                <a href='index.php'>Nueva Cita</a> | <a href='ver_citas.php'>Ver listado</a>
              </div>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error crítico: " . $e->getMessage();
    }
}