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
# $Id: download.php,v 1.5 2002/04/22 17:10:29 mav Exp $
#
# This module adds support for downloading electronicaly distributed goods 
#

require "../smarty.php";
require "../config.php";

if (empty($id)) exit();

$query = "SELECT * FROM download_keys WHERE download_key = '$id'";
$res = func_query_first($query);
# If there is corresponding key in database and not expired
if ((count($res) > 0 )AND($res['expires'] > time())){
	
	# check if there is valid distribution for this product
	$productid = $res['productid'];
	$result = func_query_first("SELECT distribution, product, provider FROM products WHERE productid = '$productid'");
	$distribution = $result['distribution'];
	$provider = $result['provider'];  
	if (empty($provider) || $single_mode) $distribution = $files_dir_name.$distribution;
		else $distribution = $files_dir_name."/$provider".$distribution;

	if ($fd = @fopen ($distribution, "r")){
	    $contents = fread($fd, filesize ($distribution));
		fclose ($fd);
		#
		# Bugfix - content corrupted
		# $contents = addslashes($contents);
		#
		$fname = basename ($distribution);
		Header("Content-type: application/octet-stream");
        Header("Content-Disposition: attachment; filename=$fname");
		echo $contents;
	}
	else{ # If no such distributive
		$smarty->assign("product", $result['product']);
		$smarty->display("modules/Egoods/no_distributive.tpl");
		exit();
	}

}
else {
	$smarty->display("modules/Egoods/wrong_key.tpl");
	exit;
}
?>
