<header>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?></span>
            <a href="auth/logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="auth/login.php">Connexion</a>
            <a href="auth/register.php">Inscription</a>
        <?php endif; ?>
    </nav> 
</header>