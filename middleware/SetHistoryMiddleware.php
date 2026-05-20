<?php

class SetHistoryMiddleware extends BaseMiddleware {
    public function apply(BaseController $controller, array $context) {
        $currentUrl = urldecode($_SERVER['REQUEST_URI']);;
        
        $pageTitle = $controller->title ?? 'Страница';
        
        if (!isset($_SESSION['history'])) {
            $_SESSION['history'] = [];
        }
        
        $lastItem = isset($_SESSION['history'][0]) ? $_SESSION['history'][0] : null;
        
        if ($lastItem && $lastItem['url'] === $currentUrl) {
            return;
        }
        
        array_unshift($_SESSION['history'], [
            'url' => $currentUrl,
            'title' => $pageTitle
        ]);
        
        if (count($_SESSION['history']) > 10) {
            array_pop($_SESSION['history']);
        }
    }
}