<?php
session_start();
require_once("DBconnect.php"); // your DB connection

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch user info
$user_id = $_SESSION['student_id'];
$query = "SELECT * FROM users WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>BRACU RIDESHARING WEBSITE</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    /* Hero section */
    .hero {
      background-image: url('images/back.jpg');
      background-size: cover;
      background-position: center;
      height: 50vh;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: absolute;
      top: 70px;
      width: 100%;
    }

    .hero h1 {
      font-size: 4rem;
      color:rgba(30, 42, 207, 0.77);
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .hero p {
      font-size: 1.4rem;
      color:rgba(30, 42, 207, 0.77);
      max-width: 800px;
      margin-top: 20px;
   
      text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
    }

    /* CTA Buttons */
    .cta-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 20px;
      margin-top: 60vh;
    }

    .cta-buttons .card {
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: linear-gradient(to bottom right, #ffffff, #f0f0f0);
      color: black;
      text-decoration: none;
      padding: 30px;
      height: 250px;
      width: 300px;
      border-radius: 24px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      text-align: center;
    }

    .cta-buttons .card:hover {
      background: #007bff;
      color: white;
      transform: scale(1.05);
      box-shadow: 0 12px 28px rgba(0, 123, 255, 0.4);
    }

    .cta-buttons .card h2 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .cta-buttons .card p {
      font-size: 0.95rem;
      color: #555;
    }

    .cta-buttons .card:hover p {
      color: #e0e0e0;
    }
    .cta-buttons .card:hover h2 {
      color: #e0e0e0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1rem;
        padding: 0 15px;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }

      .cta-buttons .card {
        width: 80%;
        height: auto;
        padding: 20px;
      }

      nav {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav-left, .nav-right {
        flex-direction: column;
        gap: 10px;
      }

      .user-dropdown {
        right: 10px;
      }
    }
    .container{
        background-color: rgba(255, 255, 255, 0.5);
    }
  </style>
</head>
<body>

<!-- Navigation -->
<nav>
  <div class="nav-left">
    <a href="home.php">Home</a>
    <a href="ride.php">Available Rides</a>
    <a href="profile.php">Profile</a>
    <a href="your_trips.php">Your Trips</a>
    <a href="select_chat.php">Chats</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="preferance.php">Preferances</a>
    <a href="completed_trips.php">Completed Trips</a>
    
  </div>
  <div class="nav-right">
    <a class="nav-btn" href="comment.php">Feedback</a>
    <button class="user-btn" onclick="toggleUserCard()">
    👤 <?php echo htmlspecialchars($user['Name']); ?> ▼
    </button>
    <div class="user-dropdown" id="userCard">
        <strong>Name:</strong> <?php echo htmlspecialchars($user['Name']); ?><br>
        <strong>ID:</strong> <?php echo htmlspecialchars($user['Student_id']); ?><br>
        <strong>Email:</strong> <?php echo htmlspecialchars($user['Brac_mail']); ?><br>
    <a href="profile.php">Manage Account</a>
    <form method="POST" style="margin-top: 10px;">
        <input type="hidden" name="logout" value="1">
        <button type="submit" style="background: none; border: none; color: red; font-weight: bold; cursor: pointer;text-align:left;">
            Logout
        </button>
    </form>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
    <h1>BRACU RIDESHARING WEBSITE</h1>
    <p>Many BRAC University students face daily transportation challenges. This platform connects students to share rides, promoting safety, community, and convenience.</p>
    </div>
</section>

<!-- CTA Buttons -->
<section class="cta-buttons">
  <a class="card" href="ride.php">
    <h2>Available Rides</h2>
    <p>Create or apply for a ride card to travel with fellow students</p>
  </a>
  <a class="card" href="profile.php">
    <h2>Profile</h2>
    <p>Verify and edit your account details and personal information</p>
  </a>
  <a class="card" href="select_chat.php">
    <h2>Chats</h2>
    <p>Chat with other students about the ride details</p>
  </a>
  <a class="card" href="your_trips.php">
    <h2>Your Trips</h2>
    <p>Details about all your trips and rides</p>
  </a>
</section>

<script>
  function toggleUserCard() {
    const card = document.getElementById("userCard");
    card.style.display = card.style.display === "block" ? "none" : "block";
  }

  window.addEventListener('click', function (e) {
    if (!e.target.closest('.user-btn') && !e.target.closest('#userCard')) {
      document.getElementById("userCard").style.display = "none";
    }
  });
</script>

</body>
</html>
