<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - GroceryMart</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">🛒 GroceryMart</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php" class="active">Contact Us</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</div>

<!-- Contact Section -->
<div class="contact-section">
    <h1>Contact Us</h1>
    <p>If you have any questions or feedback, feel free to reach out!</p>

    <form method="post" action="contact_submit.php">
        <input type="text" name="name" placeholder="Your Name" required><br>
        <input type="email" name="email" placeholder="Your Email" required><br>
        <input type="text" name="subject" placeholder="Subject" required><br>
        <textarea name="message" placeholder="Your Message" rows="5" required></textarea><br>
        <button type="submit" name="submit">Send Message</button>
    </form>
</div>

</body>
</html>
