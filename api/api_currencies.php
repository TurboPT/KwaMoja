<?php

/* This function returns a list of the currency abbreviations
 * currently setup on KwaMoja
 */

function GetCurrencyList($user, $password) {
	$Errors = array();
	$db = db($user, $password);
	if (gettype($db) == 'integer') {
		$Errors[0] = NoAuthorisation;
		return $Errors;
	}
	$sql = 'SELECT currabrev FROM currencies';
	$result = api_DB_query($sql);
	$i = 0;
	while ($myrow = DB_fetch_array($result)) {
		$CurrencyList[$i] = $myrow[0];
		$i++;
	}
	return $CurrencyList;
}

/* This function takes as a parameter a currency abbreviation
 * and returns an array containing the details of the selected
 * currency.
 */

function GetCurrencyDetails($currency, $user, $password) {
	$Errors = array();
	$db = db($user, $password);
	if (gettype($db) == 'integer') {
		$Errors[0] = NoAuthorisation;
		return $Errors;
	}
	$sql = "SELECT * FROM currencies WHERE currabrev='" . $currency . "'";
	$result = api_DB_query($sql);
	return DB_fetch_array($result);
}

?>