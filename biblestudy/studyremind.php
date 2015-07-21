<?php
require_once 'bootstrap.php';
$dates = new Dates();
if ($dates->showNextStudy()) {
	$dates->nextNonRegStudy = clone $dates->nextStudy;
	$dates->nextNonRegStudy->modify('+7 days');
	$dates->nextRegStudy = clone $dates->nextStudy;
	$dates->setNextRegStudy($dates->nextRegStudy);
}
$nowDate = $dates->rightNow->format('F j');
$studyDate = $dates->nextStudy->format('F j');
print_r($nowDate);
/*if ($nowDate === $studyDate) {
	$mailTo = 'tachyon80@hotmail.com' . ', '; //Adam
	$mailTo .= '5127961551@messaging.sprintpcs.com' . ', '; //Clint
	$mailTo .= '5126239666@txt.att.net' . ', '; //Ragen
	$mailTo .= '5123505356@txt.att.net' . ', '; //Jason
	$mailTo .= '3106149327@txt.att.net'; //Jonathan
	$mailSubject = 'Bible Study with Jonathan Tonight';
	$mailContent = 'Bible study tonight at Bannockburn Church.';
	$headers = 'From: reminder@NowzaradanNeighborhood.org' . "\r\n" . 'Reply-To: reminder@NowzaradanNeighborhood.org' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	mail($mailTo, $mailSubject, $mailContent, $headers);
}*/