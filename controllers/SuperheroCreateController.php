<?php
require_once "BaseSuperTwigController.php";

class SuperheroCreateController extends BaseSuperTwigController {
    public $template = "superhero_create.twig";

    public function get(array $context)
    {
        // Получаем список типов для выпадающего списка
        $query = $this->pdo->query("SELECT id, name FROM types ORDER BY name");
        $context['type_options'] = $query->fetchAll();

        parent::get($context);
    }

    public function post(array $context)
    {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type']; 
        $info = $_POST['info'] ?? '';

        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, "../public/media/$name");
            $image_url = "/media/$name";
        }

        $sql = "INSERT INTO superhero (title, description, type, info, image) VALUES (:title, :description, :type, :info, :image)";
        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image", $image_url);
        $query->execute();

        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId();

        $this->get($context);
    }
}