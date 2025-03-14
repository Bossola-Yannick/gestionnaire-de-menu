<?php
require '../config.php';
session_start();
$host = $_ENV['BDD_URL'];
$username = $_ENV['BDD_USERNAME'];
$password = $_ENV['BDD_PASSWORD'];

// CONNEXION à la base de donnée
try {
    $bdd  = new PDO("mysql:host=$host;dbname=gestionnaire_de_menu;charset=utf8", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);
        $req = $bdd->prepare("SELECT * FROM utilisateur WHERE mail = :email AND password = :password");
        $req->execute([
            "email" => $email,
            "password" => $password
        ]);
        $req = $req->fetch(PDO::FETCH_ASSOC);

        if (empty($req)) {
            echo '<p class="alert">Email ou mot de passe incorrect !</p>';
        } else {
            session_start();
            $_SESSION['user'] = $req;
            header("location:gestionPlat.php");
        }
    } else {
        echo '<p class="alert">Veuillez remplir tous les champs</p>';
    }
}

?>

<?php include '../composents/navbar_admin.php'; ?>
<main class="gestion-plat">
    <section class="food-management add-food">
        <!-- si une session est déjà ouverte on ne propose pas de se reconnecter -->
        <?php if (isset($_SESSION['user'])) : ?>
            <?php header("location:gestionPlat.php"); ?>
            <!-- si pas de session ouverte on propose de se connecter -->
        <?php else : ?>
            <h1 class="titre">Connexion</h1>
            <section class="bloc">
                <form method="post" action="" class="form">
                    <input class="input" type="email" name="email" id="email" value="" placeholder="Entrez votre email" required><br /><br />
                    <input class="input" type="password" name="password" id="password" value="" placeholder="Entrez votre mot de passe" required><br /><br />
                    <button type="submit" name="submit" class="bouton">Valider</button>
                </form>
            </section>
        <?php endif ?>
    </section>
</main>
<?php
include '../composents/footer_admin.php';
?>