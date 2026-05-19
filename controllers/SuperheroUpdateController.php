<?php
require_once "BaseSuperTwigController.php";

class SuperheroUpdateController extends BaseSuperTwigController {
    public $template = "update.twig";

    public function get(array $context)
    {
        $id = $this->params['id'] ?? null;
        
        if ($id) {
            $query = $this->pdo->prepare("SELECT * FROM superhero WHERE id = :id");
            $query->execute(['id' => $id]);
            $context['object'] = $query->fetch();
            
            $types_query = $this->pdo->query("SELECT name FROM types ORDER BY name");
            $context['type_options'] = $types_query->fetchAll();
        }
        
        parent::get($context);
    }

    public function post(array $context)
    {
        $id = $this->params['id'] ?? null;
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];
        
        $image_url = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, "../public/media/$name");
            $image_url = "/media/$name";
        }
        
        if ($image_url) {
            $sql = "UPDATE superhero SET title = :title, description = :description, type = :type, info = :info, image = :image WHERE id = :id";
            $query = $this->pdo->prepare($sql);
            $query->bindValue("image", $image_url);
        } else {
            $sql = "UPDATE superhero SET title = :title, description = :description, type = :type, info = :info WHERE id = :id";
            $query = $this->pdo->prepare($sql);
        }
        
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("id", $id);
        $query->execute();
        
        header("Location: /superhero/" . $id);
        exit();
    }
}