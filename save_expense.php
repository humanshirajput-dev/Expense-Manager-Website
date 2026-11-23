<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: addexpense.php");
    exit();
}

$title = trim($_POST["title"] ?? "");
$amount = trim($_POST["amount"] ?? "");
$category = trim($_POST["category"] ?? "");
$expense_date = trim($_POST["expense_date"] ?? "");
$payment_mode = trim($_POST["payment_mode"] ?? "UPI");
$notes = trim($_POST["notes"] ?? "");

if ($title === "" || $amount === "" || !is_numeric($amount) || (float)$amount <= 0 || $category === "" || $expense_date === "") {
    header("Location: addexpense.php?status=error&msg=" . urlencode("Please fill all required fields with a valid amount."));
    exit();
}

$amount_val = (float)$amount;
$payment_mode_val = ($payment_mode === "") ? null : $payment_mode;
$notes_val = ($notes === "") ? null : $notes;

$stmt = $conn->prepare(
    "INSERT INTO expenses (title, amount, category, expense_date, payment_mode, notes) VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    header("Location: addexpense.php?status=error&msg=" . urlencode("Database query error."));
    exit();
}

$stmt->bind_param("sdssss", $title, $amount_val, $category, $expense_date, $payment_mode_val, $notes_val);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: addexpense.php?status=success&msg=" . urlencode("Expense saved to database successfully!"));
    exit();
} else {
    $err = $stmt->error;
    $stmt->close();
    $conn->close();
    header("Location: addexpense.php?status=error&msg=" . urlencode("Failed to save expense: " . $err));
    exit();
}
?>