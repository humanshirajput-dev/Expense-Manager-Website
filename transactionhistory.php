<?php
require_once "db.php";

$expenses = [];
$total_spent = 0;

$sql = "SELECT id, title, amount, category, expense_date, payment_mode, notes FROM expenses ORDER BY expense_date DESC, id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
        $total_spent += (float)$row['amount'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - Expense Manager</title>
    <link rel="stylesheet" href="style.css">

    <!-- Top Fixed Sticky Navbar (Row Format) -->
    <nav class="navbar-top">
        <div class="nav-container">
            <div class="logo-container">
                <img src="logo.jpeg" alt="Logo" class="logo-img">
            </div>
            <ul class="nav-links">
                <li><a href="index.html"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="about.html"><i class="fa-solid fa-chart-pie"></i> About</a></li>
                <li><a href="http://localhost/PROJECT/addexpense.php"><i class="fa-solid fa-circle-plus"></i> Add Expense</a></li>
                <li><a href="transactionhistory.php"><i class="fa-solid fa-right-left"></i> Transaction History </a></li>
                <li><a href="Reports.php"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
                  <li><a href="saving.html"><i class="fa-solid fa-piggy-bank"></i> Saving/Goals</a></li>
                <li>
                    <a href="profile.html" class="active" id="profile-nav-link">
                        <i class="fa-solid fa-user-gear"></i> <span id="nav-user-name">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }
        .history-wrapper {
            max-width: 1050px;
            margin: 90px auto 30px auto;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }
        .history-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        .history-top h2 {
            margin: 0;
            font-size: 22px;
            color: #111827;
        }
        .summary-box {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .expense-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        .expense-table thead {
            background-color: #1e293b;
            color: #ffffff;
        }
        .expense-table th,
        .expense-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .expense-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .expense-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        .amount-cell {
            font-weight: bold;
            color: #0f172a;
        }
        .empty-state {
            text-align: center;
            padding: 36px 16px;
            color: #6b7280;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="history-wrapper">
        <div class="history-top">
            <h2>Transaction History</h2>
            <div class="summary-box">
                Total Expense: ₹<?php echo number_format($total_spent, 2); ?>
            </div>
        </div>

        <?php if (count($expenses) > 0): ?>
            <div class="table-responsive">
                <table class="expense-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Title / Expense</th>
                            <th>Category</th>
                            <th>Payment Mode</th>
                            <th>Notes</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['expense_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                <td><?php echo htmlspecialchars($item['payment_mode']); ?></td>
                                <td><?php echo htmlspecialchars(!empty($item['notes']) ? $item['notes'] : '-'); ?></td>
                                <td class="amount-cell">₹<?php echo number_format((float)$item['amount'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                Abhi tak koi expense save nahi hua hai. "Add Expense" se entry karein.
            </div>
        <?php endif; ?>
    </div>
    <!-- Professional Footer -->
    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Invoices</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p>+91 98765 43210</p>
                <p>support@expensemanager.com</p>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Expense Manager App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>