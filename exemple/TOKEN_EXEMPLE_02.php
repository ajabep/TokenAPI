<?php

$pageID = ':-)';

session_start();

include '../tokenAPI.class.php';

try {
	$tokenApi = new tokenAPI(new PDO('mysql:host=localhost;dbname=name_of_my_database', 'root', ''));
	/* you can do it to :
		$tokenApi=new tokenAPI(array('DSN'=>'mysql', 'host'=>'localhost', 'port'=>3306, 'database'=>'name_of_my_database', 'user'=>'root', 'password'=>''));
	or it
		$tokenApi=new tokenAPI(array('DSN'=>'mysql', 'database'=>'name_of_my_database'));
	because the values by default are :
		-'localhost' for the key 'host'
		-'root' for the key 'user'
		-'' for the key 'password'
	and the port is optional ! */
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
}

try {
	$myToken = $tokenApi->create($pageID, 1); //generate token
}
catch(TokenAPIException $errorToken) {
	die((string) $errorToken);
	//It's an error of the database
}

if( $myToken == tokenAPI::ERR_TRY_LATER ) {//if it's an error
	/*you can do it to :
		$tokenApi == tokenAPI::ERR_TRY_LATER
	*/
	die('The database is unavailable. Please, try later !');
}

//=======================

try {
	$result = $tokenApi->verify($myToken . ' ', $pageID . ' '); //verify false token with false page's ID
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::ERR_TOKEN_INVALID

//=======================

try {
	$result = $tokenApi->verify($myToken . ' ', $pageID); //verify false token with true page's ID
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::ERR_TOKEN_INVALID

//=======================

try {
	$result = $tokenApi->verify($myToken, $pageID . ' '); //verify true token with false page's ID
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::ERR_TOKEN_INVALID

//=======================

try {
	$result = $tokenApi->verify($myToken, $pageID); //verify true token with true page's ID, but to early
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::ERR_TO_EARLY

//=======================

$timeLimitMin = 30; //write here the TokenAPI::timeLimitMin (in second !!!)
set_time_limit($timeLimitMin + 5);
sleep($timeLimitMin + 1); //to be sure the time limit minimum is done

//=======================

try {
	$result = $tokenApi->verify($myToken, $pageID); //verify true token with true page's ID
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::TOKEN_VALID

//=======================

try {
	$result = $tokenApi->verify($myToken, $pageID); //verify true token with true page's ID, but already used
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

echo $result . '<br>'; //$result = TokenAPI::ERR_TOKEN_INVALID

//=======================

