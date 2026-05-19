<?php

class ObjectController extends BaseSuperTwigController {
    public $template = "__object.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        
        $query = $this->pdo->prepare("SELECT image, description, id, title FROM superhero WHERE id = :id");
        $query->execute(['id' => $this->params['id']]);
        $data = $query->fetch();
        
        $context['description'] = $data['description'];
        $context['id'] = $data['id'];
        $context['title'] = $data['title'];
        $this->title = $data['title'];
        $context['base_url'] = '/superhero/' . $data['id'];
        $context['is_image'] = false;
        $context['is_info'] = false;

        if (isset($_GET['show'])) {
            $show = $_GET['show'];
            if ($show === 'image') {     
                $context['image'] = $data['image'];
                $context['is_image'] = true;
            } elseif ($show === 'info') {  
                $context['info'] = $data['description'];
                $context['is_info'] = true;
            }
        } 

        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";
        $context["my_session_message"] = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : "";

        return $context;
    }

    public function get(array $context)
    {
        if (isset($_GET['show'])) {
            if ($_GET['show'] === 'image') {
                $this->template = "image.twig";
            } elseif ($_GET['show'] === 'info') {
                $this->template = "info.twig";
            } else {
                $this->template = "__object.twig";
            }
        } 

        parent::get($context);
    }
}