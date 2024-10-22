<?php
session_start();
include 'db.php';
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <title>Home</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Reservation Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Make a Reservation</h2>
            <form method="post" action="reservation.php">
                <div class="form-group">
                    <label for="reservation_name">Reservation Name:</label><br>
                    <input type="text" id="reservation_name" name="reservation_name" required><br>
                </div>
                <div class="form-group">
                    <label for="reservation_datetime">Reservation Date and Time:</label><br>
                    <input type="datetime-local" id="reservation_datetime" name="reservation_datetime" required><br>
                </div>
                <input type="submit" value="Reserve" class="btn btn-primary">
            </form>
        </div>
    </div>

    <!-- Admin Modal -->
    <div id="adminModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAdminModal()">&times;</span>
            <h2>Admin Access</h2>
            <form method="post" action="verify_admin.php">
                <div class="form-group">
                    <label for="admin_passkey">Enter Passkey</label><br>
                    <input type="password" id="admin_passkey" name="admin_passkey" required><br>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <h2>Your Reservations</h2>
    <h3>Pending Reservations</h3>
    <ul>
        <?php
        $student_id = $_SESSION['student_id'];
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE student_id = ? AND status = 'pending'");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - Status: {$row['status']}</li>";
        }
        $stmt->close();
        ?>
    </ul>

    <h3>Accepted Reservations</h3>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE student_id = ? AND status = 'accepted'");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - Status: {$row['status']}</li>";
        }
        $stmt->close();
        ?>
    </ul>

    <h3>Rejected Reservations</h3>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM prereservation WHERE student_id = ? AND status = 'rejected'");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['reservation_name']} on {$row['reservation_datetime']} - Status: {$row['status']}</li>";
        }
        $stmt->close();
        ?>
    </ul>

    <script>
        function showReservationModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function showAdminModal() {
            document.getElementById("adminModal").style.display = "block";
        }

        var reservationModal = document.getElementById("myModal");
        var reservationSpan = document.getElementsByClassName("close")[0];

        reservationSpan.onclick = function() {
            reservationModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == reservationModal) {
                reservationModal.style.display = "none";
            } else if (event.target == document.getElementById("adminModal")) {
                document.getElementById("adminModal").style.display = "none";
            }
        }

        function closeAdminModal() {
            document.getElementById("adminModal").style.display = "none";
        }
    </script>
</body>
</html>
