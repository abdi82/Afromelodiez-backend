<?php
$to      = 'shivam123@yopmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: developer1607@gmail.com' . "\r\n" .
    'Reply-To: developer1607@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?> 