<?php
require '../config/database.php';
session_start(); 
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$errors = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if (empty($nom)) {
        $errors['nom'] = "Le champ 'Nom' est requis.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ' -]{2,30}$/", $nom)) {
        $errors['nom'] = "Le nom doit contenir uniquement des lettres (2 à 30 caractères).";
    }


    if (empty($prenom)) {
        $errors['prenom'] = "Le champ 'Prénom' est requis.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ' -]{2,30}$/", $prenom)) {
        $errors['prenom'] = "Le prénom doit contenir uniquement des lettres (2 à 30 caractères).";
    }

    if (empty($email)) {
        $errors['email'] = "Le champ 'Email' est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide.";
    } else {
        $query = $db->prepare("SELECT * FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        if ($query->rowCount() > 0) {
            $errors['email'] = "Un compte avec cet email existe déjà.";
        }
    }

    if (empty($telephone)) {
        $errors['telephone'] = "Le champ 'Téléphone' est requis.";
    } elseif (!preg_match("/^\+?[0-9]{9,15}$/", $telephone)) {
        $errors['telephone'] = "Le numéro de téléphone n'est pas valide.";
    }

    if (empty($password)) {
        $errors['password'] = "Le champ 'Mot de passe' est requis.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = "Le mot de passe doit contenir au moins une majuscule.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = "Le mot de passe doit contenir au moins un chiffre.";
    }

 
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
    }

  
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = $db->prepare("INSERT INTO users (nom, prenom, email, telephone, password) VALUES (:nom, :prenom, :email, :telephone, :password)");
        $query->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'password' => $hashed_password,
        ]);

        header('Location: ../auth/login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="cont">
        <h1>Inscription</h1>
        <form method="POST">
            <div>
                <input type="text" name="nom" placeholder="Nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" >
                <small class="error"><?= $errors['nom'] ?? '' ?></small>
            </div>
            <div>
                <input type="text" name="prenom" placeholder="Prénom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" >
                <small class="error"><?= $errors['prenom'] ?? '' ?></small>
            </div>
            <div>
                <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" >
                <small class="error"><?= $errors['email'] ?? '' ?></small>
            </div>
            <div>
                <input type="text" name="telephone" placeholder="Téléphone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" >
                <small class="error"><?= $errors['telephone'] ?? '' ?></small>
            </div>
            <div>
                <input type="password" name="password" placeholder="Mot de passe" >
                <small class="error"><?= $errors['password'] ?? '' ?></small>
            </div>
            <div>
                <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" >
                <small class="error"><?= $errors['confirm_password'] ?? '' ?></small>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
    </div>
</body>
</html>
