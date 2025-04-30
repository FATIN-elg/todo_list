<?php
session_start();
require '../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = $_POST['list_id'];
    $list_name = $_POST['list_name'];
    $description = $_POST['description'] ?? '';

    if (!empty($list_id) && !empty($list_name)) {
        try {
            $query = $db->prepare("UPDATE lists SET titre = :list_name, description = :description WHERE id = :list_id");
            $query->execute([
                'list_name' => $list_name,
                'description' => $description,
                'list_id' => $list_id
            ]); 

            header('Location: ../index.php');
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la liste : " . $e->getMessage());
        }
    } else {
        header('Location: ../index.php?error=missing_fields');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>