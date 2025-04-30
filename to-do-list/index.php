<?php 
require 'config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// is done
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_done'], $_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    try {
        $stmt = $db->prepare("UPDATE tasks SET is_done = :is_done WHERE id = :id");
        $stmt->execute([
            'is_done' => $is_done,
            'id' => $task_id
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour de l'état de la tâche : " . $e->getMessage());
    }
}
try {
    $query = $db->prepare("SELECT * FROM lists WHERE user_id = :user_id");
    $query->execute(['user_id' => $user_id]);
    $lists = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des listes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
<body>
      <?php include 'includes/header.php'; ?>
      <div class="add-list">
        <h1>my To-Do List</h1>
        <form action="lists/add.php" method="POST" class="add-form">
            <input type="text" name="list_name" placeholder="Nouvelle liste" required>
            <input type="text" name="description" placeholder="Description (optionnel)">
            <button type="submit" title="Ajouter Liste"><i class="fas fa-plus-circle"></i></button>
        </form>
    </div>
    <div class="container">
        
   
    <?php foreach ($lists as $list): ?>
            <div class="list">
                <form action="lists/update.php" method="POST" class="update-form">
                    <input type="hidden" name="list_id" value="<?= htmlspecialchars($list['id']) ?>">
                    <input type="text" name="list_name" value="<?= htmlspecialchars($list['titre']) ?>" required>
                    <input type="text" name="description" value="<?= htmlspecialchars($list['description'] ?? '') ?>">
                    <button type="submit" class="update-list-btn" title="Modifier Liste"><i class="fas fa-edit"></i></button>
                </form>

                <p class="list-title"><?= htmlspecialchars($list['titre']) ?></p>
                <p class="list-description"><?= htmlspecialchars($list['description'] ?? '') ?></p>
                <p>Créé le : <?= htmlspecialchars(date('d/m/Y H:i', strtotime($list['created_at']))) ?></p>
                
                <form action="tasks/add.php" method="POST" class="add-form">
                    <input type="hidden" name="list_id" value="<?= htmlspecialchars($list['id']) ?>">
                    <input type="text" name="task_name" placeholder="Nouvelle tâche" required>
                    <button type="submit" title="Ajouter Tâche"><i class="fas fa-plus"></i></button>
                </form>

                <a href="lists/delete.php?id=<?= htmlspecialchars($list['id']) ?>" class="delete-list-btn" title="Supprimer Liste">
                    <i class="fas fa-trash-alt"></i>
                </a>

                <ul class="task-list">
                    <?php
                    try {
                        $query = $db->prepare("SELECT * FROM tasks WHERE list_id = :list_id");
                        $query->execute(['list_id' => $list['id']]);
                        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Erreur lors de la récupération des tâches : " . $e->getMessage());
                    }
                    ?>
                    <?php foreach ($tasks as $task): ?>
                        <li>
                            <div class="task-content">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
                                    <input type="hidden" name="toggle_done" value="1">
                                    <input type="checkbox" name="is_done" onchange="this.form.submit()" <?= $task['is_done'] ? 'checked' : '' ?>>
                                </form>

                                <form action="tasks/update.php" method="POST" class="update-form" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
                                    <input type="text" name="task_name" value="<?= htmlspecialchars($task['titre']) ?>"
                                           <?= $task['is_done'] ? 'style="text-decoration: line-through; color: gray;"' : '' ?> required>
                                    <div class="task-actions">
                                        <select name="status" class="task-status">
                                            <option value="En cours" <?= $task['status'] == 'En cours' ? 'selected' : '' ?>>En cours</option>
                                            <option value="Terminé" <?= $task['status'] == 'Terminé' ? 'selected' : '' ?>>Terminé</option>
                                        </select>
                                        <div class="button-group">
                                            <button type="submit" class="update-task-btn" title="Modifier Tâche"><i class="fas fa-edit"></i></button>
                                            <button type="submit" class="delete-task-btn" formaction="tasks/delete.php" title="Supprimer Tâche"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <span class="task-date">Créé le : <?= htmlspecialchars(date('d/m/Y H:i', strtotime($task['created_at']))) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
           
            </div>
           
        <?php endforeach; ?>
        </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
