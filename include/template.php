<?php
/**********************************************************************************
								LM Framework v3
								
	A simple PHP based application framework.
	
	Contact: pozniak.lukasz@gmail.com
	
	Copyright (c) 2005-2013, �ukasz Po�niak
	All rights reserved.

	Redistribution and use in source and binary forms, with or without modification,
	are permitted provided that the following conditions are met:
	
	Redistributions of source code must retain the above copyright notice,
	this list of conditions and the following disclaimer.
	Redistributions in binary form must reproduce the above copyright notice,
	this list of conditions and the following disclaimer in the documentation
	and/or other materials provided with the distribution.
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
	THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
	ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS
	BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
	OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
	OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
	WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
	OF THE POSSIBILITY OF SUCH DAMAGE.

**********************************************************************************/


function template_main() {
	global $LM_APP_NAME, $lmver, $LANG, $LM_READONLY;
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php">
	<title><?php echo("$LM_APP_NAME $lmver"); ?></title>
	<?php
	applycss(getcss());
	?>
        <!--<link rel="stylesheet" href="jquery-ui/css/ui-darkness/jquery-ui-1.10.3.custom.min.css" />-->
	<link rel="icon" href="favicon.ico" type="image/ico">
        <script type="text/javascript" src="jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="chart.js/Chart.min.js"></script>
        <script type="text/javascript" src="ajax.js"></script>
        <script type="text/javascript" src="skrypty.js"></script>
	<script type="text/javascript">
		function logoff() {
		logout=window.open("index.php?logoff=1","logoff_window","toolbar=0,location=0,scrollbars=0,resizable=0,height=64,width=64,top=100,left=100");
		logout.close();
		}</script>
	</head>
	<body text="#000000" bgcolor="#FFFFFF">
	<center>
	<br>
	<table class="tab-container">
	<tr><td width="100%" class="tab-horizbar">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr><td width="50%" align="left"><div class="top">Logged in as:<b> <?
			echo(getusername());
		?></b><br></div></td>
		<td width="50%"><div class="top2"><a href="?id=5"><img src="img/settings.gif" alt="Settings" style="vertical-align: middle;"></a><a href="index.php?logoff=1"><img src="img/log.gif" alt="Log off" style="vertical-align: middle;"> <b>Log off</b></a>
		<br></div></div></td></tr>
		</table>
	</td></tr>
	<tr><td width="100%" class="tab-logo">
	<img src="img/LMeve.png" alt="Logo">
	<?php //draw messages notify
	include("msgchk.php");
	include("custom_notifications.php");
	if ($LM_READONLY==1) echo($LANG['READONLY']);
	?>
	</td></tr>
	<tr><td width="100%" style="padding: 0;">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
	
	<? //draw menu
	if (!isset($_GET['id'])) {
		$id=getprefs();
		$id=$id['defaultPage'];
	} else {
		$id=$_GET['id'];
	}
	
	menu($id);
	?>
		</tr>
		</table>
	</td></tr>
	<tr><td width="100%" class="tab-horizbar">
	<br>
	</td></tr>
	<tr><td width="100%" style="padding: 0;">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr><td class="tab-links" style="width: 20%; vertical-align: top; padding: 5px;">
	<?php //draw links from db
	$sql="SELECT * FROM linki LIMIT 1;";
	$linki=db_asocquery($sql);
	$linki=$linki[0]['link'];
        
            echo(stripslashes(htmlspecialchars_decode($linki)));
            
	if (checkrights("Administrator")) { ?>
	<div style="text-align: center;"><hr><form method="get" action="">
	<input type="hidden" name="id" value="5">
	<input type="hidden" name="id2" value="6">
	<input type="hidden" name="nr" value="new">
	<input type="submit" value="Edit">
	</form></div>
	<?php } ?>
		</td><td width="1" class="tab-links">
			  <script type="text/javascript" src="resizer.js"></script>
		</td>
		<td width="80%" class="tab-main" valign="top">
			<?php
				showTabContents($id);
			?>
		</td>
		</tr>
		</table>
	
	</td></tr>
	<tr><td width="100%" class="tab-horizbar">
	<a href="index.php?id=254">About</a><br>
	</td></tr>
	</table>
	<?php
	include("copyright.php");
	?>
	
	</center>
	</body>
	</html>
	<?php
}

function template_locked() {
	global $LM_APP_NAME,$LM_DEFAULT_CSS,$LANG;
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<title><?php echo($LM_APP_NAME); ?> - Unavailable</title>
	<?php
	applycss($LM_DEFAULT_CSS);
	?>
	<link rel="icon" href="favicon.png" type="image/png">
	</head>
	<body text="#000000" bgcolor="#FFFFFF">
	<center>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" width="250" class="login">
	
	
	<tr><td><br><div class="tcen"><?php echo($LANG['MAINTENANCE']); ?>
	</div></td></tr>
	<tr><td><div class="tcen"><br>
		 <form method="get" action="">
			<input type="submit" value="Try again">
		 </form>
		 </div>
	</td></tr>
	</table>
	<?php
	include("copyright.php");
	?>
	</center>
	</body>
	</html>
	<?php
}

function template_login() {
	global $LM_APP_NAME,$LM_DEFAULT_CSS,$LANG;
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<title><?php echo($LM_APP_NAME); ?> - Please log in</title>
	<?
	applycss($LM_DEFAULT_CSS);
	?>
	</head>
	<body text="#000000" bgcolor="#FFFFFF">
	<center>
	<br>
	<form method="post" action="">
	
	<table border="0" cellspacing="0" cellpadding="0" width="250" class="login">
	
	
	<tr><td align="left"><br>User:</td></tr>
	<tr><td><div class="tcen">
			<input name="param[slogin]" size=20 type="text" value="" style="width: 140px">
		</div>
	</td></tr>
	<tr><td align="left">Password:</td></tr>
	<tr><td><div class="tcen">
		<input name="param[password]" size="20" type="password" style="width: 140px">
		<br><br>
		</div>
	</td></tr>
	<tr><td><div class="tcen">
			<input name="login" type="submit" value="Log in"><br>&nbsp;
		 </div>
	</td></tr>
	</table>
	<?php
	include('copyright.php');
	?>
	</form>
	</center>
	</body>
	</html>
	<?php
}

function template_badlogon() {
	global $LM_APP_NAME,$LM_DEFAULT_CSS,$LANG;	
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<title><?php echo($LM_APP_NAME); ?> - Please log in</title>
	<?
	applycss($LM_DEFAULT_CSS);
	?>
	<link rel="icon" href="favicon.ico" type="image/ico">
	</head>
	<body text="#000000" bgcolor="#FFFFFF">
	<center>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" width="250" class="login">
	<tr><td><div class="tcen"><br>Wrong username<br>or password.<br>&nbsp;</div></td></tr>
	<tr><td align="center"><div class="tcen"><br>
		 <form method="get" action="">
			<input type="submit" value="Back">
		 </form></div>
	</td></tr>
	</table>
	</center>
	<?
	 include('copyright.php');
	?>
	</body>
	</html>
	<?
}

function template_logout() {
	global $LM_APP_NAME,$LM_DEFAULT_CSS,$LANG;
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Refresh" CONTENT="3; URL=index.php">
	<title><?php echo($LM_APP_NAME); ?> - Logged out</title>
	<?php
	applycss($LM_DEFAULT_CSS);
	?>
	<link rel="icon" href="favicon.ico" type="image/ico">
	</head>
	<body text="#000000" bgcolor="#FFFFFF">
	<center>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" width="250" class="login">
	
	
	<tr><td><br><div class="tcen">Logged out.</div></td></tr>
	<tr><td><div class="tcen"><br>
		 <form method="get" action="">
			<input type="submit" value="Login again">
		 </form>
		 </div>
	</td></tr>
	</table>
	<?php
	include("copyright.php");
	?>
	</center>
	</body>
	</html>
	<?php
}

?>