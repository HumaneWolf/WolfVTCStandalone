<?php

// Welcome to the WolfVTC Config!
// Before running the application, please fill out the variables in this file with the correct information.

//SECTION A - GENERAL:
$website['name'] = "WolfVTC Demo"; //Displayed in the page title.
$website['url'] = "//localhost/"; //Url of where it is installed, i.e. (http:)//example.com/. Include trailing slash.
$website['verification'] = FALSE; //Require users to verify their email? TRUE or FALSE.


//SECTION B - EMAIL:
$email['from'] = "noreply@gmail.com"; //From adress for automated emails.

$email['smtp'] = "smtp.gmail.com"; //SMTP Server Address.
$email['username'] = "noreply"; //Username to log into the SMTP server.
$email['password'] = "password123"; //Password to log into the SMTP server.

/*
You can require users to verify their email before being able to use the website.
Enabling that feature will help reduce spam registers and similar, but will require a SMTP server to send the verification emails.

The default settings above are for using a gmail account. Create an account, and insert the info above.
*/


//SECTION B - DATABASE (MYSQL):
$mysql['server'] = "localhost"; //Database server address/ip
$mysql['database'] = "wolfvtc"; //Database name
$mysql['user'] = "root"; //Database username
$mysql['password'] = ""; //Database user password