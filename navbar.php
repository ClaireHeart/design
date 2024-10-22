<!-- navbar.php -->
<nav>
    <div class="logo-container">
        <img src="../images/logo.png" alt="Logo 1" class="logo">
        <img src="../images/txt.png" alt="Logo 2" class="logo">
    </div>
    <ul>
        <li><a href="#" onclick="showAdminModal()">Admin</a></li>
        <li><a href="home.php">Home</a></li>
        <li><a href="#" onclick="showReservationModal()">Reserve</a></li>
        <li><a href="borrowing.php">Borrowing</a></li>
        <li><a href="sanction.php">Sanctions</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Reservation Modal -->
<div id="myModal" class="modal" style="display: none;">
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
<div id="adminModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAdminModal()">&times;</span>
        <h2>Admin Access</h2>
        <form method="post" action="verify_admin.php">
            <div class="form-group">
                <label for="admin_passkey">Enter Passkey:</label><br>
                <input type="password" id="admin_passkey" name="admin_passkey" required><br>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script>
    function showReservationModal() {
        document.getElementById("myModal").style.display = "block";
    }

    function showAdminModal() {
        document.getElementById("adminModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    function closeAdminModal() {
        document.getElementById("adminModal").style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById("myModal")) {
            closeModal();
        } else if (event.target == document.getElementById("adminModal")) {
            closeAdminModal();
        }
    }
</script>
