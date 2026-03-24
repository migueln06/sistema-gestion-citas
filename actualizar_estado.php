<?php
include 'config/database.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("UPDATE citas SET estado = 'Completado' WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ver_citas.php"); // Redirecciona automáticamente al terminar
    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}
?>'