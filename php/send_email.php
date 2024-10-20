<?php

header('Content-Type: application/json'); // Always declare JSON output at the top

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';


// Start session
session_start();

// Check if the user is logged in and has a role
if (!isset($_SESSION['role'])) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. User not logged in.']);
    exit;
}

// Check if the user has the correct role (Admin or IT Admin)
$allowed_roles = ['Admin', 'IT_Admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Insufficient privileges.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sender_email']) && isset($_POST['inquiry_id'])) {
        $senderEmail = $_POST['sender_email'];
        $inquiryId = intval($_POST['inquiry_id']);

        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                      // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'magsbunz@gmail.com';               // SMTP username
                $mail->Password   = 'mmwx vehk lyvk eapk';                        // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable SSL encryption
                $mail->Port       = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('magsbunz@gmail.com', 'Lost & Found Office');
                $mail->addAddress($senderEmail);                            // Add recipient's email

                //Content
                $mail->isHTML(true);                                        // Set email format to HTML
                $mail->Subject = 'Lost Item Inquiry Response';
                $mail->Body    = 'Hi! We might have your Lost Item in our Inventory here in the Lost and Found Office, you might want to check it out.';

                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'Email has been sent']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Email could not be sent. Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing email or inquiry ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
