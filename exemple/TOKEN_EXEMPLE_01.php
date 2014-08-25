<?php

session_start(); //not required : the API can open the sessions

$pageID = ':-)';

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
	$myToken = $tokenApi->create($pageID);
}
catch(TokenAPIException $errorToken) {
	die((string)$errorToken);
	//It's an error of the database
}

if( $myToken == tokenAPI::ERR_TRY_LATER ) {//if it's an error
	/*you can do it to :
		$tokenApi == tokenAPI::ERR_TRY_LATER
	*/
	die('The database is unavailable. Please, try later !');
}

//generate a link secure
//you can use it during the regeneration of password, for example
?><!doctype html><html><head><meta charset="UTF-8"></head><body><a href="resultPage1.php?token=<?php

echo $myToken;
/*you can do it to :
	echo $tokenApi;
*/
echo '">This link use the tokenAPI !</a>';


//generate a token for a form
//you can use it in a contract form, for example
?><form action="resultPage1.php" method="get"><input type="hidden" name="token" value="<?php
echo $myToken;
/*you can do it to :
	echo $tokenApi;
*/
echo '">';


?><input type="submit" value="This form use the tokenAPI !"></form></body></html>