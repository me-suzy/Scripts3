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
# $Id: product_images_modify.php,v 1.7 2002/05/15 05:34:49 verbic Exp $
#

if ($REQUEST_METHOD == "POST" && $mode=="product_images") {
#
# Upload additional product image
#
   if (($userfile!="none")&&($userfile!="")) {
		move_uploaded_file($userfile, "$file_temp_dir/$userfile_name");
		$userfile="$file_temp_dir/$userfile_name";
        $fd = fopen($userfile, "rb");
        $image = addslashes(fread($fd, filesize($userfile)));
        fclose($fd);
        list($image_size, $image_x, $image_y) = get_image_size($userfile);
		unlink($userfile);
        db_query("insert into images (productid, image, image_type, image_size, image_x, image_y, alt, avail) values ('$productid', '$image', '$userfile_type', '$image_size', '$image_x', '$image_y', '$alt', 'Y')");
    }
	header("Location: product_modify.php?productid=$productid#product_images");
	exit;
}

if ($mode=="update_availability") {
	if ($image_avail) {
		foreach ($image_avail as $key=>$value) {
			db_query ("UPDATE images SET avail='$value', alt='$image_alt[$key]' WHERE imageid='$key'");
		}
	}

	header ("Location: product_modify.php?productid=$productid&updated");
	exit;
}

if ($mode=="product_images_delete") {
#
# delete product image
#
    if($product_info) db_query("delete from images where imageid='$imageid' and productid='$productid'");
	header("Location: product_modify.php?productid=$productid#product_images");
	exit;
}

?>
