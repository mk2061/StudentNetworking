<?php
require_once '../config/database.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    exit();
}

if (isset($_POST['query']) && strlen($_POST['query']) > 2) {
    $query = '%' . sanitize($_POST['query']) . '%';
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT id, full_name, profile_pic, major, university 
            FROM users 
            WHERE (full_name LIKE ? OR major LIKE ? OR university LIKE ?) 
            AND id != ? 
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $query, $query, $query, $user_id);
    $stmt->execute();
    $results = $stmt->get_result();
    
    if ($results->num_rows > 0) {
        while($user = $results->fetch_assoc()) {
            echo '<div class="d-flex gap-3 align-items-center p-2 border-bottom">
                    <img src="' . SITE_URL . 'assets/uploads/' . $user['profile_pic'] . '" 
                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <a href="' . SITE_URL . 'modules/profile/view.php?id=' . $user['id'] . '" class="text-decoration-none">
                            <strong>' . htmlspecialchars($user['full_name']) . '</strong><br>
                            <small class="text-muted">' . htmlspecialchars($user['major']) . ' • ' . htmlspecialchars($user['university']) . '</small>
                        </a>
                    </div>
                </div>';
        }
    } else {
        echo '<p class="text-center text-muted py-3">No results found</p>';
    }
}
?>