<?php
session_start();
require '../../database-config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'];

// Validate the amount
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit();
}

$link->begin_transaction();

try {
    // Update balance
    $stmt = $link->prepare('UPDATE client SET balance = balance + ? WHERE user_id = ?');
    $stmt->bind_param('di', $amount, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Balance update failed');
    }

    // Insert transaction record
    $type = 'deposit';
    $stmt = $link->prepare('INSERT INTO Transactions (user_id, type, amount) VALUES (?, ?, ?)');
    $stmt->bind_param('isd', $user_id, $type, $amount);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Transaction record insert failed');
    }

    $link->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $link->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$link->close();
?>
