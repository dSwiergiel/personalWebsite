<?php
// if(!isset($_POST['submit']))
// {
// 	//This page should not be accessed directly. Need to submit the form.
// 	echo "error; you need to submit the form!";
// }
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$message = $_POST['message'];
$phone = $_POST['phone'];
$company = $_POST['company'];

//Validate first
// if(empty($name)||empty($visitor_email)) 
// {
//     echo "Name and email are mandatory!";
//     exit;
// }

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
header('Location: message-confirmation.html');


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