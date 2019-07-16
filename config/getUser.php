<?php
    if (isset($_SESSION['id'])) {
        return $_SESSION['id'];
    }
    return \App\Helper\Helper::checkHash($_SERVER['HTTP_AUTHORIZATION'] ?? '');
