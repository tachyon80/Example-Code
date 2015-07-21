<?php
require_once 'bootstrap.php';
function compose_screen($form = false, $formFlag = 0) {
	include('includes/headerCode.inc');
	echo "<h1>Email Group</h1>";
	echo '<p>This form will help you send an Email message to the Bible study attendees.</p>';
	switch ($formFlag) {
		case 1:
			echo '<p class="redhedr">One or more form fields are empty.</p>';
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
	echo '<input type="text" id="senderSubject" name="senderSubject" size="75" value="'.htmlentities($form['senderSubject'], ENT_QUOTES, 'UTF-8').'">';
	echo '<span class="error">Must enter a subject</span>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<label for="senderText">Email Text</label>';
	echo '<textarea id="senderText" name="senderText" cols="100" rows="10">'.htmlentities($form['senderText'], ENT_QUOTES, 'UTF-8').'</textarea>';
	echo '<span class="error">Must enter text</span>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<input type="submit" name="submit" value="Send Email">';
	echo '<input type="hidden" name="emailSent" value="1">';
	echo '</fieldset>';
	echo '</form>';
}
function email_sent() {
	$formFlag = 0;
	$form['senderSubject'] = trim($_POST['senderSubject']);
	$form['senderEmail'] = trim($_POST['senderEmail']);
	$form['senderText'] = trim($_POST['senderText']);
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
	$mailTo = 'tachyon80@hotmail.com' . ', '; //Adam
	$mailTo .= '5127961551@messaging.sprintpcs.com' . ', '; //Clint
	$mailTo .= '5126239666@txt.att.net' . ', '; //Ragen
	$mailTo .= '5123505356@txt.att.net' . ', '; //Jason
	$mailSubject = $form['senderSubject'];
	$mailContent = $form['senderText'];
	$headers = 'From: ' . $form['senderEmail'] . "\r\n" . 'Reply-To: ' . $form['senderEmail'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	mail($mailTo, $mailSubject, $mailContent, $headers);
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