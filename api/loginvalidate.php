<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    // USER CHECKING LOGIC
    // SET USERNAME & PASSWORD
    if (($u === 'Thilina' && $p === 'Thilina') || ($u === 'admin' && $p === '123')) {

        // START SESSION
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $u;

        // SEND RESPONSE
        echo json_encode([
            "status" => "success",
            "message" => "Login Successful!",
            "redirect" => "admin.php",  
            "user" => $u
        ]);

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username or Password Incorrect!"
        ]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>