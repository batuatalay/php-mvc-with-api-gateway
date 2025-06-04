<?php

class SimpleController {
    protected static function return($code, $message) {
        return json_encode(['code' => $code, 'message' => $message]);
    }

    protected static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected static function view($viewName, $data = []) {
        if (file_exists(BASE . '/view/' . $viewName . '.php')) {
            extract($data);
            require_once BASE . '/view/' . $viewName . '.php';
        } else {
            throw new Exception("View not found: " . $viewName);
        }
    }

    protected static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 