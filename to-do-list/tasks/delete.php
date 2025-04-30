<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php'); 
    exit;
}

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    $query = $db->prepare("DELETE FROM tasks WHERE id = :task_id");
    $query->execute(['task_id' => $task_id]);
}

header('Location: ../index.php'); 
exit;
?>