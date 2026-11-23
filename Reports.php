<?php
require_once "db.php";

$total_expense = 0;
$total_count = 0;
$category_rows = [];
$payment_rows = [];

$summary_res = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total_amt, COUNT(*) AS total_cnt FROM expenses");
if ($summary_res && $row = $summary_res->fetch_assoc()) {
    $total_expense = (float)$row['total_amt'];
    $total_count = (int)$row['total_cnt'];
}

$cat_res = $conn->query("
    SELECT COALESCE(NULLIF(TRIM(category), ''), 'Uncategorized') AS category,
           COALESCE(SUM(amount), 0) AS total_amt,
           COUNT(*) AS tx_count
    FROM expenses
    GROUP BY COALESCE(NULLIF(TRIM(category), ''), 'Uncategorized')
    ORDER BY total_amt DESC
");
if ($cat_res) {
    while ($r = $cat_res->fetch_assoc()) {
        $category_rows[] = $r;
    }
}

$pay_res = $conn->query("
    SELECT COALESCE(NULLIF(TRIM(payment_mode), ''), 'Other') AS payment_mode,
           COALESCE(SUM(amount), 0) AS total_amt,
           COUNT(*) AS tx_count
    FROM expenses
    GROUP BY COALESCE(NULLIF(TRIM(payment_mode), ''), 'Other')
    ORDER BY total_amt DESC
");
if ($pay_res) {
    while ($r = $pay_res->fetch_assoc()) {
        $payment_rows[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Expense Manager</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="report.css">
</head>
<body>
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
    <!-- Yahan transactionhistory.php wala apna <nav class="navbar-top">...</nav> paste karein -->

    <main class="reports-wrapper">
        <div class="reports-header">
            <h1>Expense Reports</h1>
            <p>Summary of your recorded spending by category and payment method.</p>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Spent</span>
                <span class="stat-value">₹<?php echo number_format($total_expense, 2); ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Transactions</span>
                <span class="stat-value"><?php echo $total_count; ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Average / Entry</span>
                <span class="stat-value">
                    ₹<?php echo $total_count > 0 ? number_format($total_expense / $total_count, 2) : '0.00'; ?>
                </span>
            </div>
        </section>

        <section class="report-panels">
            <div class="report-card">
                <h2>Category-Wise Spending</h2>
                <?php if (!empty($category_rows)): ?>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Entries</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($category_rows as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                    <td><?php echo (int)$item['tx_count']; ?></td>
                                    <td class="amt-col">₹<?php echo number_format((float)$item['total_amt'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-msg">No category data available yet.</p>
                <?php endif; ?>
            </div>

            <div class="report-card">
                <h2>Payment Mode Breakdown</h2>
                <?php if (!empty($payment_rows)): ?>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Payment Mode</th>
                                <th>Entries</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payment_rows as $pay): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pay['payment_mode']); ?></td>
                                    <td><?php echo (int)$pay['tx_count']; ?></td>
                                    <td class="amt-col">₹<?php echo number_format((float)$pay['total_amt'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-msg">No payment mode data available yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

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