<?php
include 'config/database.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
try {
    $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ver_citas.php");
    } catch (PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
}
?>