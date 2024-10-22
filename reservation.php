<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
        $reservation_name = $_POST['reservation_name'];
        $reservation_datetime = $_POST['reservation_datetime'];

        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO prereservation (student_id, reservation_name, reservation_datetime, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iss", $student_id, $reservation_name, $reservation_datetime);

        if ($stmt->execute()) {
            // Redirect to home page upon successful reservation
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: Student ID not found in session.";
    }
}
?>
