<?php
require_once ('class/Message.php');
require_once ('class/GuestBook.php');

$errors = null;
$guestbook = new GuestBook(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'messages');
$success = false;

if(isset($_POST['userName'],$_POST['userMessage'])){
    $message = new Message($_POST['userName'],$_POST['userMessage']);
    if($message->isValid()){
        $guestbook->addMessage($message);
        $success = true;
        $_POST = [];
    }else{
        $errors = $message->getErrors();
    }
}
$messages = $guestbook->getMessages();
$title = "Livre d'or";
$name = null;
$message = null; 

require_once 'elements/header.php'; ?>
<div class="container">
    <h1 class="my-3">Mon livre d'or :</h1>

    <?php if(!empty($errors)):?>
        <div class="alert alert-danger">
            Formulaire invalide!
        </div>
    <?php endif;?>
    <?php if($success):?>
        <div class="alert alert-success">
            Merci pour votre message!
        </div>
    <?php endif;?>

    <form action="" method="POST" id="formLivreOr" class="form-group">
        <div class="form-group">
            <input value="<?= htmlentities($_POST['userName'] ?? '') ?>" type="text" name="userName" placeholder="nom d'utilisateur" class="form-control <?= isset($errors['userName']) ? 'is-invalid' : ''?>">
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
    <?php if(!empty($messages)): ?>
        <h1>Vos messages :</h1>

        <?php foreach($messages as $message): ?>
            <?= $message->toHTML() ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once 'elements/footer.php'; ?>