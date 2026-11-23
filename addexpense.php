<?php
require_once "db.php";

$status = $_GET["status"] ?? "";
$msg = $_GET["msg"] ?? "";

$recent_expenses = [];
$sql = "SELECT id, title, amount, category, expense_date, payment_mode, notes FROM expenses ORDER BY expense_date DESC, id DESC LIMIT 10";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_expenses[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Manager - Add Expense</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .alert-banner {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .form-actions-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }
        .form-actions-row .btn-green {
            flex: 1;
        }
        .notes-sub {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-top: 3px;
        }
        @media (max-width: 600px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Sticky Top Navbar -->
    <nav class="navbar-top">
        <div class="nav-container">
            <div class="logo-container">
                <a href="index.html">
                    <img src="logo.jpeg" alt="Logo" class="logo-img">
                </a>
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

    <div class="main-content">

        <div class="dash-header">
            <div>
                <h1><i class="fa-solid fa-circle-plus" style="color: #22c55e;"></i> Add New Expense</h1>
                <p>Record your daily spending directly into the MySQL database.</p>
            </div>
           
        </div>

        <?php if ($status === "success" && $msg !== ""): ?>
            <div class="alert-banner alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo htmlspecialchars($msg); ?></span>
            </div>
        <?php elseif ($status === "error" && $msg !== ""): ?>
            <div class="alert-banner alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?php echo htmlspecialchars($msg); ?></span>
            </div>
        <?php endif; ?>

        <section class="dash-grid">

            <!-- Left: Add Expense Form -->
            <div class="dash-panel">
                <h3><i class="fa-solid fa-receipt"></i> Expense Details</h3>

                <form action="save_expense.php" method="POST" id="add-expense-form">
                    <div class="form-group">
                        <label for="title">Expense Title *</label>
                        <input type="text" id="title" name="title" placeholder="e.g. Metro Card Recharge, Lunch" required>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="amount">Amount *</label>
                            <input type="number" id="amount" name="amount" step="0.01" min="0.01" placeholder="e.g. 250" required>
                        </div>

                        <div class="form-group">
                            <label for="expense_date">Date *</label>
                            <input type="date" id="expense_date" name="expense_date" required>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="Travel">Travel</option>
                                <option value="Food & Dining">Food & Dining</option>
                                <option value="Bills & Utilities">Bills & Utilities</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Education">Education</option>
                                <option value="Health">Health</option>
                                <option value="Savings/Goals">Savings/Goals</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="payment_mode">Payment Mode</label>
                            <select id="payment_mode" name="payment_mode">
                                <option value="UPI">UPI</option>
                                <option value="Cash">Cash</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Net Banking">Net Banking</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <input type="text" id="notes" name="notes" placeholder="e.g. Rajiv Chowk to Maitreyi route">
                    </div>

                    <div class="form-actions-row">
                        <button type="submit" class="btn-green">
                            <i class="fa-solid fa-floppy-disk"></i> Save Expense
                        </button>
                        <button type="reset" class="btn-outline-navy">Clear</button>
                    </div>
                </form>
            </div>

            

    <script>
        // Default today's date
        const dateInput = document.getElementById("expense_date");
        if (dateInput && !dateInput.value) {
            dateInput.valueAsDate = new Date();
        }

        // Sync user name & currency from localStorage profile if available
        const savedProfile = JSON.parse(localStorage.getItem("expenseUserProfile") || "null");
        if (savedProfile) {
            const navNameEl = document.getElementById("nav-user-name");
            if (navNameEl && savedProfile.name) {
                navNameEl.innerText = savedProfile.name;
            }
            if (savedProfile.currency) {
                document.querySelectorAll(".currency-symbol").forEach(el => {
                    el.innerText = savedProfile.currency;
                });
            }
        }
    </script>
    <br>
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
<?php
if (isset($conn)) {
    $conn->close();
}
?>