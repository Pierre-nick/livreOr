<?php
class Message{

    // on initialise les constantes nécessaires à la vérification des infos du formulaire
    const LIMIT_USERNAME = 3;
    const LIMIT_MESSAGE = 10;

    // on initialise les attributs qu'on va passer en paramètres dans le constructeur
    private $username;
    private $message;
    private $date;

    // fromJSON converti une ligne au format json en objet de classe Message
    public static function fromJSON(string $json): Message
    {
        $data = json_decode($json,true);
        // 'self' équivaut à Message sauf qu'on peut désormais changer le nom de la classe sans avoir de modif à faire ici
        return new self($data['userName'], $data['message'],new DateTime("@".$data['date']));
    }

    // le constructeur prend 3 paramètre, la date peut etre soit nulle soit un objet DateTime
    public function __construct(string $username, string $message, ?DateTime $date = null)
    {
        $this->username = $username;
        $this->message = $message;
        // si la date existe alors c'est un objet DateTime
        $this->date = $date ?: new DateTime();
    }

    // cf d'abord getErrors()
    // cette fonction permet de vérifier si le formulaire est valid en renvoyant "true" quand la fonction getErrors n'a pas relevé d'erreurs
    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    // cette fonction renvoi un tableau d'erreurs
    public function getErrors(): array
    {
        // on initialise un tableau vide
        $errors = [];
        // si la taille de la string username < la constante initialisée en haut de page, alors on ajoute un message d'index 'username' au tableau
        if(strlen($this->username) < self::LIMIT_USERNAME ){
            $errors['userName'] = 'votre pseudo est trop court!';
        }
        // si la taille de la string message < la constante initialisée en haut de page, alors on ajoute un message d'index 'message' au tableau
        if(strlen($this->message) < self::LIMIT_MESSAGE){
            $errors['message'] = 'votre message est trop court!';
        }
        // on renvoi le tableau
        return $errors;
    }

    // toHTML() converti en langage html le formulaire soumis par l'utilisateur
    public function toHTML(): string
    {
        // on soumet $this->username à l'htmlentities pour éviter les caractères dangereux ou malveillants
        $username = htmlentities($this->username);
        // par défaut le fuseau horaire du DateTime n'est pas le notre donc on converti this->date
        $this->date->setTimezone(new DateTimeZone('Europe/Paris'));
        // on donne a notre date le format JJ/MM/YY HH:MM
        $date = $this->date->format('d/m/Y à H:i' );
        // on soumet également le message a l'htmlentities, le nl2br sert a conserver les sauts de lignes du message
        $message = nl2br(htmlentities($this->message));

        return "<p><strong>$username</strong><em> le $date </em><br>'$message'</p>";
    }

    // on encode les données recues du formulaire en format json() ca servira a les insérer dans le fichier 'messages'
    public function toJSON(): string
    {
        return json_encode([
            'userName' => $this->username,
            'message' => $this->message,
            'date' => $this->date->getTimestamp()
        ]);
    }

    
}