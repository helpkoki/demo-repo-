<?php
session_start();
require '../../database-config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$recipient_email = $_POST['recipient'];
$amount = $_POST['amount'];

try {
    $link->begin_transaction();

    // Get recipient's user_id from UserDetails table
    $stmt = $link->prepare('SELECT id FROM UserDetails WHERE emailAddress = ?');
    $stmt->bind_param('s', $recipient_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipient = $result->fetch_assoc();

    if (!$recipient) {
        throw new Exception('Recipient not found: ' . $recipient_email);
    }

    $recipient_id = $recipient['id'];

    // Update sender's balance in client table
    $stmt = $link->prepare('UPDATE client SET balance = balance - ? WHERE user_id = ? AND balance >= ?');
    $stmt->bind_param('dii', $amount, $user_id, $amount); // Bind parameters by reference
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        throw new Exception('Insufficient funds');
    }

    // Update recipient's balance in client table
    $stmt = $link->prepare('UPDATE client SET balance = balance + ? WHERE user_id = ?');
    $stmt->bind_param('di', $amount, $recipient_id); // Bind parameters by reference
    $stmt->execute();

    // Log transactions
    $type_send = 'send';
    $type_receive = 'receive';
    $stmt = $link->prepare('INSERT INTO Transactions (user_id, type, amount) VALUES (?, ?, ?), (?, ?, ?)');
    $stmt->bind_param('isdisd', $user_id, $type_send, $amount, $recipient_id, $type_receive, $amount); // Bind parameters by reference
    $stmt->execute();

    $link->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $link->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}
?>
