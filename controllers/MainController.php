<?php
require_once "BaseSuperTwigController.php";

class MainController extends BaseSuperTwigController {
    public $template = "main.twig";
    public $title = "Главная";

    public function getContext(): array
    {
        $context = parent::getContext();

        if (isset($_GET['type'])) {
            $query = $this->pdo->prepare("SELECT * FROM superhero WHERE type = :type");
            $query->bindValue("type", $_GET['type']);
        } else {
            $query = $this->pdo->query("SELECT * FROM superhero");
        }
        $query->execute();
        $context['superhero'] = $query->fetchAll();

        return $context;
    }
}