<?php
/************************************************************************/
/* AWStats Access 3.0: Provides access to AWStats outside of cPanel     */
/* ============================================                         */
/* Created for and by members of TotalChoiceHosting.com                 */
/* Copyright (C) 2005 by TotalChoiceHosting.com                         */
/*                                                                      */
/* This file is part of AWStats Access.                                 */
/*  AWStats Access is free software; you can redistribute it and/or     */
/*  modify it under the terms of the GNU General Public License as      */
/*  published by the Free Software Foundation; either version 2 of      */
/*  the License, or (at your option) any later version.                 */
/*                                                                      */
/*  AWStats Access is distributed in the hope that it will be useful,   */
/*  but WITHOUT ANY WARRANTY; without even the implied warranty of      */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       */
/*  GNU General Public License for more details.                        */
/*                                                                      */
/*  You should have received a copy of the GNU General Public License   */
/*  along with Foobar; if not, write to                                 */
/* Free Software Foundation, Inc.                                      */
/*  59 Temple Place, Suite 330                                          */
/* Boston, MA  02111-1307  USA                                         */
/************************************************************************/

require_once("config.php");

if (!isset($PHP_AUTH_USER)) {

header('WWW-Authenticate: Basic realm="Site Statistics"');
header('HTTP/1.0 401 Unauthorized');
echo 'Authorization Required.';
exit;

} else if (isset($PHP_AUTH_USER)) {
if (($PHP_AUTH_USER != $username) || ($PHP_AUTH_PW != $password)) {

header('WWW-Authenticate: Basic realm="Site Statistics"');
header('HTTP/1.0 401 Unauthorized');
echo 'Authorization Required.';
exit;
}
else {
if($QUERY_STRING == ""){$query = "config=$site";}else{$query=$QUERY_STRING;};

$Previous = false;
if(isset($_POST))
{
foreach($_POST as $key => $value)
{
if($Previous)
{
 $POSTED .= "&";
}

$POSTED = "$key=$value";

$Previous = true;
}
}

$Curl = curl_init();
curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($Curl, CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($Curl, CURLOPT_URL, "https://$site:2083/awstats.pl?$query");
curl_setopt($Curl, CURLOPT_USERPWD, "$cpnlusername:$cpnlpassword");
if(isset($_POST))
{
curl_setopt($Curl, CURLOPT_POST, TRUE);
curl_setopt($Curl, CURLOPT_POSTFIELDS, $POSTED);
}
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, 1);
$results = curl_exec($Curl);
echo curl_error($Curl);
curl_close ($Curl);

for ($i = 0; $i < count($return_message_array); $i++) {
 $results = $results.$return_message_array[$i];
}

if($query == "config=$site"){$results = str_replace("src=\"", "src=\"?", $results);}

if($framename==index){$results = str_replace("src=\"", "src=\"index.php?", $results);}

$results = str_replace("action=\"", "action=\"index.php?", $results);
$results = str_replace("href=\"", "href=\"?", $results);
$results = str_replace("href=\"?http://", "href=\"http://", $results);
$results = str_replace("awstats.pl?", "", $results);
$results = str_replace("src=\"/images", "src=\"images", $results);

echo $results;
}
}
?>