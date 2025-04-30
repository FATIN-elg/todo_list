<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $list_id = isset($_GET['id']) ? $_GET['id'] : '';

    if (!empty($list_id)) {
        $query = $db->prepare("DELETE FROM lists WHERE id = :id");
        
        try {
            $deleteTasks = $db->prepare("DELETE FROM tasks WHERE list_id = :list_id");
            $deleteTasks->execute(['list_id' => $list_id]);
            $query->execute(['id' => $list_id]);
        } catch (PDOException $e) {
            die("Erreur lors de la suppression de la liste : " . $e->getMessage());
        }
    }
    header('Location: ../index.php');
    exit;
}      
?>