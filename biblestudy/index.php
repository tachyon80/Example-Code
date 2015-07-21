<?php

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

require_once 'bootstrap.php';
include('includes/headerCode.inc');
?>
<div id="topBar"></div>
<div id="loginSpace">
<?php
if (User::loggedIn()) {
	echo '<a href="logout.php">Logout</a>';
} else {
	echo '<a href="login.php">Login</a>';
}
$dates = new Dates();
if (User::loggedIn()) {
	$_SESSION['dates'] = $dates;
}
if ($dates->showNextStudy()) {
	$displayStudy = $dates->nextStudy->format('F jS \a\t g:i A');
	$dates->nextNonRegStudy = clone $dates->nextStudy;
	$dates->nextNonRegStudy->modify('+7 days');
	$dates->nextRegStudy = clone $dates->nextStudy;
	$dates->setNextRegStudy($dates->nextRegStudy);
} else {
	$displayStudy = "I DON'T KNOW";
}
?>
</div>
<div id="page">
<div id="nextStudyNotice">
	<p>Next Bible study is <?php echo $displayStudy; ?></p>
</div>
<div id="mainTitle">
	<h1>Nowzaradan Bible Study</h1>
	<h3>"Where the scriptures and you collide."</h3>
	<p>(Crash helmets available on request.)</p>
</div>
<div id="menuContainer">
<?php
if (User::loggedIn()) {
	echo '<ul id="commandMenu">';
	echo '<li><a href="emailgroup.php">Email Group</a></li>';
	echo '<li><a href="cancelStudy.php?setting=nextReg">Cancel to Next Regular Study Night</a></li>';
	echo '<li><a href="cancelStudy.php?setting=nextNonReg">Cancel to Next Tuesday</a></li>';
}
?>
</ul>
</div>
<div id="quoteContainer">
	<p><span>&#8220;</span><?php include("quotes.php"); ?><span>&#8221;</span></p>
</div>
</div><!-- end #page -->
<div id="footer">
	<h2>Nowzaradan Neighborhood Ministries</h2>
	<span>"We know where you live."</span>
</div>
</body>
</html>