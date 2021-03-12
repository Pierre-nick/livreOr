<?php
require_once ('class/Message.php');
require_once ('class/GuestBook.php');

$errors = null;
// on initialise la variable $guestbook comme un objet de type Guestbook, c'est ici que le dossier message est créé
$guestbook = new GuestBook(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'messages');
$success = false;

// si le formulaire a été soumis
if(isset($_POST['userName'],$_POST['userMessage'])){
    // on initialise une variable $message de type Message qui prend en paramètre les éléments retrouvé dans le $_post
    $message = new Message($_POST['userName'],$_POST['userMessage']);
    // on soumet ce nouvel objet à la fonction isValid
    if($message->isValid()){
        // si c'est valide le message est ajouté au dossier, on vide le $_POST, la variable $succes est passée à true pour gérer l'affichage
        $guestbook->addMessage($message);
        $success = true;
        $_POST = [];
    }else{
        // sinon on crée une variable $errors qui va stocker les erreurs de l'objet $message relevées par la fonction getErrors
        $errors = $message->getErrors();
    }
}
// on initialise $messages qui est un tableau des messages récupérés par getMessages
$messages = $guestbook->getMessages();
$title = "Livre d'or";
$name = null;
$message = null; 

require_once 'elements/header.php'; ?>
<div class="container">
    <h1 class="my-3">Mon livre d'or :</h1>

    <!-- cf l20 à 23 si getErrors a relevé des erreur on affiche un message d'alerte -->
    <?php if(!empty($errors)):?>
        <div class="alert alert-danger">
            Formulaire invalide!
        </div>
    <?php endif;?>
    <!-- cf l15 à 19 si le formulaire est correct on affiche un remerciement -->
    <?php if($success):?>
        <div class="alert alert-success">
            Merci pour votre message!
        </div>
    <?php endif;?>

    <form action="" method="POST" id="formLivreOr" class="form-group">
        <div class="form-group">
            <!-- la value sert à persister les informations du formulaires
            si il y a une erreur la classe is-invalid s'applique cf condition ternaire-->
            <input value="<?= htmlentities($_POST['userName'] ?? '') ?>" type="text" name="userName" placeholder="nom d'utilisateur" 
            class="form-control <?= isset($errors['userName']) ? 'is-invalid' : ''?>">
            <!-- affichage d'une div supplémentaire en cas d'erreur -->
            <?php if(isset($errors['userName'])): ?>
                <div class="invalid-feedback">
                    <?= $errors['userName'] ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <textarea name="userMessage" form="formLivreOr" cols="30" rows="10" placeholder="entrez votre message ici" class="form-control <?= isset($errors['message']) ? 'is-invalid' : ''?>"><?= htmlentities($_POST['userMessage'] ?? '') ?></textarea>
            <?php if(isset($errors['message'])): ?>
                <div class="invalid-feedback">
                    <?= $errors['message'] ?>
                </div>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary" type="submit">Soumettre</button>
    </form>
    <!-- si le tableau des messages n'est pas vide alors on affiche: -->
    <?php if(!empty($messages)): ?>
        <h1>Vos messages :</h1>

        <?php foreach($messages as $message): ?>
            <!-- on converti chaque message du tableau en html pour qu'il s'affiche -->
            <?= $message->toHTML() ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once 'elements/footer.php'; ?>