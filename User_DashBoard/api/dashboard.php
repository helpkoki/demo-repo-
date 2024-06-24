<?php
session_start();
require '../../database-config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculate the balance
$stmt = $link->prepare('SELECT SUM(CASE WHEN type = "deposit" THEN amount WHEN type = "receive" THEN amount ELSE -amount END) as balance FROM Transactions WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$balance = $result->fetch_assoc()['balance'];

// Get transactions
$stmt = $link->prepare('SELECT type, amount, created_at FROM Transactions WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['balance' => $balance, 'transactions' => $transactions]);
?>
