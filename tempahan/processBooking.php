<?php
header('Content-Type: application/json');

// Get JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Database connection
$servername = "localhost";
$username = "root"; // Default for XAMPP/WAMP
$password = ""; // Default for XAMPP/WAMP (no password)
$dbname = "tempahan"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Extract data
$namaPIC = $conn->real_escape_string($data['namaPIC']);
$bahagian = $conn->real_escape_string($data['bahagian']);
$tarikh = $conn->real_escape_string($data['tarikh']);
$masa = $conn->real_escape_string($data['masa']);
$bilanganPeserta = $conn->real_escape_string($data['bilanganPeserta']);
$namaProgram = $conn->real_escape_string($data['namaProgram']);

// Check if the date is already booked
$sql = "SELECT * FROM tempahan_dibuat WHERE tarikh = '$tarikh'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode(["message" => "The date is already booked. Please choose another date."]);
} else {
    // Insert the booking into the database
    $sql = "INSERT INTO tempahan_dibuat (namaPIC, bahagian, tarikh, masa, bilanganPeserta, namaProgram)
            VALUES ('$namaPIC', '$bahagian', '$tarikh', '$masa', '$bilanganPeserta', '$namaProgram')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Booking successful!"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

$conn->close();
?>
