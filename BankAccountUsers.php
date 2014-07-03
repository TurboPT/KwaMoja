<?php

include('includes/session.inc');
$Title = _('Maintenance Of Bank Account Authorised Users');
include('includes/header.inc');

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/money_add.png" title="' . _('Bank Account Authorised Users') . '" alt="" />' . ' ' . $Title . '</p>';

if (isset($_POST['SelectedUser'])) {
	$SelectedUser = mb_strtoupper($_POST['SelectedUser']);
} elseif (isset($_GET['SelectedUser'])) {
	$SelectedUser = mb_strtoupper($_GET['SelectedUser']);
} else {
	$SelectedUser = '';
}

if (isset($_POST['SelectedBankAccount'])) {
	$SelectedBankAccount = mb_strtoupper($_POST['SelectedBankAccount']);
} elseif (isset($_GET['SelectedBankAccount'])) {
	$SelectedBankAccount = mb_strtoupper($_GET['SelectedBankAccount']);
}

if (isset($_POST['Cancel'])) {
	unset($SelectedBankAccount);
	unset($SelectedUser);
}

if (isset($_POST['Process'])) {
	if ($_POST['SelectedBankAccount'] == '') {
		echo prnMsg(_('You have not selected any bank account'), 'error');
		echo '<br />';
		unset($SelectedBankAccount);
		unset($_POST['SelectedBankAccount']);
	}
}

if (isset($_POST['submit'])) {

	$InputError = 0;

	if ($_POST['SelectedUser'] == '') {
		$InputError = 1;
		echo prnMsg(_('You have not selected an user to be authorised to use this bank account'), 'error');
		echo '<br />';
		unset($SelectedBankAccount);
	}

	if ($InputError != 1) {

		// First check the user is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bankaccountusers
			     WHERE accountcode= '" . $_POST['SelectedBankAccount'] . "'
				 AND userid = '" . $_POST['SelectedUser'] . "'";

		$checkresult = DB_query($checkSql);
		$checkrow = DB_fetch_row($checkresult);

		if ($checkrow[0] > 0) {
			$InputError = 1;
			prnMsg(_('The user') . ' ' . $_POST['SelectedUser'] . ' ' . _('already authorised to use this bank account'), 'error');
		} else {
			// Add new record on submit
			$SQL = "INSERT INTO bankaccountusers (accountcode,
												userid)
										VALUES ('" . $_POST['SelectedBankAccount'] . "',
												'" . $_POST['SelectedUser'] . "')";

			$msg = _('User') . ': ' . $_POST['SelectedUser'] . ' ' . _('has been authorised to use') . ' ' . $_POST['SelectedBankAccount'] . ' ' . _('bank account');
			$result = DB_query($SQL);
			prnMsg($msg, 'success');
			unset($_POST['SelectedUser']);
		}
	}
} elseif (isset($_GET['delete'])) {
	$SQL = "DELETE FROM bankaccountusers
		WHERE accountcode='" . $SelectedBankAccount . "'
		AND userid='" . $SelectedUser . "'";

	$ErrMsg = _('The bank account user record could not be deleted because');
	$result = DB_query($SQL, $ErrMsg);
	prnMsg(_('User') . ' ' . $SelectedUser . ' ' . _('has been un-authorised to use') . ' ' . $SelectedBankAccount . ' ' . _('bank account'), 'success');
	unset($_GET['delete']);
}

if (!isset($SelectedBankAccount)) {

	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedUser will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true. These will call the same page again and allow update/input or deletion of the records*/
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<table class="selection">'; //Main table

	echo '<tr><td>' . _('Select Bank Account') . ':</td><td><select name="SelectedBankAccount">';

	$SQL = "SELECT accountcode,
					bankaccountname
			FROM bankaccounts";

	$result = DB_query($SQL);
	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($MyRow = DB_fetch_array($result)) {
		if (isset($SelectedBankAccount) and $MyRow['accountcode'] == $SelectedBankAccount) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['accountcode'] . '">' . $MyRow['accountcode'] . ' - ' . $MyRow['bankaccountname'] . '</option>';

	} //end while loop

	echo '</select></td></tr>';

	echo '</table>'; // close main table
	DB_free_result($result);

	echo '<div class="centre"><input type="submit" name="Process" value="' . _('Accept') . '" />
				<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
			</div>';

	echo '</form>';

}

//end of ifs and buts!
if (isset($_POST['process']) OR isset($SelectedBankAccount)) {
	$SQLName = "SELECT bankaccountname
			FROM bankaccounts
			WHERE accountcode='" . $SelectedBankAccount . "'";
	$result = DB_query($SQLName);
	$MyRow = DB_fetch_array($result);
	$SelectedBankName = $MyRow['bankaccountname'];

	echo '<div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Authorised users for') . ' ' . $SelectedBankName . ' ' . _('bank account') . '</a></div>';
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="SelectedBankAccount" value="' . $SelectedBankAccount . '" />';

	$SQL = "SELECT bankaccountusers.userid,
					www_users.realname
			FROM bankaccountusers INNER JOIN www_users
			ON bankaccountusers.userid=www_users.userid
			WHERE bankaccountusers.accountcode='" . $SelectedBankAccount . "'
			ORDER BY bankaccountusers.userid ASC";

	$result = DB_query($SQL);

	echo '<table class="selection">';
	echo '<tr>
			<th colspan="3"><h3>' . _('Authorised users for bank account') . ' ' . $SelectedBankName . '</h3></th>
		</tr>';
	echo '<tr>
			<th>' . _('User Code') . '</th>
			<th>' . _('User Name') . '</th>
		</tr>';

	$k = 0; //row colour counter

	while ($MyRow = DB_fetch_array($result)) {
		if ($k == 1) {
			echo '<tr class="EvenTableRows">';
			$k = 0;
		} else {
			echo '<tr class="OddTableRows">';
			$k = 1;
		}

		printf('<td>%s</td>
			<td>%s</td>
			<td><a href="%s?SelectedUser=%s&amp;delete=yes&amp;SelectedBankAccount=' . $SelectedBankAccount . '" onclick="return confirm(\'' . _('Are you sure you wish to un-authorize this user?') . '\');">' . _('Un-authorize') . '</a></td>
			</tr>', $MyRow['userid'], $MyRow['realname'], htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), $MyRow['userid'], htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), $MyRow['userid']);
	}
	//END WHILE LIST LOOP
	echo '</table>';

	if (!isset($_GET['delete'])) {


		echo '<table  class="selection">'; //Main table

		echo '<tr>
				<td>' . _('Select User') . ':</td>
				<td><select name="SelectedUser">';

		$SQL = "SELECT userid,
						realname
				FROM www_users";

		$result = DB_query($SQL);
		if (!isset($_POST['SelectedUser'])) {
			echo '<option selected="selected" value="">' . _('Not Yet Selected') . '</option>';
		}
		while ($MyRow = DB_fetch_array($result)) {
			if (isset($_POST['SelectedUser']) AND $MyRow['userid'] == $_POST['SelectedUser']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $MyRow['userid'] . '">' . $MyRow['userid'] . ' - ' . $MyRow['realname'] . '</option>';

		} //end while loop

		echo '</select></td></tr>';

		echo '</table>'; // close main table
		DB_free_result($result);

		echo '<div class="centre">
				<input type="submit" name="submit" value="' . _('Accept') . '" />
				<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
			</div>';

		echo '</form>';

	} // end if user wish to delete
}

include('includes/footer.inc');
?>