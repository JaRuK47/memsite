<?php

class BaseSuperTwigController extends TwigBaseController {
    public function getContext(): array
    {
        $context = parent::getContext();
        $query = $this->pdo->query("SELECT id, name FROM types ORDER BY name");
        $types = $query->fetchAll();
        $context['types'] = $types;
        $context['history'] = $_SESSION['history'] ?? [];

        return $context;
    }
}