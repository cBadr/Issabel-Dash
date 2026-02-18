<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Logger.php';
require_once __DIR__ . '/../config/config.php'; // Ensure Config is loaded if needed for logging

Auth::logout();
