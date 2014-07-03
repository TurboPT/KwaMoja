<?php

include('includes/session.inc');
$Title = _('Import General Ledger Transactions');
include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme . '/images/maintenance.png" title="' . _('Import GL Payments Receipts Or Journals From CSV') . '" />' . ' ' . _('Import GL Payments Receipts Or Journals From CSV') . '</p>';

$FieldHeadings = array(
	'Date', //  0 'Transaction Date',
	'Account', //  1 'GL Account Code,
	'ChequeNo', //  2 'Cheque/Voucher Number',
	'Amount', //  3 'Amount',
	'Narrative', //  4 'Narrative'
	'Tag' //  5 'Tag reference'
);

if (isset($_FILES['userfile']) and $_FILES['userfile']['name']) { //start file processing
	//check file info
	$FileName = $_FILES['userfile']['name'];
	$TempName = $_FILES['userfile']['tmp_name'];
	$FileSize = $_FILES['userfile']['size'];
	$FieldTarget = 6;
	$InputError = 0;

	//get file handle
	$FileHandle = fopen($TempName, 'r');

	//get the header row
	$HeadRow = fgetcsv($FileHandle, 10000, ",");

	//check for correct number of fields
	if (count($HeadRow) != count($FieldHeadings)) {
		prnMsg(_('File contains ' . count($HeadRow) . ' columns, expected ' . count($FieldHeadings) . '. Try downloading a new template.'), 'error');
		fclose($FileHandle);
		include('includes/footer.inc');
		exit;
	}

	//test header row field name and sequence
	$head = 0;
	foreach ($HeadRow as $HeadField) {
		if (trim(mb_strtoupper($HeadField)) != trim(mb_strtoupper($FieldHeadings[$head]))) {
			prnMsg(_('File contains incorrect headers ' . mb_strtoupper($HeadField) . ' != ' . mb_strtoupper($FieldHeadings[$head]) . '. Try downloading a new template.'), 'error');
			fclose($FileHandle);
			include('includes/footer.inc');
			exit;
		}
		$head++;
	}

	//Get the next transaction number
	$TransNo = GetNextTransNo($_POST['TransactionType']);

	//Get the exchange rate to use between the transaction currency and the functional currency
	$SQL = "SELECT rate FROM currencies WHERE currabrev='" . $_POST['Currency'] . "'";
	$result = DB_query($SQL);
	$MyRow = DB_fetch_array($result);
	$ExRate = $MyRow['rate'];

	//start database transaction
	DB_Txn_Begin();

	//Total for transactions must come back to zero
	$TransactionTotal = 0;

	//loop through file rows
	$row = 1;
	while (($MyRow = fgetcsv($FileHandle, 10000, ",")) !== FALSE) {

		//check for correct number of fields
		$FieldCount = count($MyRow);
		if ($FieldCount != $FieldTarget) {
			prnMsg(_($FieldTarget . ' fields required, ' . $FieldCount . ' fields received'), 'error');
			fclose($FileHandle);
			include('includes/footer.inc');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		foreach ($MyRow as &$value) {
			$value = trim($value);
			$value = str_replace('"', '', $value);
		}

		//first off check that the account code actually exists
		$SQL = "SELECT COUNT(accountcode) FROM chartmaster WHERE accountcode='" . $MyRow[1] . "'";
		$result = DB_query($SQL);
		$testrow = DB_fetch_row($result);
		if ($testrow[0] == 0) {
			$InputError = 1;
			prnMsg(_('Account code' . ' ' . $MyRow[1] . ' ' . 'does not exist'), 'error');
		}

		//Then check that the date is in a correct format
		if (!Is_date($MyRow[0])) {
			$InputError = 1;
			prnMsg(_('The date "' . $MyRow[0] . '" is not in the correct format'), 'error');
		}

		//Then check that the tag ref is either zero, or exists in the tags table
		if ($MyRow[5] != 0) {
			$SQL = "SELECT COUNT(tagref) FROM tags WHERE tagref='" . $MyRow[5] . "'";
			$result = DB_query($SQL);
			$testrow = DB_fetch_row($result);
			if ($testrow[0] == 0) {
				$InputError = 1;
				prnMsg(_('Tag ref "' . $MyRow[5] . '" does not exist'), 'error');
			}
		}

		//Find the period number from the date
		$Period = GetPeriod($MyRow[0]);

		//All transactions must be in the same period
		if (isset($PreviousPeriod) and $PreviousPeriod != $Period) {
			$InputError = 1;
			prnMsg(_('All transactions must be in the same period'), 'error');
		}

		//Finally force the amount to be a double
		$MyRow[3] = (double) $MyRow[3];
		if ($InputError != 1) {

			//Firstly add the line to the gltrans table
			$SQL = "INSERT INTO gltrans (type,
										typeno,
										chequeno,
										trandate,
										periodno,
										account,
										narrative,
										amount,
										tag
									) VALUES (
										'" . $_POST['TransactionType'] . "',
										'" . $TransNo . "',
										'" . $MyRow[2] . "',
										'" . FormatDateForSQL($MyRow[0]) . "',
										'" . $Period . "',
										'" . $MyRow[1] . "',
										'" . $MyRow[4] . "',
										'" . round($MyRow[3] / $ExRate, 2) . "',
										'" . $MyRow[5] . "'
									)";

			$result = DB_query($SQL);

			if ($_POST['TransactionType'] != 0 and IsBankAccount($MyRow[1])) {

				//Get the exchange rate to use between the transaction currency and the bank account currency
				$SQL = "SELECT rate
							FROM currencies
							INNER JOIN bankaccounts
								ON currencies.currabrev=bankaccounts.currcode
							WHERE bankaccounts.accountcode='" . $MyRow[1] . "'";
				$result = DB_query($SQL);
				$MyRateRow = DB_fetch_array($result);
				$FuncExRate = $MyRateRow['rate'];
				$SQL = "INSERT INTO banktrans (transno,
												type,
												bankact,
												ref,
												chequeno,
												exrate,
												functionalexrate,
												transdate,
												banktranstype,
												amount,
												currcode
											) VALUES (
												'" . $TransNo . "',
												'" . $_POST['TransactionType'] . "',
												'" . $MyRow[1] . "',
												'" . $MyRow[4] . "',
												'" . $MyRow[2] . "',
												'" . ($ExRate / $FuncExRate) . "',
												'" . $FuncExRate . "',
												'" . FormatDateForSQL($MyRow[0]) . "',
												'" . _('Cheque') . "',
												'" . round($MyRow[3], 2) . "',
												'" . $_POST['Currency'] . "'
											)";
				$result = DB_query($SQL);
			}
			$PreviousPeriod = $Period;
			$TransactionTotal = $TransactionTotal + $MyRow[3];
		}

		if ($InputError == 1) { //this row failed so exit loop
			break;
		}
		$row++;

	}

	if ($InputError != 1 and round($TransactionTotal, 2) != 0) {
		$InputError = 1;
		prnMsg(_('The total of the transactions must balance back to zero'), 'error');
	}

	if ($InputError == 1) { //exited loop with errors so rollback
		prnMsg(_('Failed on row ' . $row . '. Batch import has been rolled back.'), 'error');
		DB_Txn_Rollback();
	} else { //all good so commit data transaction
		DB_Txn_Commit();
		prnMsg(_('Batch Import of') . ' ' . $FileName . ' ' . _('has been completed. All transactions committed to the database.'), 'success');
	}

	fclose($FileHandle);
	include('includes/GLPostings.inc');

} else { //show file upload form

	echo '<form onSubmit="return VerifyForm(this);" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" class="noPrint" enctype="multipart/form-data">';
	echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="page_help_text">' . _('This function loads a set of general ledger transactions from a comma separated variable (csv) file.') . '<br />' . _('The file must contain six columns, and the first row should be the following headers') . ':' . '<br />' . $FieldHeadings[0] . ', ' . $FieldHeadings[1] . ', ' . $FieldHeadings[2] . ', ' . $FieldHeadings[3] . ', ' . $FieldHeadings[4] . ', ' . $FieldHeadings[5] . '<br />' . _('followed by rows containing these six fields for each price to be uploaded.') . '<br />' . _('The total of the transactions must come back to zero. Debits are positive, credits are negative.') . '<br />' . _('All the transactions must be within the same accounting period.') . '<br />' . _('The Account field must have a corresponding entry in the chartmaster table.') . '</div>';

	echo '<br /><input type="hidden" name="MAX_FILE_SIZE" value="1000000" />';
	echo _('Select Transaction Type') . ':&nbsp;
			<select minlength="0" name="TransactionType">
				<option value=0>' . _('GL Journal') . '</option>
				<option value=1>' . _('GL Payment') . '</option>
				<option value=2>' . _('GL Receipt') . '</option>
			</select>&nbsp;&nbsp;';

	echo _('Select Currency') . ':&nbsp;<select minlength="0" name="Currency">';
	$SQL = "SELECT currency, currabrev, rate FROM currencies";
	$result = DB_query($SQL);
	if (DB_num_rows($result) == 0) {
		echo '</select>';
		prnMsg(_('No currencies are defined yet') . '. ' . _('Receipts cannot be entered until a currency is defined'), 'warn');

	} else {
		while ($MyRow = DB_fetch_array($result)) {
			if ($_SESSION['CompanyRecord']['currencydefault'] == $MyRow['currabrev']) {
				echo '<option selected="selected" value="' . $MyRow['currabrev'] . '">' . $MyRow['currency'] . '</option>';
			} else {
				echo '<option value="' . $MyRow['currabrev'] . '">' . $MyRow['currency'] . '</option>';
			}
		}
		echo '</select>';
	}
	echo _('Upload file') . ': <input name="userfile" type="file" />
			<input type="submit" name="submit" value="' . _('Send File') . '" />
		</div>
		</form>';

}

include('includes/footer.inc');

function IsBankAccount($Account) {

	$SQL = "SELECT accountcode FROM bankaccounts WHERE accountcode='" . $Account . "'";
	$result = DB_query($SQL);
	if (DB_num_rows($result) == 0) {
		return false;
	} else {
		return true;
	}
}

?>