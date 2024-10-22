<?php
session_start();

if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header("Location: home.php"); // Redirect if not an admin
    exit();
}

include 'db.php';

date_default_timezone_set('Asia/Manila'); // Set your timezone
$current_datetime = date('Y-m-d H:i:s');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'];

    // Get the reservation details
    $stmt = $conn->prepare("SELECT reservation_datetime FROM prereservation WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();
    $reservation_datetime = $reservation['reservation_datetime'];
    $stmt->close();

    if ($action == 'accept') {
        // Check for overlapping reservations
        $stmt = $conn->prepare("
            SELECT * FROM prereservation 
            WHERE reservation_id != ? 
            AND reservation_datetime = ?
            AND status = 'accepted'
        ");
        $stmt->bind_param("is", $reservation_id, $reservation_datetime);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Cannot accept: There is an overlapping reservation.');</script>";
        } elseif ($reservation_datetime < $current_datetime) {
            echo "<script>alert('Cannot accept: Reservation date is in the past.');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE prereservation SET status = 'accepted' WHERE reservation_id = ?");
            $stmt->bind_param("i", $reservation_id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE prereservation SET status = 'rejected' WHERE reservation_id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'cancel') {
        $stmt = $conn->prepare("UPDATE prereservation SET status = 'pending' WHERE reservation_id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">

    <title>Admin</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Admin Page</h1>

    <h2>Pending Reservations</h2>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE status = 'pending'");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - ";
            echo "<form method='post' style='display:inline;'>
                    <input type='hidden' name='reservation_id' value='{$row['reservation_id']}'>
                    <button type='submit' name='action' value='accept'>Accept</button>
                    <button type='submit' name='action' value='reject'>Reject</button>
                  </form>";
            echo "</li>";
        }
        $stmt->close();
        ?>
    </ul>

    <h2>Accepted Reservations</h2>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE status = 'accepted'");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - ";
            echo "<form method='post' style='display:inline;'>
                    <input type='hidden' name='reservation_id' value='{$row['reservation_id']}'>
                    <button type='submit' name='action' value='cancel'>Cancel</button>
                  </form>";
            echo "</li>";
        }
        $stmt->close();
        ?>
    </ul>

    <h2>Rejected Reservations</h2>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE status = 'rejected'");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - ";
            echo "<form method='post' style='display:inline;'>
                    <input type='hidden' name='reservation_id' value='{$row['reservation_id']}'>
                    <button type='submit' name='action' value='accept'>Accept</button>
                  </form>";
            echo "</li>";
        }
        $stmt->close();
        ?>
    </ul>
</body>
</html>
