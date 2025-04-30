<?php 
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    if (!empty($task_id) && !empty($task_name)) {
        try {
            $query = $db->prepare("UPDATE tasks SET titre = :task_name WHERE id = :task_id");
            $query->execute([
                'task_name' => $task_name,
                'task_id' => $task_id
            ]);
            header('Location: ../index.php');
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la tâche : " . $e->getMessage());
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