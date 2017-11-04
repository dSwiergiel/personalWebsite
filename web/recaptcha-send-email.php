<?php
    
    $name = stripslashes($_POST['name']);
    $visitor_email = stripslashes($_POST['email']);
    $message = stripslashes($_POST['message']);
    $phone = stripslashes($_POST['phone']);
    $company = stripslashes($_POST['company']);
	$response = $_POST["g-recaptcha-response"];

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6Le7zTYUAAAAAJQqKNNqDmvMqTxaIbDZAQp1izH1',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);

	if ($captcha_success->success==false) {
        echo "<p>You only had to prove that you were not a robot, and you failed...</p>";
        exit;
	} else if ($captcha_success->success==true) {
    
		if(IsInjected($visitor_email))
        {
            echo "Bad email value!";
            exit;
        }
        
        $email_from = $visitor_email;//<== update the email address
        $email_subject = "New Email From Personal Website!";
        $email_body = "You have received a new message from your website.\n\n".
        
        "The message: \n
        $message \n\n".
        
        "Their info: 
        Name: $name. 
        Email: $visitor_email.
        Phone: $phone  . 
        Conpany: $company  . \n\n";
            
        $to = "devenswiergiel@gmail.com";//<== update the email address
        $headers = "From: $email_from \r\n";
        $headers .= "Reply-To: $visitor_email \r\n";
        //Send the email!
        mail($to,$email_subject,$email_body,$headers);
        
        //done. redirect to thank-you page.
        header('Location: message-confirmation');

    
    }
            // Function to validate against any email injection attempts
            function IsInjected($str)
            {
              $injections = array('(\n+)',
                          '(\r+)',
                          '(\t+)',
                          '(%0A+)',
                          '(%0D+)',
                          '(%08+)',
                          '(%09+)'
                          );
              $inject = join('|', $injections);
              $inject = "/$inject/i";
              if(preg_match($inject,$str))
                {
                return true;
              }
              else
                {
                return false;
              }
            }
            
    
    ?>