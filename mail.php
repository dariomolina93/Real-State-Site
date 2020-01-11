<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        # FIX: Replace this email with recipient email
        $mail_to = "darioporta88@gmail.com";
        
        # Sender Data
        // $subject = trim($_POST["subject"]);
        $name = str_replace(array("\r","\n"),array(" "," ") , strip_tags(trim($_POST["name"])));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);

        if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($message)) {
            # Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }

        ///////////////////////////////////////////////////

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = false;                                   // Enable SMTP authentication
            //$mail->Username   = 'tinagarciarealtor@gmail.com';                     // SMTP username
            //$mail->Password   = 'tinagarciarealtortinagarciarealtor';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom("tinagarciarealtor@gmail.com","Website");
            $mail->addAddress('darioporta88@gmail.com');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name


            if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
                    $mail->Subject = 'PHPMailer contact form';
                    //Keep it simple - don't use HTML
                    $mail->isHTML(false);
                    //Build a simple message body
                    $mail->Body = <<<EOT
Email: {$email}
Name: {$name}
Message: {$message}
EOT;
                        
                    //Send the message, check for errors
                    if (!$mail->send()) {
                        //The reason for failing to send will be in $mail->ErrorInfo
                        //but you shouldn't display errors to users - process the error, log it on your server.
                        http_response_code(403);
                        echo 'Sorry, something went wrong. Please try again later.';
                    } else {
                        http_response_code(200);
                        echo 'Message sent! Thanks for contacting us.';
                    }
                } else {
                    http_response_code(403);
                    echo 'Invalid email address, message ignored.';
                }
            

            // Content
        //     $mail->isHTML(true);                                  // Set email format to HTML
        //     $mail->Subject = 'New Inquiry from Tina Garcia Website';
        //     $mail->Body    = $message;
        //     // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //     $mail->send();
        //     echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


        //////////////////////////////////////////////////
    
        
    //     # Mail Content
    //     $content = "Name: $name\n";
    //     $content .= "Email: $email\n\n";
    //     $content .= "Message:\n$message\n";

    //     # email headers.
    //     $headers = "From: $name <$email>";

    //     # Send the email.
    //     $success = mail($mail_to,$subject,$content, $headers);
    //     if ($success) {
    //         # Set a 200 (okay) response code.
    //         http_response_code(200);
    //         echo "Thank You! Your message has been sent.";
    //     } else {
    //         # Set a 500 (internal server error) response code.
    //         http_response_code(500);
    //         echo "Oops! Something went wrong, we couldn't send your message.";
    //     }

    // } else {
    //     # Not a POST request, set a 403 (forbidden) response code.
    //     http_response_code(403);
    //     echo "There was a problem with your submission, please try again.";
    }

?>
