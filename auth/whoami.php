<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
Auth::startSession();
echo json_encode(Auth::user());
