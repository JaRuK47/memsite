<?php
require_once "BaseSuperTwigController.php";

class TypeAddController extends BaseSuperTwigController {
    public $template = "type_add.twig";

    public function get(array $context)
    {
        parent::get($context);
    }

    public function post(array $context) {
        $name = $_POST['name'];
        
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, "../public/media/$filename");
            $image_url = "/media/$filename";
        }

        $sql = "INSERT INTO types(name, image) VALUES(:name, :image)";
        $query = $this->pdo->prepare($sql);
        $query->bindValue("name", $name);
        $query->bindValue("image", $image_url);
        $query->execute();
        
        $context['message'] = "Тип '$name' успешно добавлен";
        $this->get($context);
    }
}