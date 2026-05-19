<?php

class ObjectController extends BaseSuperTwigController {
    public $template = "__object.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->prepare("SELECT id, title, description, image, type FROM superhero WHERE id = :id");
        $query->execute(['id' => $this->params['id']]);
        $data = $query->fetch();

        $context['title'] = $data['title'];
        $context['description'] = $data['description'];
        $context['image'] = $data['image'];
        $context['base_url'] = '/superhero/' . $data['id'];
        $context['is_image'] = false;
        $context['is_info'] = false;

        if (isset($_GET['show'])) {
            $show = $_GET['show'];
            if ($show === 'image') {
                $context['is_image'] = true;
                $context['image'] = $data['image'];
            } elseif ($show === 'info') {
                $context['is_info'] = true;
                $context['info'] = $data['description'];
            }
        }

        $context["messages"] = $_SESSION['messages'] ?? "";
        $context["my_session_message"] = $_SESSION['welcome_message'] ?? "";

        return $context;
    }

    public function get(array $context)
    {
        if (isset($_GET['show'])) {
            if ($_GET['show'] === 'image') {
                $this->template = "image.twig";
            } elseif ($_GET['show'] === 'info') {
                $this->template = "info.twig";
            }
        }
        parent::get($context);
    }
}