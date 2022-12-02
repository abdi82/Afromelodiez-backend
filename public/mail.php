<?php
$to      = 'developer1607@gmail.com';
$subject = 'the subject';
$message = 'hello 2';
$headers = 'From: info@afromelodies.com' . "\r\n" .
    'Reply-To: info@afromelodies.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$send=mail($to, $subject, $message, $headers);

if(isset($send))
{
	echo "success";
	print_r($send);
}
?> 
