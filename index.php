<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submissions here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle setting room rates and charges
    if (isset($_POST['set_rates'])) {
        $icu_rate = $_POST['icu_rate'];
        $deluxe_rate = $_POST['deluxe_rate'];
        $general_rate = $_POST['general_rate'];
        $doctor_charges = $_POST['doctor_charges'];
        $rmo_charges = $_POST['rmo_charges'];
        $nurse_charges = $_POST['nurse_charges'];

        // Check if values already exist
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM room_rates");
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $stmt = $pdo->prepare("INSERT INTO room_rates (icu_rate, deluxe_rate, general_rate, doctor_charges, rmo_charges, nurse_charges) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$icu_rate, $deluxe_rate, $general_rate, $doctor_charges, $rmo_charges, $nurse_charges]);
            setcookie("rates_set", true, time() + 3600, "/");
        } else {
            $stmt = $pdo->prepare("UPDATE room_rates SET icu_rate = ?, deluxe_rate = ?, general_rate = ?, doctor_charges = ?, rmo_charges = ?, nurse_charges = ?");
            $stmt->execute([$icu_rate, $deluxe_rate, $general_rate, $doctor_charges, $rmo_charges, $nurse_charges]);
        }
    }

    // Handle adding medical expenses
    if (isset($_POST['add_expense'])) {
        $date = $_POST['date'];
        $type = $_POST['type'];
        $amount = $_POST['amount'];

        $stmt = $pdo->prepare("INSERT INTO medical_expenses (date, type, amount) VALUES (?, ?, ?)");
        $stmt->execute([$date, $type, $amount]);
    }

    // Handle updating and deleting medical expenses
    if (isset($_POST['update_expense']) || isset($_POST['delete_expense'])) {
        // Handle update and delete logic here
    }

    // Handle room usage and doctor visits
    if (isset($_POST['calculate_charges'])) {
        $icu_days = $_POST['icu_days'];
        $deluxe_days = $_POST['deluxe_days'];
        $general_days = $_POST['general_days'];
        $doctor_visits = $_POST['doctor_visits'];
        $rmo_visits = $_POST['rmo_visits'];
        $nurse_visits = $_POST['nurse_visits'];

        $stmt = $pdo->prepare("INSERT INTO room_usage (icu_days, deluxe_days, general_days, doctor_visits, rmo_visits, nurse_visits) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$icu_days, $deluxe_days, $general_days, $doctor_visits, $rmo_visits, $nurse_visits]);
    }
}

// Fetch data for display
$stmt = $pdo->query("SELECT * FROM room_rates");
$rates = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM medical_expenses");
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM room_usage");
$usage = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle updating room rates and charges
if (isset($_POST['update_rates'])) {
    $icu_rate = $_POST['icu_rate'];
    $deluxe_rate = $_POST['deluxe_rate'];
    $general_rate = $_POST['general_rate'];
    $doctor_charges = $_POST['doctor_charges'];
    $rmo_charges = $_POST['rmo_charges'];
    $nurse_charges = $_POST['nurse_charges'];

    $stmt = $pdo->prepare("UPDATE room_rates SET icu_rate = ?, deluxe_rate = ?, general_rate = ?, doctor_charges = ?, rmo_charges = ?, nurse_charges = ?");
    $stmt->execute([$icu_rate, $deluxe_rate, $general_rate, $doctor_charges, $rmo_charges, $nurse_charges]);
    header("Location: index.php"); // Refresh page to avoid resubmission
}

// Handle editing medical expenses
if (isset($_POST['edit_expense'])) {
    $expense_id = $_POST['expense_id'];
    // Fetch expense details
    $stmt = $pdo->prepare("SELECT * FROM medical_expenses WHERE id = ?");
    $stmt->execute([$expense_id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    // Display form for editing (similar to Add form)
    // Use the fetched data to pre-fill the form
}

// Handle updating edited expense
// if (isset($_POST['update_expense'])) {
//     $expense_id = $_POST['expense_id'];
//     $date = $_POST['date'];
//     $type = $_POST['type'];
//     $amount = $_POST['amount'];

//     $stmt = $pdo->prepare("UPDATE medical_expenses SET date = ?, type = ?, amount = ? WHERE id = ?");
//     $stmt->execute([$date, $type, $amount, $expense_id]);
//     header("Location: index.php"); // Refresh page to avoid resubmission
// }

// Handle deleting medical expenses
if (isset($_POST['delete_expense'])) {
    $expense_id = $_POST['expense_id'];

    $stmt = $pdo->prepare("DELETE FROM medical_expenses WHERE id = ?");
    $stmt->execute([$expense_id]);
    header("Location: index.php"); // Refresh page to avoid resubmission
}

// Handle updating medical expenses
if (isset($_POST['update_expense'])) {
    $expense_id = $_POST['expense_id'];
    $expense_date = $_POST['expense_date'];
    $expense_type = $_POST['expense_type'];
    $expense_amount = $_POST['expense_amount'];

    $stmt = $pdo->prepare("UPDATE medical_expenses SET date = ?, type = ?, amount = ? WHERE id = ?");
    $stmt->execute([$expense_date, $expense_type, $expense_amount, $expense_id]);
    header("Location: index.php"); // Refresh page to avoid resubmission
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Billing App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div style="text-align:center; margin-bottom:20px;">
        <img src="logo.svg" alt="Hospital Billing App Logo" style="width:100px; height:100px;">
    </div>
    <h1>Hospital Billing App</h1>

    <!-- Section 1: Set Room Rates and Charges -->
    <h2>Set Room Rates and Charges</h2>
    <form method="POST">
        <label for="icu_rate">ICU Room Rate:</label>
        <input type="number" name="icu_rate" step="0.01" value="<?= $rates['icu_rate'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <label for="deluxe_rate">Deluxe Room Rate:</label>
        <input type="number" name="deluxe_rate" step="0.01" value="<?= $rates['deluxe_rate'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <label for="general_rate">General Room Rate:</label>
        <input type="number" name="general_rate" step="0.01" value="<?= $rates['general_rate'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <label for="doctor_charges">Doctor Charges per day:</label>
        <input type="number" name="doctor_charges" step="0.01" value="<?= $rates['doctor_charges'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <label for="rmo_charges">RMO Charges per day:</label>
        <input type="number" name="rmo_charges" step="0.01" value="<?= $rates['rmo_charges'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <label for="nurse_charges">Nurses Charges per day:</label>
        <input type="number" name="nurse_charges" step="0.01" value="<?= $rates['nurse_charges'] ?? '' ?>" <?= isset($_COOKIE['rates_set']) ? 'disabled' : '' ?>><br>
        <button type="submit" name="set_rates">Set Rates</button>
    </form>

    <!-- Display Room Rates and Charges Table -->
<h3>Room Rates and Charges</h3>
<?php if ($rates): ?>
<table>
    <tr>
        <th>ICU Room Rate</th>
        <th>Deluxe Room Rate</th>
        <th>General Room Rate</th>
        <th>Doctor Charges per day</th>
        <th>RMO Charges per day</th>
        <th>Nurses Charges per day</th>
        <th>Actions</th>
    </tr>
    <tr>
        <td><?= $rates['icu_rate'] ?></td>
        <td><?= $rates['deluxe_rate'] ?></td>
        <td><?= $rates['general_rate'] ?></td>
        <td><?= $rates['doctor_charges'] ?></td>
        <td><?= $rates['rmo_charges'] ?></td>
        <td><?= $rates['nurse_charges'] ?></td>
        <td>
            <form method="POST" style="display:inline;">
            <button type="button" name="edit_rates">Edit</button>
            </form>
        </td>
    </tr>
</table>

<!-- Modal for Editing Room Rates and Charges -->
<div id="editRatesModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Edit Room Rates and Charges</h3>
        <form id="editRatesForm" method="POST">
            <label for="icu_rate">ICU Room Rate:</label>
            <input type="number" id="icu_rate" name="icu_rate" step="0.01" required><br>
            <label for="deluxe_rate">Deluxe Room Rate:</label>
            <input type="number" id="deluxe_rate" name="deluxe_rate" step="0.01" required><br>
            <label for="general_rate">General Room Rate:</label>
            <input type="number" id="general_rate" name="general_rate" step="0.01" required><br>
            <label for="doctor_charges">Doctor Charges per day:</label>
            <input type="number" id="doctor_charges" name="doctor_charges" step="0.01" required><br>
            <label for="rmo_charges">RMO Charges per day:</label>
            <input type="number" id="rmo_charges" name="rmo_charges" step="0.01" required><br>
            <label for="nurse_charges">Nurses Charges per day:</label>
            <input type="number" id="nurse_charges" name="nurse_charges" step="0.01" required><br>
            <button type="submit" name="update_rates">Update Rates</button>
        </form>
    </div>
</div>



<?php else: ?>
<p>No rates and charges set yet.</p>
<?php endif; ?>


    <!-- Section 2: Manage Medical Expenses -->
    <h2>Manage Medical Expenses</h2>
    <form method="POST">
        <label for="date">Date:</label>
        <input type="date" name="date" required><br>
        <label for="type">Type:</label>
        <select name="type" required>
            <option value="medical">Medical</option>
            <option value="non-medical">Non-Medical</option>
        </select><br>
        <label for="amount">Amount:</label>
        <input type="number" name="amount" step="0.01" required><br>
        <button type="submit" name="add_expense">Add Expense</button>
    </form>

    <!-- Display Medical Expenses Table -->
    <h3>Medical Expenses</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($expenses as $expense): ?>
            <tr data-id="<?= $expense['id'] ?>">
    <td><?= $expense['date'] ?></td>
    <td><?= $expense['type'] ?></td>
    <td><?= $expense['amount'] ?></td>
    <td>
        <button class="edit-expense-btn" data-id="<?= $expense['id'] ?>">Edit</button>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="expense_id" value="<?= $expense['id'] ?>">
            <input type="hidden" name="delete_expense" value="1">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>

<!-- Modal for Editing Medical Expenses -->
<div id="editExpenseModal" class="modal">
    <div class="modal-content">
        <span class="close close-expense">&times;</span>
        <h3>Edit Medical Expense</h3>
        <form id="editExpenseForm" method="POST">
            <input type="hidden" id="expense_id" name="expense_id">
            <label for="expense_date">Date:</label>
            <input type="date" id="expense_date" name="expense_date" required><br>
            <label for="expense_type">Type:</label>
            <select id="expense_type" name="expense_type" required>
                <option value="medical">Medical</option>
                <option value="non-medical">Non-Medical</option>
            </select><br>
            <label for="expense_amount">Amount:</label>
            <input type="number" id="expense_amount" name="expense_amount" step="0.01" required><br>
            <button type="submit" name="update_expense">Update Expense</button>
        </form>
    </div>
</div>



<!-- Section 3: Room Usage and Doctor Visits -->
<h2>Room Usage and Doctor Visits</h2>
<form method="POST">
    <label for="icu_days">Days in ICU:</label>
    <input type="number" name="icu_days" required><br>
    <label for="deluxe_days">Days in Deluxe:</label>
    <input type="number" name="deluxe_days" required><br>
    <label for="general_days">Days in General:</label>
    <input type="number" name="general_days" required><br>
    <label for="doctor_visits">Number of Doctor Visits:</label>
    <input type="number" name="doctor_visits" required><br>
    <label for="rmo_visits">Number of RMO Visits:</label>
    <input type="number" name="rmo_visits" required><br>
    <label for="nurse_visits">Number of Nurse Visits:</label>
    <input type="number" name="nurse_visits" required><br>
    <button type="submit" name="calculate_charges">Calculate Charges</button>
</form>

<?php
if ($rates && $usage) {
    $total_days = $usage['icu_days'] + $usage['deluxe_days'] + $usage['general_days'];
    
    // Calculate the total medical bill, default to 0 if no expenses found
    $total_medical_bill = array_sum(array_column($expenses, 'amount'));
    if ($total_medical_bill == 0) {
        $total_medical_bill = 0;
    }

    // Calculate the total hospital bill, default to 0 if no rates found
    $total_hospital_bill = 0;
    if ($rates) {
        $total_hospital_bill = ($rates['doctor_charges'] * $total_days) +
                               ($rates['rmo_charges'] * $total_days) +
                               ($rates['nurse_charges'] * $total_days);
    }
?>
<table class="summary-table">
    <tr>
        <th>Total Doctor Visits</th>
        <td><?= $usage['doctor_visits'] ?></td>
    </tr>
    <tr>
        <th>Total RMO Visits</th>
        <td><?= $usage['rmo_visits'] ?></td>
    </tr>
    <tr>
        <th>Total Nurse Visits</th>
        <td><?= $usage['nurse_visits'] ?></td>
    </tr>
    <tr>
        <th>Number of Days in ICU</th>
        <td><?= $usage['icu_days'] ?></td>
    </tr>
    <tr>
        <th>Number of Days in Deluxe</th>
        <td><?= $usage['deluxe_days'] ?></td>
    </tr>
    <tr>
        <th>Number of Days in General</th>
        <td><?= $usage['general_days'] ?></td>
    </tr>
    <tr>
        <th>Total Medical Bill</th>
        <td><?= $total_medical_bill ?></td>
    </tr>
    <tr>
        <th>Total Hospital Bill</th>
        <td><?= $total_hospital_bill ?></td>
    </tr>
</table>
<?php
} else {
    echo '<p>No data available.</p>';
}
?>

    <form method="POST" action="logout.php">
        <button type="submit">Logout</button>
    </form>
    <script  src="script.js"></script>
</body>
</html>
