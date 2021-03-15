<?php
require_once('Message.php');
class GuestBook{

    private $file;
    
    // le constructeur va servir a créer le fichier $file si il n'existe pas
    public function __construct(string $file)
    {
        // on initialise $directory qui est le dossier (dirname) ou se trouve $file
        $directory = dirname($file);
        // si $directory n'existe pas on le crée avec mkdir
        if(!is_dir($directory)){
            mkdir($directory,0777,true);
        }
        // si $file n'existe pas on le crée avec touch
        if(!file_exists($file)){
            touch($file);
        }
        $this->file = $file;
    }

    // on passe en parametre a la fonction addMessage un objet de type Message
    public function addMessage(Message $message): void
    {
        // cet objet est converti au format JSON (toJSON) et inséré au fichier $file
        // PHP_EOL implique un retour chariot apres chaque ajout d'un nouvel objet
        // FILE_APPEND implique que l'objet est AJOUTE a la fin du fichier et non pas écrit par dessus en ECRASANT les données
        file_put_contents($this->file, $message->toJSON().PHP_EOL, FILE_APPEND);
        //var_dump($message);
    }

    // getMessages sert a récupérer les messages du fichier message
    public function getMessages(): array
    {
        // on récupère le contenu du fichier dans la variable $content
        $content = trim(file_get_contents($this->file));
        //var_dump($content);
        // $lines est un tableau dont chaque élément est une ligne du fichier, on a séparé les éléments grace a explode(PHP_EOL)
        $lines = explode(PHP_EOL,$content);
        //var_dump($lines);
        // on initialise un nouveau tableau vide
        $messages = [];
        foreach($lines as $line){
            //var_dump(Message::fromJSON($line));
            // pour chaque élément du tableau $lines on lui applique la méthode fromJSon pour le convertir puis on l'ajoute au tableau $messages
            $messages[] = Message::fromJSON($line);
        }
        // on inverse l'ordre du tableau avant de le retourner pour afficher les messages du plus récent au plus ancien
        //var_dump(array_reverse($messages));
        return array_reverse($messages);
    }
}