<?php
session_start();
require '../../database-config/config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'];

// Validate amount
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit();
}

// Convert amount to float
$amount = floatval($amount);

// Check if user has sufficient balance
$stmt = $link->prepare('SELECT balance FROM client WHERE user_id = ?');
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    if ($stmt->fetch()) {
        if ($balance < $amount) {
            echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve balance']);
        exit();
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    exit();
}

// Begin transaction for withdrawal
$link->autocommit(false);

// Update balance
$updateStmt = $link->prepare('UPDATE client SET balance = balance - ? WHERE user_id = ?');
if ($updateStmt) {
    $updateStmt->bind_param('di', $amount, $user_id);
    $updateStmt->execute();
    if ($updateStmt->affected_rows === 0) {
        $link->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update balance']);
        exit();
    }
    $updateStmt->close();
} else {
    $link->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to prepare update statement']);
    exit();
}

// Record transaction
$type = 'withdrawal';
$insertStmt = $link->prepare('INSERT INTO Transactions (user_id, type, amount) VALUES (?, ?, ?)');
if ($insertStmt) {
    $insertStmt->bind_param('isd', $user_id, $type, $amount);
    $insertStmt->execute();
    if ($insertStmt->affected_rows === 0) {
        $link->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to record transaction']);
        exit();
    }
    $insertStmt->close();
} else {
    $link->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to prepare insert statement']);
    exit();
}

// Commit transaction
$link->commit();

// Send JSON response for successful withdrawal
$response = ['success' => true, 'message' => 'Withdrawal successful'];
echo json_encode($response);

// Redirect to dashboard.html using JavaScript after a short delay
echo '<script>
        setTimeout(function() {
            window.location.href = "dashboard.html";
        }, 2000); // Redirect after 2 seconds (adjust delay as needed)
      </script>';

$link->autocommit(true); // Restore autocommit mode
?>
