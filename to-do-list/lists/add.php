<?php
session_start();
require '../config/database.php';
        
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_name = isset($_POST['list_name']) ? trim($_POST['list_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $created_at = date('Y-m-d H:i:s');  
         
    if (!empty($list_name)) {
        $query = $db->prepare("INSERT INTO lists (titre, description, created_at, user_id) 
                              VALUES (:titre, :description, :created_at, :user_id)");
        try {
            $query->execute([
                'titre' => $list_name,
                'description' => $description,
                'created_at' => $created_at,
                'user_id' => $_SESSION['user_id']
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion de la liste : " . $e->getMessage());
        }
    } else {
        die("Nom de la liste non defini.");
    }
}

header('Location: ../index.php');
exit;