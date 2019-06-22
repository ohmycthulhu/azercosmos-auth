<?php
    return \App\Helper\Helper::checkHash($_SERVER['HTTP_AUTHORIZATION'] ?? '');
