<?php
session_start();
require '../../database-config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve balance
$stmt = $link->prepare('SELECT balance FROM client WHERE user_id = ?');
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'balance' => $balance]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve balance']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
}

?>
