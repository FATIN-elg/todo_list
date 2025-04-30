<?php
session_start(); 
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = isset($_POST['task_name']) ? trim($_POST['task_name']) : '';
    $list_id = isset($_POST['list_id']) ? $_POST['list_id'] : '';
    $status = 'En cours';
    $created_at = date('Y-m-d H:i:s');
    $is_done = 0; // Default value for new tasks

    if (!empty($task_name) && !empty($list_id)) {
        $query = $db->prepare("INSERT INTO tasks (titre, list_id, status, created_at, is_done) 
                              VALUES (:titre, :list_id, :status, :created_at, :is_done)");

        try {
            $query->execute([
                'titre' => $task_name, 
                'list_id' => $list_id,
                'status' => $status,
                'created_at' => $created_at,
                'is_done' => $is_done
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion de la tâche : " . $e->getMessage());
        }
    } else {
        die("Le nom de la tâche ou l'ID de la liste n'est pas défini.");
    }
}
header('Location: ../index.php');
exit;
?>