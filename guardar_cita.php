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

        // 2. Insertamos al cliente
        $stmtCliente = $pdo->prepare("INSERT INTO clientes (nombre) VALUES (?)");
        $stmtCliente->execute([$nombre]);
        $cliente_id = $pdo->lastInsertId();

        // 3. Insertamos la cita incluyendo el precio_pactado
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

        $stmtCliente = $pdo->prepare("INSERT INTO clientes (nombre) VALUES (?)");
        $stmtCliente->execute([$nombre]);
        $cliente_id = $pdo->lastInsertId();

        $stmtCita = $pdo->prepare("INSERT INTO citas (cliente_id, servicio_id, fecha, hora, precio_pactado) VALUES (?, ?, ?, ?, ?)");
        $stmtCita->execute([$cliente_id, $servicio_id, $fecha, $hora, $precio_pactado]);

        $pdo->commit();
        echo "✅ Cita registrada con éxito. <a href='ver_citas.php'>Ver listado</a>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
