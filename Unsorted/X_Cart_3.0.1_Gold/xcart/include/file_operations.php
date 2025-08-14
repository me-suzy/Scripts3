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
# $Id: file_operations.php,v 1.10 2002/05/30 11:15:12 zorg Exp $
#

#
# Security check
#
$file = str_replace("..","",$file);
$dir = str_replace("..","",$dir);
$filename = str_replace("..","",$filename);

#
# Save file mode
#
if($REQUEST_METHOD=="POST" && $mode=="save_file") {
	
	if($fw = @fopen($root_dir.$filename,"w")) {
		fputs($fw, stripslashes($filebody));
		fclose($fw);
	}
	else $smarty->assign("io_error","1");

	$smarty->assign("filename",$filename);
	$smarty->assign("main","edit_file_message");
} elseif ($REQUEST_METHOD=="POST" && $mode=="restore" && $filename) {
#
# This facility restores the corrupted template from the repository
#
    if (!@copy($templates_repository.$filename, $root_dir.$filename))
        $smarty->assign("io_error","1");

    $smarty->assign("filename",$filename);
    $smarty->assign("main","edit_file_message");

} elseif ($REQUEST_METHOD=="POST" && $mode=="restore_all") {
#
# Restore all files from $template_repository
#
	function list_all_templates ($dir, $parent_dir) {

    	$all_files=array();

    	if(!$handle = opendir($dir)) return $all_files;

    	while (false !== ($file = readdir($handle)))
        	if (is_file($dir."/".$file) && substr($file,-4,4)==".tpl")
            	$all_files[$parent_dir."/".$file]="Q";
        	elseif (is_dir($dir."/".$file) && $file != "." && $file != "..")
             	$all_files=array_merge($all_files, list_all_templates ($dir."/".$file,$parent_dir."/".$file))
;
    	closedir($handle);
    	return $all_files;
	}

	$files_to_restore = list_all_templates ($templates_repository,"");

	if(!empty($files_to_restore)) {
		foreach($files_to_restore as $file_to_restore => $file_status) {
			
			echo "Restoring $file_to_restore - ";

    		if (!@copy($templates_repository.$file_to_restore, $root_dir.$file_to_restore))
				echo " <b><font color=red>FAILED TO RESTORE</font></b>";
			else
				echo " successfully restored";
	
			echo "<br>\n";
			flush();
		}
		exit;
	} else {
        $smarty->assign("io_error","3");
		$smarty->assign ("main", "copy_file_message");
	}

} elseif($file) {
#
# Edit file mode
#
	$mainfile=$root_dir.$file;

	$smarty->assign("filename", $file);
	$smarty->assign("filebody", file($mainfile));
	$smarty->assign("main","edit_file");

} else if ($REQUEST_METHOD=="POST" && $mode=="New directory") {
		if (is_dir($root_dir.$dir."/$new_directory")) {
			$smarty->assign("io_error","2");
		} else {
			if (!@mkdir($root_dir.$dir."/$new_directory", 0775)) 
				$smarty->assign("io_error","1");
		}
		$smarty->assign ("directory", $new_directory);
		$smarty->assign ("main","new_file_message");
} else if ($REQUEST_METHOD=="POST" && $mode=="New file") {

		if (file_exists ($root_dir.$dir."/$new_file")) {
			$smarty->assign("io_error","2");
		} else {
			if ($fw = @fopen($root_dir.$dir."/$new_file", "w")) {
				fclose ($fw);
			} else $smarty->assign("io_error","1");
		}

		$smarty->assign ("filename", $new_file);
		$smarty->assign ("main","new_file_message");
} else {
	$success = true;

	if ($REQUEST_METHOD=="POST" && $mode=="Delete") {

		if ((file_exists ($root_dir.$filename)) and (filetype ($root_dir.$filename)=="file")) {
			if (!unlink ($root_dir.$filename)) {
				$smarty->assign("io_error","1");
				$success = false;
			}
		}
		elseif (is_dir($root_dir.$filename)){
			func_rm_dir($root_dir.$filename);
		}
		else {
			echo $root_dir.$filename."<BR>";
			$smarty->assign("io_error","2");
			$success = false;
		}

		if (!$success) {
			$smarty->assign ("filename", $filename);
			$smarty->assign ("main", "delete_file_message");
		}
	}

	if ($REQUEST_METHOD=="POST" && $mode=="Upload") {

		if (file_exists ($root_dir.$dir."/$userfile_name")) {
			$success = false;
			$smarty->assign ("io_error", "1");
		} else {
			if ($userfile_name == "none" || $userfile_name == "") {
				$success = false;
				$smarty->assign ("io_error", "1");
			} else {
				if (!@copy ($userfile, $root_dir.$dir."/$userfile_name")) {
					$success = false;
					$smarty->assign ("io_error", "1");
				};
			}
		}
		
		if (!$success) {
			$smarty->assign ("filename", $userfile_name);
			$smarty->assign ("main", "upload_file_message");
		}
	}

	if ($REQUEST_METHOD=="POST" && $mode=="Copy to") {

		if (file_exists ($root_dir.$dir."/$copy_file")) {
			$success = false;
			$smarty->assign ("io_error", "1");
		} else {
			if (empty ($copy_file)) {
				$success = false;
				$smarty->assign ("io_error", "1");
			} else {
				if (!@copy ($root_dir.$filename, $root_dir.$dir."/$copy_file")) {
					$success = false;
					$smarty->assign ("io_error", "1");
				}
			}
		}
		if (!$success) {
			$smarty->assign ("filename", $copy_file);
			$smarty->assign ("main", "copy_file_message");
		}
	}
#
# Browse directory tree mode
#
	if ($success) {
		$maindir=$root_dir.$dir;

		if($dh = @opendir($maindir)) {
	 		while (($file = readdir($dh))!==false)
				if ($file!=".") $dir_entries[]=array("file"=>$file, "href"=>($file==".."?ereg_replace("\/[^\/]*$","",$dir):"$dir/$file"),"file_href"=>($root_url.$dir."/".$file),"filetype"=>filetype($maindir."/".$file));

			closedir($dh); 
		}
		$smarty->assign("dir_entries",$dir_entries);
		$smarty->assign("dir_entries_half",(int) (sizeof($dir_entries)/2));
		$smarty->assign("main","edit_dir");
	}
}

?>
