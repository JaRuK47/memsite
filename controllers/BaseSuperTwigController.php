<?php

class BaseSuperTwigController extends TwigBaseController {
    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->query("SELECT name as type FROM types ORDER BY 1");
        $types = $query->fetchAll();
        $context['types'] = $types;
        $context['history'] = $_SESSION['history'];

        return $context;
    }
}