<?php
// Configuración de la ruta de la base de datos
$db_path = __DIR__ . '/../gestion_citas.db';

try {
    // 1. Conexión con PDO
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Crear Tabla de Clientes
    $pdo->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        telefono TEXT,
        email TEXT UNIQUE
    )");

    // 3. Crear Tabla de Servicios (Tus especialidades)
    $pdo->exec("CREATE TABLE IF NOT EXISTS servicios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre_servicio TEXT NOT NULL,
        precio REAL
    )");

    // 4. Crear Tabla de Citas (La relación)
    $pdo->exec("CREATE TABLE IF NOT EXISTS citas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cliente_id INTEGER,
        servicio_id INTEGER,
        fecha DATE,
        hora TIME,
        precio_pactado REAL,
        estado TEXT DEFAULT 'Pendiente',
        FOREIGN KEY (cliente_id) REFERENCES clientes(id),
        FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    )");

    // 5. Insertar servicios base (Solo si la tabla está vacía)
    $count = $pdo->query("SELECT count(*) FROM servicios")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO servicios (nombre_servicio, precio) VALUES (?, ?)");
        $stmt->execute(['Mantenimiento PC', 20.00]);
        $stmt->execute(['Configuración Redes', 10.00]);
        $stmt->execute(['Formateo e Instalación SO', 15.00]);
    }

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>