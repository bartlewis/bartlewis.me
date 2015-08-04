<?php
require_once('common.php');

// Redirect?
if (count($_POST)==0){
	header('Location: index.php');
}

$response = array(
	'is_success' => false,
	'message' => 'Email sent. Thanks!'
);
$to = 'bartlewis+starme@gmail.com';
$isAjax = array_key_exists('is-ajax', $_POST);
$isCaptcha = (array_key_exists('captcha', $_POST)) ? (bool) $_POST['captcha'] : false;
$url = (array_key_exists('url', $_POST)) ? $_POST['url'] : '';
$name = (array_key_exists('name', $_POST)) ? $_POST['name'] : '';
$email = (array_key_exists('email', $_POST)) ? $_POST['email'] : '';
$subject = (array_key_exists('subject', $_POST)) ? $_POST['subject'] : '';
$message = (array_key_exists('message', $_POST)) ? $_POST['message'] : '';

if ($isCaptcha || !empty($url)){
	$response['message'] = 'Mail not sent. You might not be human.';
}
else{
	$message = "
	<html>
	<head>
		<title>Contact Form From About Me Page</title>
	</head>
	<body>
		<h2>Someone sent you a message from your <a href=\"http://www.bartlewis.me\">About Me</a> page!</h2>
		<p>
			<h3>Name</h3>$name
		</p>
		<p>
			<h3>Email</h3>$email
		</p>
		<p>
			<h3>Subject</h3>$subject
		</p>
		<p>
			<h3>Message</h3>$message
		</p>
	</body>
	</html>
	";

	// To send HTML mail, the Content-type header must be set
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	// Additional headers
	$headers .= "To: $to\r\n";
	$headers .= "From: contact@bartlewis.me\r\n";

	// Send
	$response['is_success'] = @mail($to, 'Contact From www.bartlewis.me', $message, $headers);
}

// How to respond?
if ($isAjax){
	if (!$response['is_success']){
		$response['message'] = 'Something went wrong... embarrassing.';
	}
	
	echo json_encode($response);
}
else{
	echo '
		<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="utf-8">
				<title>Contact | Bart Lewis | End to End Web Developer</title>
			</head>
			<body>';
	
	if ($response['is_success']){
		echo '<h1>Success</h1><p>Your message was sent successfully.</p>';
	}
	else{
		echo '<h1>Error</h1><p>There was an error sending your email. Please yell at Bart on the twitters: <a href="https://twitter.com/#!/bart_lewis">@bart_lewis</a></p>';
	}

	echo '</body></html>';
}
?>