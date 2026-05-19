<?php
require_once "BaseSuperTwigController.php";

class SearchController extends BaseSuperTwigController {
    public $template = "search.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $type = $_GET['type'] ?? '';
        $title = $_GET['title'] ?? '';
        $description = $_GET['description'] ?? '';

        $typeQuery = $this->pdo->query("SELECT id, name FROM types ORDER BY name");
        $context['type_options'] = $typeQuery->fetchAll();

        $context['selected_type'] = $type;
        $context['search_title'] = $title;
        $context['search_description'] = $description;

        $sql = "SELECT id, title FROM superhero WHERE 1=1";

        if ($title !== '') {
            $sql .= " AND title LIKE CONCAT('%', :title, '%')";
        }
        if ($description !== '') {
            $sql .= " AND description LIKE CONCAT('%', :description, '%')";
        }
        if ($type !== '' && $type !== 'all') {
            $sql .= " AND type = :type";
        }

        $query = $this->pdo->prepare($sql);

        if ($title !== '') {
            $query->bindValue("title", $title);
        }
        if ($description !== '') {
            $query->bindValue("description", $description);
        }
        if ($type !== '' && $type !== 'all') {
            $query->bindValue("type", $type);
        }

        $query->execute();
        $context['objects'] = $query->fetchAll();

        return $context;
    }
}