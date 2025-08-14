<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: configuration.php,v 1.13 2002/05/08 06:40:18 lucky Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";
#
# Update configuration variables
# these variables are for internal use in PHP scripts 
#

if ($dopgp == 1) {
	func_update_pgp ();
}

if ($REQUEST_METHOD=="POST") {
	include "./safe_mode.php";

	db_query("update config set value='N' where type='checkbox' and category='$option'");

	while (list($key,$val) = each($HTTP_POST_VARS)) {
	db_query("update config set value='".($val=="on" ? "Y" : $val)."' where name='".$key."' and category='$option'");
	}

	if ($option == "PGP") {
		header ("Location: configuration.php?option=PGP&dopgp=1");
		exit;
	}
}

#
# Select default options tab
#
if (!$option) $option="General";

$options = func_query("select distinct(category) from config where category!='' ");
$configuration = func_query("select * from config where category='$option' order by orderby");

$smarty->assign("configuration", $configuration);
$smarty->assign("options", $options);
$smarty->assign("option", $option);
$smarty->assign("main","configuration");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
