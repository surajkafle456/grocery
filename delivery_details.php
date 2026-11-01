<?php
session_start();
include "db.php";

/* =======================
   LOGIN & CART CHECK
======================= */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'delivery_details.php';
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];

/* =======================
   SERVER-SIDE VALIDATION
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function clean($data) {
        return htmlspecialchars(trim($data));
    }

    $full_name    = clean($_POST['full_name'] ?? '');
    $contact      = clean($_POST['contact'] ?? '');
    $city         = clean($_POST['city'] ?? '');
    $municipality = clean($_POST['municipality'] ?? '');
    $ward         = clean($_POST['ward'] ?? '');
    $address      = clean($_POST['address'] ?? '');

    if (!preg_match("/^[a-zA-Z ]{3,}$/", $full_name))
        $errors[] = "Invalid full name";

    if (!preg_match("/^[0-9]{10}$/", $contact))
        $errors[] = "Invalid contact number";

    if (!preg_match("/^[a-zA-Z ]+$/", $city))
        $errors[] = "Invalid city name";

    if (!preg_match("/^[a-zA-Z ]+$/", $municipality))
        $errors[] = "Invalid municipality";

    if (!preg_match("/^[1-9][0-9]*$/", $ward))
        $errors[] = "Invalid ward number";

    if (strlen($address) < 5)
        $errors[] = "Address must be at least 5 characters";

    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT id FROM delivery_addresses WHERE user_id=?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $stmt = $conn->prepare(
                "UPDATE delivery_addresses
                 SET full_name=?, contact=?, city=?, municipality=?, ward=?, address=?
                 WHERE user_id=?"
            );
            $stmt->bind_param(
                "ssssssi",
                $full_name, $contact, $city,
                $municipality, $ward, $address, $user_id
            );

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO delivery_addresses
                 (full_name, contact, city, municipality, ward, address, user_id)
                 VALUES (?,?,?,?,?,?,?)"
            );
            $stmt->bind_param(
                "ssssssi",
                $full_name, $contact, $city,
                $municipality, $ward, $address, $user_id
            );
        }

        $stmt->execute();

        $_SESSION['delivery_details'] = compact(
            'full_name','contact','city','municipality','ward','address'
        );

        header("Location: receipt.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Delivery Details</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family:Segoe UI;
    background:linear-gradient(135deg,#e8f5e9,#f4f6f8);
    margin:0;
}

/* ⬇ reduced bottom space */
.container{
    min-height:88vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding-bottom:5px;
}

.card{
    width:440px;
    background:#fff;
    border-radius:14px;
    box-shadow:0 15px 35px rgba(0,0,0,.12);
}

.card-header{
    background:linear-gradient(135deg,#28a745,#218838);
    color:#fff;
    text-align:center;
    padding:20px;
}

.card-body{padding:25px;}

.form-group{
    position:relative;
    margin-bottom:14px;
}

.form-group i{
    position:absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color:#28a745;
}

.form-group input,
.form-group textarea{
    width:90%;
    padding:10px 10px 10px 38px;
    border-radius:8px;
    border:1px solid #ccc;
}

.btn{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#28a745,#218838);
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:6px;
    margin-bottom:10px;
}

/* ⬇ only SMALL gap here */
.cart-actions{
    text-align:center;
    margin-top:6px;
    margin-bottom:10px;
    font-family:Segoe UI;
}

.cart-actions .action-btn{
    padding:10px 22px;
    background:#28a745;
    color:#fff;
    border-radius:6px;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.cart-actions .action-btn:hover{
    background:#218838;
}
</style>
</head>
<body>

<?php include "nav_bar.php"; ?>

<div class="container">
<div class="card">

<div class="card-header">
<h2><i class="fa fa-truck"></i> Delivery Details</h2>
</div>

<div class="card-body">

<?php if (!empty($errors)): ?>
<div class="error">
<?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
</div>
<?php endif; ?>

<form method="post" onsubmit="return validateForm()">

<div class="form-group">
<i class="fa fa-user"></i>
<input type="text" name="full_name" placeholder="Full Name" required>
</div>

<div class="form-group">
<i class="fa fa-phone"></i>
<input type="text" name="contact" placeholder="Contact Number" required>
</div>

<div class="form-group">
<i class="fa fa-city"></i>
<input type="text" name="city" placeholder="City" required>
</div>

<div class="form-group">
<i class="fa fa-map-marker-alt"></i>
<input type="text" name="municipality" placeholder="Municipality" required>
</div>

<div class="form-group">
<i class="fa fa-hashtag"></i>
<input type="text" name="ward" placeholder="Ward Number" required>
</div>

<div class="form-group">
<i class="fa fa-location-dot"></i>
<textarea name="address" placeholder="Exact Address" required></textarea>
</div>

<button class="btn">
<i class="fa fa-check-circle"></i> Confirm Payment
</button>

</form>
<div class="cart-actions">
<a href="payment.php" class="action-btn">
<i class="fa fa-arrow-left"></i> Back to Payment
</a>
</div>
</div>
</div>
</div>



<script>
function validateForm(){
    let name=document.querySelector("[name='full_name']").value.trim();
    let contact=document.querySelector("[name='contact']").value.trim();
    let city=document.querySelector("[name='city']").value.trim();
    let municipality=document.querySelector("[name='municipality']").value.trim();
    let ward=document.querySelector("[name='ward']").value.trim();
    let address=document.querySelector("[name='address']").value.trim();

    if(!/^[a-zA-Z ]{3,}$/.test(name)){
        alert("Full name must contain only letters (min 3)");
        return false;
    }
    if(!/^9[0-9]{9}$/.test(contact)){
        alert("Contact number must start with 9 and be 10 digits");
        return false;
    }
    if(!/^[a-zA-Z ]+$/.test(city)){
        alert("City must contain letters only");
        return false;
    }
    if(!/^[a-zA-Z ]+$/.test(municipality)){
        alert("Municipality must contain letters only");
        return false;
    }
    if(!/^[1-9][0-9]*$/.test(ward)){
        alert("Ward must be a positive number");
        return false;
    }
    if(address.length < 5){
        alert("Address must be at least 5 characters");
        return false;
    }
    return true;
}
</script>

</body>
</html>




