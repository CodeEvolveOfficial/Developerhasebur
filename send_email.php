<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form_type'])) {
    $formType = $_POST['form_type'];
    $mail = new PHPMailer(true);

    try {
        // SMTP Config (No changes needed here)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'haseburh12@gmail.com';
        $mail->Password   = 'hlxc ewvv hwuv cefz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('developerhasebur@gmail.com', 'Developer Hasebur');
        $mail->addAddress('developeropy@gmail.com', 'Developer Opy');

        if ($formType === 'newsletter') {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            if (!$email) {
                echo "Invalid newsletter email.";
                exit;
            }

            $mail->addReplyTo($email);
            $mail->Subject = "New Newsletter Subscription";
            $mail->isHTML(true);
            $mail->Body = "You have a new subscriber!<br><br>Email: <b>{$email}</b>";
            $mail->AltBody = "New subscriber:\n{$email}";

            $mail->send();
            echo "success_newsletter"; // Specific success message
        } elseif ($formType === 'contact') {
            $name    = htmlspecialchars(trim($_POST['name']));
            $email   = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $phone   = htmlspecialchars(trim($_POST['phone']));
            $service = htmlspecialchars(trim($_POST['service']));
            $budget  = htmlspecialchars(trim($_POST['budget']));
            $message = htmlspecialchars(trim($_POST['message']));

            if (!$email || empty($name) || empty($service) || empty($budget) || empty($message)) {
                echo "Please fill in all required contact fields.";
                exit;
            }

            $mail->addReplyTo($email, $name);
            $mail->Subject = "New Project Inquiry from {$name}";
            $mail->isHTML(true);
            $mail->Body = "
                <h3>New Client Inquiry</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>WhatsApp Number:</strong> {$phone}</p>
                <p><strong>Service Needed:</strong> {$service}</p>
                <p><strong>Budget:</strong> {$budget}</p>
                <p><strong>Project Details:</strong><br>" . nl2br($message) . "</p>
            ";
            $mail->AltBody = "
                Name: {$name}
                Email: {$email}
                WhatsApp: {$phone}
                Service: {$service}
                Budget: {$budget}
                Message: {$message}
            ";

            $mail->send();
            echo "success_contact"; // Specific success message
        } else {
            echo "Unknown form type.";
            exit;
        }

    } catch (Exception $e) {
        // For production, log the error instead of echoing it directly
        // error_log("PHPMailer Error: " . $e->getMessage(), 0); // Example of logging to a file
        echo "error_sending_mail"; // Generic error for the user
    }
} else {
    echo "Invalid request.";
}
?>