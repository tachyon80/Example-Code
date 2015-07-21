<?php
//this page is for canceling the next study and pushing it to the next Tuesday or next regular Tuesday

//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);

require_once 'bootstrap.php';
function compose_screen($form = false, $formFlag = 0) {
	switch ($_GET['setting']) {
		case 'nextNonReg':
			$cancelType = 'nextNonReg';
			break;
		case 'nextReg':
		default:
			$cancelType = 'nextReg';
	}
	$dates = $_SESSION['dates'];
	include('includes/headerCode.inc');
	echo '<p>This page will help you cancel the next scheduled Bible study and reschedule it to the next Tuesday ';
	if ($cancelType === 'nextReg') {
		echo 'that would be a regular study night.</p>';
	} else {
		echo '(which may not be a regular study night).</p>';
	}
	switch ($formFlag) {
		case 1:
			echo '<p>One or more form fields are empty.</p>';
			break;
	}
	echo '<form action="'.$_SERVER[PHP_SELF].'" method="post" id="emailForm">';
	echo '<fieldset>';
	echo '<label for="senderEmail">Email Address</label>';
	if ($form['senderEmail']) {
		echo '<input type="text" id="senderEmail" name="senderEmail" size="75" value="'.htmlentities($form['senderEmail'], ENT_QUOTES, 'UTF-8').'">';
	} else {
		echo '<input type="text" id="senderEmail" name="senderEmail" size="75" value="jonathan@megalomedia.com">';
	}
	echo '<span class="error">Must use a valid Email address</span>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<label for="senderSubject">Subject</label>';
	if ($form['senderSubject']) {
		echo '<input type="text" id="senderSubject" name="senderSubject" size="75" value="'.htmlentities($form['senderSubject'], ENT_QUOTES, 'UTF-8').'">';
	} else {
		echo '<input type="text" id="senderSubject" name="senderSubject" size="75" value="Bible Study Cancelled">';
	}
	echo '<span class="error">Must enter a subject</span>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<label for="senderText">Email Text</label>';
	if ($form['senderText']) {
		echo '<textarea id="senderText" name="senderText" cols="100" rows="10">'.htmlentities($form['senderText'], ENT_QUOTES, 'UTF-8').'</textarea>';
	} else {
		echo '<textarea id="senderText" name="senderText" cols="100" rows="10">The Bible study on '.$dates->nextStudy->format('F jS \a\t g:i A').' has been cancelled. It has been rescheduled for ';
		if ($cancelType == 'nextReg') {
			echo $dates->nextRegStudy->format('F jS \a\t g:i A');
		} else {
			echo $dates->nextNonRegStudy->format('F jS \a\t g:i A');
		}
		echo '.</textarea>';
	}
	echo '<span class="error">Must enter text</span>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<input type="submit" name="submit" value="Cancel and Reschedule">';
	echo '<input type="hidden" name="emailSent" value="1">';
	echo '<input type="hidden" name="cancelType" value="'.$cancelType.'">';
	echo '</fieldset>';
	echo '</form>';
}
function email_sent() {
	$formFlag = 0;
	$form['senderSubject'] = trim($_POST['senderSubject']);
	$form['senderEmail'] = trim($_POST['senderEmail']);
	$form['senderText'] = trim($_POST['senderText']);
	$form['cancelType'] = $_POST['cancelType'];
	if ($form['cancelType'] !== 'nextNonReg') {
		$form['cancelType'] = 'nextReg';
	}
	$formErrors = array();
	if (!strlen($form['senderSubject'])) {
		$formErrors[] = 'senderSubject';
	}
	if (!strlen($form['senderEmail'])) {
		$formErrors[] = 'senderEmail';
	}
	if (!strlen($form['senderText'])) {
		$formErrors[] = 'senderText';
	}
	if (count($formErrors) > 0) {
		$formFlag = 1;
		compose_screen($form, $formFlag);
	} else {
		email_success($form);
	}
}
function email_success($form) {
	$dates = $_SESSION['dates'];
	$mailTo = 'tachyon80@hotmail.com' . ', '; //Adam
	$mailTo .= '5127961551@messaging.sprintpcs.com' . ', '; //Clint
	$mailTo .= '5126239666@txt.att.net' . ', '; //Ragen
	$mailTo .= '5123505356@txt.att.net' . ', '; //Jason
	$mailSubject = $form['senderSubject'];
	$mailContent = $form['senderText'];
	$headers = 'From: ' . $form['senderEmail'] . "\r\n" . 'Reply-To: ' . $form['senderEmail'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	mail($mailTo, $mailSubject, $mailContent, $headers);

	if ($form['cancelType'] == 'nextReg') {
		$dates->setNextRegStudy($dates->nextStudy);
		$dates->nextNonRegStudy = clone $dates->nextStudy;
		$dates->nextNonRegStudy->modify('+7 days');
		$dates->nextRegStudy = clone $dates->nextStudy;
		$dates->setNextRegStudy($dates->nextRegStudy);
	} else {
		$dates->nextStudy->modify('+7 days');
		$dates->nextNonRegStudy->modify('+7 days');
		if ($dates->nextRegStudy->format('F j') === $dates->nextStudy->format('F j')) {
			$dates->setNextRegStudy($dates->nextRegStudy);
		}
	}

	$db = Database::getInstance();
	$MySQLDate = $dates->nextStudy->format('Y-m-d H:i:00');
	$sql = "UPDATE meetings SET starttime=? WHERE id=?";
	$result = $db->queryOne($sql, array($MySQLDate, 1));
	Util::redirect('/biblestudy/');
}

if ($_POST['emailSent']) {
	email_sent();
} else {
	compose_screen();
}
?>
</body>
</html>