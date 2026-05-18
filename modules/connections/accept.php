<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    echo 'error';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = intval($_POST['request_id']);
    
    if (acceptConnectionRequest($request_id)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>