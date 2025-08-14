<?php
/*
********************************************************
TinyButStrong - Template Engine for Pro and Beginners
------------------------
Version  : 2.01 for PHP >= 4.0.6
Date     : 2005-01-03
Web site : www.tinybutstrong.com
Author   : skrol29@freesurf.fr
********************************************************
This library is free software.
You can redistribute and modify it even for commercial usage,
but you must accept and respect the LPGL License (v2.1 or later).
*/

// Check PHP version
if (PHP_VERSION<'4.0.6') echo '<br><b>TinyButStrong Error</b> (PHP Version Check) : Your PHP version is '.PHP_VERSION.' while TinyButStrong needs PHP version 4.0.6 or higher.';
if (PHP_VERSION<'4.1.0') {
	function array_key_exists (&$key,&$array) {
		return key_exists($key,$array);
	}
}

// Render flags
define('TBS_NOTHING', 0);
define('TBS_OUTPUT', 1);
define('TBS_EXIT', 2);

// Special cache actions
define('TBS_DELETE', -1);
define('TBS_CANCEL', -2);
define('TBS_CACHENOW', -3);
define('TBS_CACHEONSHOW', -4);
define('TBS_CACHELOAD', -5);

// *********************************************

class clsTbsLocator {
	var $PosBeg = false;
	var $PosEnd = false;
	var $Enlarged = false;
	var $FullName = false;
	var $SubName = '';
	var $SubOk = false;
	var $SubLst = array();
	var $SubNbr = 0;
	var $PrmLst = array();
	var $MagnetId = false;
	var $BlockFound = false;
	var $FirstMerge = true;
	var $ConvProtec = true;
	var $ConvHtml = true;
	var $ConvBr = true;
	var $ConvEsc = false;
	var $ConvWS = false;
	var $ConvLook = false;
}

// *********************************************

class clsTbsDataSource {

var $Type = false;
var $SubType = 0;
var $SrcId = false;
var $Query = '';
var $RecSet = false;
var $RecKey = '';
var $RecNum = 0;
var $RecNumInit = 0;
var $RecSaving = false;
var $RecSaved = false;
var $RecBuffer = false;
var $CurrRec = false;
var $PrevRec = array();
var $Parent = false;
var $BlockName = '';

function DataAlert($Msg) {
	return $this->Parent->meth_Misc_Alert('MergeBlock '.$this->Parent->ChrOpen.$this->BlockName.$this->Parent->ChrClose,$Msg);
}

function DataPrepare(&$SrcId,&$Parent) {

	$this->SrcId = &$SrcId;
	$this->Parent = &$Parent;

	if (is_array($SrcId)) {
		$this->Type = 0;
	} elseif (is_resource($SrcId)) {

		$Key = get_resource_type($SrcId);
		switch ($Key) {
		case 'mysql link'            : $this->Type = 1; break;
		case 'mysql link persistent' : $this->Type = 1; break;
		case 'mysql result'          : $this->Type = 1; $this->SubType = 1; break;
		case 'pgsql link'            : $this->Type = 8; break;
		case 'pgsql link persistent' : $this->Type = 8; break;
		case 'pgsql result'          : $this->Type = 8; $this->SubType = 1; break;
		case 'sqlite database'       : $this->Type = 9; break;
		case 'sqlite database (persistent)'	: $this->Type = 9; break;
		case 'sqlite result'         : $this->Type = 9; $this->SubType = 1; break;
		default :
			$SubKey = 'resource type';
			$this->Type = 7;
			$x = strtolower($Key);
			$x = str_replace('-','_',$x);
			$Function = '';
			$i = 0;
			$iMax = strlen($x);
			while ($i<$iMax) {
				if (($x[$i]==='_') or (($x[$i]>='a') and ($x[$i]<='z')) or (($x[$i]>='0') and ($x[$i]<='9'))) {
					$Function .= $x[$i];
					$i++;
				} else {
					$i = $iMax;
				}
			}
		}

	} elseif (is_string($SrcId)) {

		switch (strtolower($SrcId)) {
		case 'array' : $this->Type = 0; $this->SubType = 1; break;
		case 'clear' : $this->Type = 0; $this->SubType = 3; break;
		case 'mysql' : $this->Type = 1; $this->SubType = 2; break;
		case 'text'  : $this->Type = 4; break;
		case 'num'   : $this->Type = 6; break;
		default :
			$Key = $SrcId;
			$SubKey = 'keyword';
			$this->Type = 7;
			$Function = $SrcId;
		}

	} elseif (is_object($SrcId)) {
		if (method_exists($SrcId,'tbsdb_open')) {
			if (!method_exists($SrcId,'tbsdb_fetch')) {
				$this->Type = $this->DataAlert('The expected method \'tbsdb_fetch\' is not found for the class '.get_class($SrcId).'.');
			} elseif (!method_exists($SrcId,'tbsdb_close')) {
				$this->Type = $this->DataAlert('The expected method \'tbsdb_close\' is not found for the class '.get_class($SrcId).'.');
			} else {
				$this->Type = 10;
			}
		} else {
			$Key = get_class($SrcId);
			$SubKey = 'object type';
			$this->Type = 7;
			$Function = $Key;
		}
	} elseif ($SrcId===false) {
		$this->DataAlert('The specified source is set to FALSE. Maybe your connection has failed.');
	} else {
		$this->DataAlert('Unsupported variable type : \''.gettype($SrcId).'\'.');
	}

	if ($this->Type===7) {
		$FctOpen  = 'tbsdb_'.$Function.'_open';
		$FctFetch = 'tbsdb_'.$Function.'_fetch';
		$FctClose = 'tbsdb_'.$Function.'_close';
		if (function_exists($FctOpen)) {
			if (function_exists($FctFetch)) {
					if (function_exists($FctClose)) {
						$this->FctOpen = $FctOpen;
						$this->FctFetch = $FctFetch;
						$this->FctClose = $FctClose;
					} else {
					$this->Type = $this->DataAlert('The expected custom function \''.$FctClose.'\' is not found.');
				}
			} else {
				$this->Type = $this->DataAlert('The expected custom function \''.$FctFetch.'\' is not found.');
			}
		} else {
			$this->Type = $this->DataAlert('The data source Id \''.$Key.'\' is an unsupported '.$SubKey.'. And the corresponding custom function \''.$FctOpen.'\' is not found.');
		}
	}

	return ($this->Type!==false);

}

function DataOpen(&$Query,&$PageSize,&$PageNum,&$RecStop) {

	// Init values
	$this->CurrRec = &tbs_Misc_UnlinkVar(true); // Just to unlink
	if ($this->RecSaved) {
		$this->FirstRec = true;
		$this->RecKey = &tbs_Misc_UnlinkVar('');
		$this->RecNum = $this->RecNumInit;
		return true;
	}
	$this->RecSet = &tbs_Misc_UnlinkVar(false);
	$this->RecNumInit = 0;
	$this->RecNum = 0;

	switch ($this->Type) {
	case 0: // Array
		if (($this->SubType===1) and (is_string($Query))) $this->SubType = 2;
		if ($this->SubType===0) {
			$this->RecSet = &$this->SrcId;
		} elseif ($this->SubType===1) {
			if (is_array($Query)) {
				$this->RecSet = &$Query;
			} else {
				$this->DataAlert('Type \''.gettype($Query).'\' not supported for the Query Parameter going with \'array\' Source Type.');
			}
		} elseif ($this->SubType===2) {
			//Found the global variable name and the sub-keys
			$Pos = strpos($Query,'[');
			if ($Pos===false) {
				$VarName = $Query;
				$Keys = array();
			} else {
				$VarName = substr($Query,0,$Pos);
				$Keys = substr($Query,$Pos+1,strlen($Query)-$Pos-2);
				$Keys = explode('][',$Keys);
			}
			// Check variable and sub-keys
			if (isset($GLOBALS[$VarName])) {
				$Var = &$GLOBALS[$VarName];
				if (is_array($Var)) {
					$Ok = true;
					$KeyMax = count($Keys)-1;
					$KeyNum = 0;
					while ($Ok and ($KeyNum<=$KeyMax)) {
						if (isset($Var[$Keys[$KeyNum]])) {
							$Var = &$Var[$Keys[$KeyNum]];
							$KeyNum++;
							if (!is_array($Var)) $Ok = $this->DataAlert('Invalid query \''.$Query.'\' because item \''.$VarName.'['.implode('][',array_splice($Keys,0,$KeyNum)).']\' is not an array.');
						} else {
							$Ok = false; // Item not found => not an error, considered as a query with empty result.
							$this->RecSet = array();
						}
					}
					if ($Ok) $this->RecSet = &$Var;
				} else {
					$this->DataAlert('Invalid query \''.$Query.'\' because global variable \''.$VarName.'\' is not an array.');
				}
			} else {
				$this->DataAlert('Invalid query \''.$Query.'\' because global variable \''.$VarName.'\' is not found.');
			}
		} elseif ($this->SubType===3) { // Clear
			$this->RecSet = array();
		}
		// First record
		if ($this->RecSet!==false) {
			$this->RecNbr = $this->RecNumInit + count($this->RecSet);
			$this->FirstRec = true;
			$this->RecSaved = true;
			$this->RecSaving = false;
		}
		break;
	case 1: // MySQL
		switch ($this->SubType) {
		case 0: $this->RecSet = @mysql_query($Query,$this->SrcId); break;
		case 1: $this->RecSet = $this->SrcId; break;
		case 2: $this->RecSet = @mysql_query($Query); break;
		}
		if ($this->RecSet===false) $this->DataAlert('MySql error message when opening the query: '.mysql_error());
		break;
	case 4: // Text
		if (is_string($Query)) {
			$this->RecSet = &$Query;
		} else {
			$this->RecSet = ''.$Query;	
		}
		$PageSize = 0;
		break;
	case 6: // Num
		$this->RecSet = true;
		$this->NumMin = 1;
		$this->NumMax = 1;
		$this->NumStep = 1;
		if (is_array($Query)) {
			if (isset($Query['min'])) $this->NumMin = $Query['min'];
			if (isset($Query['step'])) $this->NumStep = $Query['step'];
			if (isset($Query['max'])) {
				$this->NumMax = $Query['max'];
			} else {
				$this->RecSet = $this->DataAlert('The \'num\' source is an array that has no value for the \'max\' key.');
			}
			if ($this->NumStep==0) $this->RecSet = $this->DataAlert('The \'num\' source is an array that has a step value set to zero.');
		} else {
			$this->NumMax = ceil($Query);
		}
		if ($this->RecSet) {
			if ($this->NumStep>0) {
				$this->NumVal = $this->NumMin;
			} else {
				$this->NumVal = $this->NumMax;
			}
		}
		break;
	case 7: // Custom function
		$FctOpen = $this->FctOpen;
		$this->RecSet = $FctOpen($this->SrcId,$Query);
		break;
	case 8: // PostgreSQL
		switch ($this->SubType) {
		case 0: $this->RecSet = @pg_query($this->SrcId,$Query); break;
		case 1: $this->RecSet = $this->SrcId; break;
		}
		if ($this->RecSet===false) $this->DataAlert('PostgreSQL error message when opening the query: '.pg_last_error($this->SrcId));
		break;
	case 9: // SQLite
		switch ($this->SubType) {
		case 0: $this->RecSet = @sqlite_query($this->SrcId,$Query); break;
		case 1: $this->RecSet = $this->SrcId; break;
		}
		if ($this->RecSet===false) $this->DataAlert('SQLite error message when opening the query:'.sqlite_error_string(sqlite_last_error($this->SrcId)));
		break;
	case 10: // Custom method
		$this->RecSet = $this->SrcId->tbsdb_open($this->SrcId,$Query);
		break;
	}	

	if ($this->Type===0) {
		$this->RecKey = &tbs_Misc_UnlinkVar('');
	} else {
		if ($this->RecSaving) $this->RecBuffer = &tbs_Misc_UnlinkVar(array());
		$this->RecKey = &$this->RecNum; // Not array: RecKey = RecNum
	}

	//Goto the page
	if (($this->RecSet!==false) and ($PageSize>0)) {
		if ($PageNum>0) {
			// We pass all record until the asked page
			$RecStop = ($PageNum-1) * $PageSize;
			while ($this->RecNum<$RecStop) {
				$this->DataFetch();
				if ($this->CurrRec===false) $RecStop=$this->RecNum;	
			}
			if ($this->CurrRec!==false) $RecStop = $PageNum * $PageSize;
			$this->RecNumInit = $this->RecNum; // Useful if RecSaved
		} elseif ($PageNum==-1) { // Goto end of the recordset
			// Read records, saving the last page in $this->RecBuffer
			$i = 0;
			$n = 0;
			$x = true;
			$this->RecBuffer = &tbs_Misc_UnlinkVar(array());
			$this->RecSaving = true;
			while ($x) {
				$this->DataFetch();
				if ($this->CurrRec===false) {
					$x = false;
				} else {
					$i++;
					if ($i>$PageSize) {
						$this->RecBuffer = array();
						$i = 1;
						$n += $PageSize;
					}
				}
			}
			// Close the real recordset source
			$this->DataClose();
			$this->RecNumInit = $n;
		} else {
			$RecStop = 1;
		}
	}

	return ($this->RecSet!==false);

}

function DataFetch() {

	if ($this->RecSaved) {
		if ($this->RecNum<$this->RecNbr) {
			if ($this->FirstRec) {
				if ($this->SubType===2) { // From string
					reset($this->RecSet);
					$this->RecKey = key($this->RecSet);
					$this->CurrRec = &$this->RecSet[$this->RecKey];
				} else {
					$this->CurrRec = reset($this->RecSet);
					$this->RecKey = key($this->RecSet);
				}
				$this->FirstRec = false;
			} else {
				if ($this->SubType===2) { // From string
					next($this->RecSet);
					$this->RecKey = key($this->RecSet);
					$this->CurrRec = &$this->RecSet[$this->RecKey];
				} else {
					$this->CurrRec = next($this->RecSet);
					$this->RecKey = key($this->RecSet);
				}
			}
			if (!is_array($this->CurrRec)) $this->CurrRec = array('key'=>$this->RecKey, 'val'=>$this->CurrRec);
			$this->RecNum++;
		} else {
			$this->CurrRec = &tbs_Misc_UnlinkVar(false);
		}
		return;
	}

	switch ($this->Type) {
	case 1: // MySQL
		$this->CurrRec = mysql_fetch_assoc($this->RecSet);
		break;
	case 4: // Text
		if ($this->RecNum===0) {
			if ($this->RecSet==='') {
				$this->CurrRec = false;
			} else {
				$this->CurrRec = &$this->RecSet;
			}
		} else {
			$this->CurrRec = false;
		}
		break;
	case 6: // Num
		if (($this->NumVal>=$this->NumMin) and ($this->NumVal<=$this->NumMax)) {
			$this->CurrRec = array('val'=>$this->NumVal);
			$this->NumVal += $this->NumStep;
		} else {
			$this->CurrRec = false;
		}
		break;
	case 7: // Custom function
		$FctFetch = $this->FctFetch;
		$this->CurrRec = $FctFetch($this->RecSet,$this->RecNum+1);
		break;
	case 8: // PostgreSQL
		$this->CurrRec = @pg_fetch_array($this->RecSet,$this->RecNum,PGSQL_ASSOC); // warning comes when no record left.
		break;
	case 9: // SQLite
		$this->CurrRec = sqlite_fetch_array($this->RecSet,SQLITE_ASSOC);
		break;
	case 10: // Custom method
		$this->CurrRec = $this->SrcId->tbsdb_fetch($this->RecSet,$this->RecNum+1);
		break;
	}	

	// Set the row count
	if ($this->CurrRec!==false) {
		$this->RecNum++;
		if ($this->RecSaving) $this->RecBuffer[$this->RecKey] = $this->CurrRec;
	}

}

function DataClose() {
	if ($this->RecSaved) return;
	switch ($this->Type) {
	case 1: mysql_free_result($this->RecSet); break;
	case 7: $FctClose=$this->FctClose; $FctClose($this->RecSet); break;
	case 8: pg_free_result($this->RecSet); break;
	case 10: $this->SrcId->tbsdb_close($this->RecSet); break;
	}
	if ($this->RecSaving) {
		$this->RecSet = &$this->RecBuffer;
		$this->RecNbr = $this->RecNumInit + count($this->RecSet);
		$this->RecSaving = false;
		$this->RecSaved = true;
	}
}

}

// *********************************************

class clsTinyButStrong {

// Public properties
var $Source = ''; // Current result of the merged template
var $Render = 3;
var $HtmlCharSet = '';
var $TplVars = array();
var $VarPrefix = '';
// Private properties
var $_LastFile = ''; // The last loaded template file
var $_CacheFile = false; // The name of the file to save the content in.
var $_DebugTxt = '';
var $_HtmlCharFct = false;
// Used to be globals
var $ChrOpen = '[';
var $ChrClose = ']';
var $ChrVal = '[val]';
var $ChrProtect = '&#91;';
var $CacheMask = 'cache_tbs_*.php';
var $TurboBlock = true;

function clsTinyButStrong($Chr='',$VarPrefix='') {
	if ($Chr!=='') {
		if (strlen($Chr)==2) {
			$this->ChrOpen = $Chr[0];
			$this->ChrClose = $Chr[1];
			$this->ChrVal = $this->ChrOpen.'val'.$this->ChrClose;
			$this->ChrProtect = '&#'.ord($this->ChrOpen).';';
		} else {
			$this->meth_Misc_Alert('Creating instance','Bad Opening and Closing characters.');
		}
	}
	$this->VarPrefix = $VarPrefix;
	//Cache for formats
	if (!isset($GLOBALS['_tbs_FrmMultiLst'])) {
		$GLOBALS['_tbs_FrmMultiLst'] = array();
		$GLOBALS['_tbs_FrmSimpleLst'] = array();
	}
}

// Public methods
function LoadTemplate($File,$HtmlCharSet='') {
	//Find charset (deprecated)
	if ($HtmlCharSet==-1) {
		$this->HtmlCharSet = '';
		$Pos = 0;
		while ($Loc = tbs_Html_FindTag($this->Source,'META',true,$Pos,true,1,true)) {
			$Pos = $Loc->PosEnd + 1;
			if (isset($Loc->PrmLst['http-equiv'])) {
				if (strtolower($Loc->PrmLst['http-equiv'])==='content-type') {
					if (isset($Loc->PrmLst['content'])) {
						$x = ';'.strtolower($Loc->PrmLst['content']).';';
						$x = str_replace(' ','',$x);
						$p = strpos($x,';charset=');
						if ($p!==false) {
							$x = substr($x,$p+strlen(';charset='));
							$p = strpos($x,';');
							if ($p!==false) $x = substr($x,0,$p);
							$this->HtmlCharSet = $x;
							$Loc = false;
						}
					}
				}
			}
		}
	}
	// Load the file
	$x = '';
	if (tbs_Misc_GetFile($x,$File)===false) return $this->meth_Misc_Alert('LoadTemplate Method','Unable to read the file \''.$File.'\'.');
	$this->_LastFile = $File;		
	if (($HtmlCharSet!==false) and !is_string($HtmlCharSet)) {
		$this->meth_Misc_Alert('LoadTemplate Method','CharSet is not a string.');
		$HtmlCharSet = '';
	}
	// Manage Source and CharSet
	if ($HtmlCharSet==='+') {
		$this->Source .= $x;
	} else {
		// Initialize
		$this->_HtmlCharFct = false;
		$this->Source = $x;
		$this->TplVars = array();
		if ((strlen($HtmlCharSet)>0) and ($HtmlCharSet[0]==='=')) {
			$this->HtmlCharSet = substr($HtmlCharSet,1);
			if (function_exists($this->HtmlCharSet)) {
				$this->_HtmlCharFct = true;
			} else {
				$this->meth_Misc_Alert('LoadTemplate Method','Function '.$this->HtmlCharSet.'() for text conversion is not found.');
				$this->HtmlCharSet = '';
			}
		} else {
			$this->HtmlCharSet = $HtmlCharSet;
		}
	}
	// Include files
	$this->meth_Merge_Auto2($this->Source,'onload',true,true);
	return true;
}

function GetBlockSource($BlockName,$List=false) {
	$x = false;
	if ($List) {
		$SrcLst = array();
		$Nbr = 0;
		$Pos = 0;
		while ($Loc = $this->meth_Locator_FindBlockNext($this->Source,$BlockName,$Pos,'.',false,$x)) {
			$Nbr++;
			$SrcLst[$Nbr] = $Loc->BlockSrc;
			$Pos = $Loc->PosEnd;
			$x = false;
		}
		return $SrcLst;
	} else {
		$Loc = $this->meth_Locator_FindBlockNext($this->Source,$BlockName,0,'.',false,$x);
		if ($Loc===false) {
			return false;
		} else {
			return $Loc->BlockSrc;
		}
	}
}

function MergeBlock($BlockName,$SrcId,$Query='',$PageSize=0,$PageNum=0,$RecKnown=0) {
	if ($SrcId==='cond') {
		$Nbr = 0;
		$BlockLst = explode(',',$BlockName);
		foreach ($BlockLst as $Block) {
			$Nbr += $this->meth_Merge_Auto2($this->Source,$Block,false,false);
		}
		return $Nbr;
	} else {
		return $this->meth_Merge_Block($this->Source,$BlockName,$SrcId,$Query,$PageSize,$PageNum,$RecKnown);
	}
}

function MergeField($Name,$Value,$Fct=false) {
	if ($Fct) {
		if (function_exists($Value)) {
			$PosBeg = 0;
			while ($Loc = $this->meth_Locator_FindTbs($this->Source,$Name,$PosBeg,'.')) {
				$PosBeg = $this->meth_Locator_Replace($this->Source,$Loc,@$Value($Loc->SubName,$Loc->PrmLst),false);
			}
		} else {
			$this->meth_Misc_Alert('MergeField Method','Custom function \''.$Value.'\' is not found.');
		}
	} else {
		$this->meth_Merge_Field($this->Source,$Name,$Value,'.',true,false);
	}
}

function MergeSpecial($Type) {
	$Type = strtolower($Type);
	$this->meth_Merge_Special($Type);
}

function MergeNavigationBar($BlockName,$Options,$PageCurr,$RecCnt=-1,$PageSize=1) {
	return $this->meth_Merge_NavigationBar($this->Source,$BlockName,$Options,$PageCurr,$RecCnt,$PageSize);
}

function Show($Render='') {

	if ($Render==='') $Render = $this->Render;

	$this->meth_Merge_Special('onshow,var');

	if ($this->_DebugTxt!=='') $this->Source = $this->_DebugTxt.$this->Source;
	if ($this->_CacheFile!==false) $this->meth_Cache_Save($this->_CacheFile,$this->Source);
	if (($Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source;
	if (($Render & TBS_EXIT) == TBS_EXIT) exit;

}

function CacheAction($CacheId,$Action=3600,$Dir='') {

	$CacheId = trim($CacheId);
	$Res = false;

	if ($Action===TBS_CANCEL) { // Cancel cache save if any
		$this->_CacheFile = false;
	} elseif ($CacheId === '*') {
		if ($Action===TBS_DELETE) $Res = tbs_Cache_DeleteAll($Dir,$this->CacheMask);
	} else {
		$CacheFile = tbs_Cache_File($Dir,$CacheId,$this->CacheMask);
		if ($Action===TBS_CACHENOW) {
			$this->meth_Cache_Save($CacheFile,$this->Source);
		} elseif ($Action===TBS_CACHELOAD) {
			if (file_exists($CacheFile)) {
				if (tbs_Misc_GetFile($this->Source,$CacheFile)) {
					$this->_CacheFile = $CacheFile;
					$Res = true;
				}
			}
			if ($Res===false)	$this->Source = '';
		} elseif ($Action===TBS_DELETE) {
			if (file_exists($CacheFile)) @unlink($CacheFile);
		} elseif ($Action===TBS_CACHEONSHOW) {
			$this->_CacheFile = $CacheFile;
			@touch($CacheFile);
		} elseif($Action>=0) {
			$Res = tbs_Cache_IsValide($CacheFile,$Action);
			if ($Res) { // Load the cache
				$this->_CacheFile = false;
				if (tbs_Misc_GetFile($this->Source,$CacheFile)) {
					if (($this->Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source;
					if (($this->Render & TBS_EXIT) == TBS_EXIT) Exit;
				} else {
					$this->meth_Misc_Alert('CacheAction Method','Unable to read the file \''.$CacheFile.'\'.');
					$Res==false;
				}
			} else {
				// The result will be saved in the cache when the Show() method is called
				$this->_CacheFile = $CacheFile;
				@touch($CacheFile);
			}
		}
	}

	return $Res;

}

// Hidden methods
function DebugPrint($Txt) {
	if ($Txt===false) {
		$this->_DebugTxt = '';
	} else {
		$this->_DebugTxt .= 'Debug: '.htmlentities($Txt).'<br>';
	}
}

function DebugLocator($Names) {
// Add information at _DebugTxt that describes all locators with the given name.
	$Nl = "<br>\n";
	$Break = '-------------------'.$Nl;
	$ColOn = '<font color="#993300">';
	$ColOff = '</font>';
	$NbrTot = 0;
	$NameArray = explode(',',$Names);
	$Msg = '';
	foreach ($NameArray as $Name) {
		$x = '';
		$Pos = 0;
		$Nbr = 0;
		while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$Pos,'.')) {
			$Pos = $Loc->PosBeg+1;
			$Nbr++;
			$NbrTot++;
			if (isset($Loc->PrmLst['block'])) {
				if ($Loc->PrmLst['block']==='begin') {
					$Type = 'Type = '.$ColOn.'Block begining'.$ColOff.', Syntax = '.$ColOn.'Explicit'.$ColOff;
				} elseif ($Loc->PrmLst['block']==='end') {
					$Type = 'Type = '.$ColOn.'Block ending'.$ColOff.', Syntax = '.$ColOn.'Explicit'.$ColOff;
				} elseif ($Loc->SubOk===false) {
					$Type = 'Type = '.$ColOn.'Block'.$ColOff.', Syntax = '.$ColOn.'Relative'.$ColOff.', Html tag = '.$ColOn.htmlentities($Loc->PrmLst['block']).$ColOff;
				} else {
					$Type = 'Type = '.$ColOn.'Block & Field'.$ColOff.', Syntax = '.$ColOn.'Simplified'.$ColOff.', Html tag = '.$ColOn.htmlentities($Loc->PrmLst['block']).$ColOff;
				}
			} else {
				$Type = 'Type = '.$ColOn.'Field'.$ColOff;
			}
			$x .= 'Locator = '.$ColOn.htmlentities(substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1)).$ColOff.$Nl;
			$x .= $Type.$Nl;
			$x .= 'Name = '.$ColOn.htmlentities($Name).$ColOff.', subname = '.$ColOn.htmlentities(($Loc->SubName===false)? '(none)':$Loc->SubName).$ColOff.$Nl;
			$x .= 'Begin = '.$ColOn.$Loc->PosBeg.$ColOff.', end = '.$ColOn.$Loc->PosEnd.$ColOff.$Nl;
			foreach ($Loc->PrmLst as $key=>$val) {
				if ($val===true) $val = '(true)';
				if ($val===false) $val = '(false)';
				$x .= 'Parameters['.$ColOn.htmlentities($key).$ColOff.'] = '.$ColOn.htmlentities($val).$ColOff.$Nl;
			}
			$x .= $Break;
		}
		$Msg = $Msg.'Locators '.$ColOn.htmlentities($NameLst).$ColOff.': found = '.$ColOn.$Nbr.$ColOff.$Nl.$Break.$x;
	}
	$Header = 'DEBUG LOCATOR: Search for = '.$ColOn.htmlentities($NameLst).$ColOff.', total found = '.$ColOn.$NbrTot.$ColOff.', Template size = '.$ColOn.strlen($Txt).$ColOff.'.'.$Nl;
	$Msg = $Header.$Break.$Msg;

	$this->_DebugTxt .= $Msg;
}

// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-

function meth_Locator_FindTbs(&$Txt,$Name,$Pos,$ChrSub) {
// Find a TBS Locator

	$PosEnd = false;
	$PosMax = strlen($Txt) -1;
	$Start = $this->ChrOpen.$Name;

	do {
		// Search for the opening char
		if ($Pos>$PosMax) return false;
		$Pos = strpos($Txt,$Start,$Pos);

		// If found => next chars are analyzed
		if ($Pos===false) {
			return false;
		} else {
			$Loc = &new clsTbsLocator;
			$ReadPrm = false;
			$PosX = $Pos + strlen($Start);
			$x = $Txt[$PosX];

			if ($x===$this->ChrClose) {
				$PosEnd = $PosX;
			} elseif ($x===$ChrSub) {
				$Loc->SubOk = true; // it is no longer the false value
				$ReadPrm = true;
				$PosX++;
			} elseif (strpos(';',$x)!==false) {
				$ReadPrm = true;
				$PosX++;
			} else {
				$Pos++;
			}

			if ($ReadPrm) tbs_Locator_ReadPrm($Txt,$PosX,';','= ','\'','([{',')]}',$this->ChrClose,0,$Loc,$PosEnd);

		}

	} while ($PosEnd===false);

	$Loc->PosBeg = $Pos;
	$Loc->PosEnd = $PosEnd;
	if ($Loc->SubOk) {
		$Loc->FullName = $Name.'.'.$Loc->SubName;
		$Loc->SubLst = explode('.',$Loc->SubName);
		$Loc->SubNbr = count($Loc->SubLst);
	} else {
		$Loc->FullName = $Name;
	}
	if ($ReadPrm and isset($Loc->PrmLst['comm'])) {
		$Loc->PosBeg0 = $Loc->PosBeg;
		$Loc->PosEnd0 = $Loc->PosEnd;
		$Loc->Enlarged = tbs_Locator_EnlargeToStr($Txt,$Loc,'<!--' ,'-->');
	}

	return $Loc;

}

// Search and cache TBS locators founded in $Txt.
function meth_Locator_SectionCache(&$Txt,&$BlockName,&$LocR) {

	$LocR->BlockNbr++;
	$LocR->BlockName[$LocR->BlockNbr] = $BlockName;
	$LocR->BlockSrc[$LocR->BlockNbr] = $Txt;
	$LocR->BlockLoc[$LocR->BlockNbr] = array();
	$LocR->BlockChk[$LocR->BlockNbr] = false;

	if (!$this->TurboBlock) {
		$LocR->BlockLoc[$LocR->BlockNbr][0] = 0;
		$LocR->BlockChk[$LocR->BlockNbr] = true;
		return;
	}

	$LocLst = &$LocR->BlockLoc[$LocR->BlockNbr];

	$Pos = 0;
	$PrevEnd = -1;
	$Nbr = 0;
	while ($Loc = $this->meth_Locator_FindTbs($Txt,$BlockName,$Pos,'.')) {
		if (($Loc->SubName==='#') or ($Loc->SubName==='$')) {
			$Loc->IsRecInfo = true;
			$Loc->RecInfo = $Loc->SubName;
			$Loc->SubName = '';
		} else {
			$Loc->IsRecInfo = false;
		}
		if ($Loc->PosBeg>$PrevEnd) {
			// The previous tag is not embeding => increment
			$Nbr++;
		} else {
			// The previous tag is embeding => no increment, then previous is over writed
			$LocR->BlockChk[$LocR->BlockNbr] = true;
		}
		$PrevEnd = $Loc->PosEnd;
		if ($Loc->Enlarged) { // Parameter 'comm'
			$Pos = $Loc->PosBeg0+1;
			$Loc->Enlarged = false;
		} else {
			$Pos = $Loc->PosBeg+1;
		}
		$LocLst[$Nbr] = $Loc;
	}

	$LocLst[0] = $Nbr;

}

function meth_Locator_Replace(&$Txt,&$Loc,&$Value,$CheckSub) {
// This function enables to merge a locator with a text and returns the position just after the replaced block
// This position can be useful because we don't know in advance how $Value will be replaced.

	// Found the value if there is a subname
	if ($CheckSub and $Loc->SubOk) {
		$SubId = 0;
		while ($SubId<$Loc->SubNbr) {
			$x = $Loc->SubLst[$SubId]; // &$Loc... brings an error with Event Example, I don't know why.
			if (is_array($Value)) {
				if (isset($Value[$x])) {
					$Value = &$Value[$x];
				} elseif (array_key_exists($x,$Value)) {// can happens when value is NULL
					$Value = &$Value[$x];
				} else {
					$Value = &tbs_Misc_UnlinkVar('');
					if (!isset($Loc->PrmLst['noerr'])) $this->meth_Misc_Alert('Array value','Can\'t merge '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' because there is no key named \''.$x.'\'.',true);
				}
				$SubId++;
			} elseif (is_object($Value)) {
				if (method_exists($Value,$x)) {
					$x = call_user_func(array(&$Value,$x));
				} else {
					$x = $Value->$x;
				}
				$Value = &$x;
				$x = &tbs_Misc_UnlinkVar('');
				$SubId++;
			} else {
				if (isset($Loc->PrmLst['selected'])) {
					$SelArray = &$Value;
				} else {
					if (!isset($Loc->PrmLst['noerr'])) $this->meth_Misc_Alert('Object or Array value expected','Can\'t merge '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' because the value before the key \''.$x.'\' (type: '.gettype($Value).') is not an object or an array.',true);
				}
				$Value = &tbs_Misc_UnlinkVar('');
				$SubId = $Loc->SubNbr;
			}
		}
	}

	$CurrVal = $Value;

	$Select = false;	
	$ExternalVal = false;// Value to embed in the current val
	$Script = true;   // False to ignore script execution

	// File inclusion
	if (isset($Loc->PrmLst['file'])) {
		$File = str_replace($this->ChrVal,$CurrVal,$Loc->PrmLst['file']);
		$this->meth_Merge_PhpVar($File,false);
		$OnlyBody = true;
		if (isset($Loc->PrmLst['htmlconv'])) {
			if (strtolower($Loc->PrmLst['htmlconv'])==='no') {
				$OnlyBody = false; // It's a text file, we don't get the BODY part
			}
		}
		if (tbs_Misc_GetFile($CurrVal,$File)) {
			if ($OnlyBody) $CurrVal = tbs_Html_GetPart($CurrVal,'BODY',false,true);
		} else {
			$CurrVal = '';
			if (!isset($Loc->PrmLst['noerr'])) $this->meth_Misc_Alert('Parameter \'file\'','Field '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' : unable to read the file \''.$File.'\'.',true);
		}
		$Loc->ConvHtml = false;
		$Loc->ConvProtec = false; // Default value for file inclusion
	}

	// OnFormat event
	if (isset($Loc->PrmLst['onformat'])) {
		$OnFormat = $Loc->PrmLst['onformat'];
		if (function_exists($OnFormat)) {
			$OnFormat($Loc->FullName,$CurrVal);
		} else {
			if (!isset($Loc->PrmLst['noerr'])) $this->meth_Misc_Alert('Parameter \'onformat\'','Field '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' : the function \''.$OnFormat.'\' doesn\'t exist.',true);
		}
	}

	// Select a value in a HTML option list
	if (isset($Loc->PrmLst['selected'])) {
		$Select = true;
		if (is_array($CurrVal)) {
			$SelArray = &$CurrVal;
			$CurrVal = &tbs_Misc_UnlinkVar(' ');
		} else {
			$SelArray = false;
		}
	}

	// Convert the value to a string, use format if specified
	if (isset($Loc->PrmLst['frm'])) {
		$CurrVal = tbs_Misc_Format($Loc,$CurrVal);
		$Loc->ConvHtml = false;
	} else {
		if (!is_string($CurrVal)) $CurrVal = @strval($CurrVal);
	}

	// case of an 'if' 'then' 'else' options
	if (isset($Loc->PrmLst['if'])) {
		if ($Loc->FirstMerge) $this->meth_Merge_PhpVar($Loc->PrmLst['if'],false);
		$x = str_replace($this->ChrVal,$CurrVal,$Loc->PrmLst['if']);
		if (tbs_Misc_CheckCondition($x)) {
			if (isset($Loc->PrmLst['then'])) {
				$ExternalVal = $CurrVal;
				$CurrVal = $Loc->PrmLst['then'];
			} // else -> it's the given value
		} else {
			$Script = false;
			if (isset($Loc->PrmLst['else'])) {
				$ExternalVal = $CurrVal;
				$CurrVal = $Loc->PrmLst['else'];
			} else {
				$CurrVal = '';
				$Loc->ConvProtec = false; // Only because it is empty
			}
		}
	}

	if ($Script) {// Include external PHP script
		if (isset($Loc->PrmLst['script'])) {
			$File = str_replace($this->ChrVal,$CurrVal,$Loc->PrmLst['script']);
			$this->meth_Merge_PhpVar($File,false);
			if (isset($Loc->PrmLst['getob'])) ob_start();
			if (isset($Loc->PrmLst['once'])) {
				include_once($File);
			} else {
				include($File);
			}
			if (isset($Loc->PrmLst['getob'])) {
				$CurrVal = ob_get_contents();
				ob_end_clean();
			}
			$Loc->ConvHtml = false;
		}
	}

	if ($Loc->FirstMerge) {
		$Loc->FirstMerge = false;
		// Check HtmlConv parameter
		if (isset($Loc->PrmLst['htmlconv'])) {
			$x = strtolower($Loc->PrmLst['htmlconv']);
			$x = '+'.str_replace(' ','',$x).'+';
			if (strpos($x,'+no+')!==false) $Loc->ConvHtml = false;
			if (strpos($x,'+yes+')!==false) $Loc->ConvHtml = true;
			if (strpos($x,'+nobr+')!==false) { $Loc->ConvHtml = true; $Loc->ConvBr = false; }
			if (strpos($x,'+esc+')!==false) { $Loc->ConvHtml = false; $Loc->ConvEsc = true; }
			if (strpos($x,'+wsp+')!==false) $Loc->ConvWS = true;
			if (strpos($x,'+look+')!==false) $Loc->ConvLook = true;
		} else {
			if ($this->HtmlCharSet===false) $Loc->ConvHtml = false; // No HTML
		}
		// We protect the data that does not come from the source of the template
		if (isset($Loc->PrmLst['protect'])) {
			$x = strtolower($Loc->PrmLst['protect']);
			switch ($x) {
			case 'no' : $Loc->ConvProtec = false; break;
			case 'yes': $Loc->ConvProtec = true; break;
			}
		}
	}
	if ($Loc->ConvEsc) $CurrVal = str_replace('\'','\'\'',$CurrVal);
	if ($Loc->ConvLook) {
		if (tbs_Html_IsHtml($CurrVal)) {
			$Loc->ConvHtml = false;
			$CurrVal = tbs_Html_GetPart($CurrVal,'BODY',false,true);
		} else {
			$Loc->ConvHtml = true;
		}
	}

	// MaxLength
	if (isset($Loc->PrmLst['max'])) {
		$x = intval($Loc->PrmLst['max']);
		if (strlen($CurrVal)>$x) {
			if ($Loc->ConvHtml or ($this->HtmlCharSet===false)) {
				$CurrVal = substr($CurrVal,0,$x-1).'...';
			} else {
				tbs_Html_Max($CurrVal,$x);
			}
		}
	}

	// HTML conversion
	if ($Loc->ConvHtml) {
		if ($ExternalVal===false) {
			$this->meth_Html_Conv($CurrVal,$Loc->ConvBr,$Loc->ConvWS);
		} else {
			$this->meth_Html_Conv($ExternalVal,$Loc->ConvBr,$Loc->ConvWS);
		}
	}

	//TBS protection
	if ($Loc->ConvProtec) {
		if ($ExternalVal===false) {
			$CurrVal = str_replace($this->ChrOpen,$this->ChrProtect,$CurrVal);
		} else {
			$ExternalVal = str_replace($this->ChrOpen,$this->ChrProtect,$ExternalVal);
			$CurrVal = str_replace($this->ChrVal,$ExternalVal,$CurrVal);
		}
	}

	// Case when it is an empty string
	if ($CurrVal==='') {

		if ($Loc->MagnetId===false) {
			if (isset($Loc->PrmLst['.'])) {
				$Loc->MagnetId = -1;
			} elseif (isset($Loc->PrmLst['ifempty'])) {
				$Loc->MagnetId = -2;
			} elseif (isset($Loc->PrmLst['magnet']))  {
				$Loc->MagnetId = 1;
				$Loc->PosBeg0 = $Loc->PosBeg;
				$Loc->PosEnd0 = $Loc->PosEnd;
				if (isset($Loc->PrmLst['mtype'])) {
					switch ($Loc->PrmLst['mtype']) {
					case 'm+m': $Loc->MagnetId = 2; break;
					case 'm*': $Loc->MagnetId = 3; break;
					case '*m': $Loc->MagnetId = 4; break;
					}
				}
			} else {
				$Loc->MagnetId = 0;
			}
		}

		switch ($Loc->MagnetId) {
		case 0: break;
		case -1: $CurrVal = '&nbsp;'; break; // Enables to avoid blanks in HTML tables
		case -2: $CurrVal = $Loc->PrmLst['ifempty']; break;
		case 1:
			$Loc->Enlarged = true;
			tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['magnet'],false,false);
			break;
		case 2:
			$Loc->Enlarged = true;
			$CurrVal = tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['magnet'],false,true);
			break;
		case 3:
			$Loc->Enlarged = true;
			$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['magnet'],true,$Loc->PosBeg,false,1,false);
			if ($Loc2!==false) {
				$Loc->PosBeg = $Loc2->PosBeg;
				if ($Loc->PosEnd<$Loc2->PosEnd) $Loc->PosEnd = $Loc2->PosEnd;
			}
			break;
		case 4:
			$Loc->Enlarged = true;
			$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['magnet'],true,$Loc->PosBeg,true,1,false);
			if ($Loc2!==false) $Loc->PosEnd = $Loc2->PosEnd;
			break;
		}

	}

	$Txt = substr_replace($Txt,$CurrVal,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
	$NewEnd = $Loc->PosBeg + strlen($CurrVal);

	if ($Select) tbs_Html_MergeItems($Txt,$Loc,$CurrVal,$SelArray,$NewEnd);

	return $NewEnd; // Returns the new end position of the field

}

function &meth_Locator_FindBlockNext(&$Txt,$BlockName,$PosBeg,$ChrSub,$Special,&$P1) {
// Return the first block locator object just after the PosBeg position

	$Invalide = true;

	// Search for the first tag with parameter "block"
	while ($Invalide and ($Loc = $this->meth_Locator_FindTbs($Txt,$BlockName,$PosBeg,$ChrSub))) {
		if (isset($Loc->PrmLst['block'])) {
			$Block = $Loc->PrmLst['block'];
			if ($P1) {
				if (isset($Loc->PrmLst['p1'])) return false;
			} else {
				if (isset($Loc->PrmLst['p1'])) $P1 = true;
			}
			$Invalide = false;
		} elseif ($Special) {
			return $Loc;
		}
		$PosBeg = $Loc->PosEnd;
	}

	if ($Invalide) return false;

	if ($Block==='begin') { // Block definied using begin/end

		while ($Loc2 = $this->meth_Locator_FindTbs($Txt,$BlockName,$PosBeg,$ChrSub)) {
			if (isset($Loc2->PrmLst['block']) and ($Loc2->PrmLst['block']==='end')) {
				if ($Special) {
					$Loc->PosBeg2 = $Loc2->PosBeg;
					$Loc->PosEnd2 = $Loc2->PosEnd;
				} else {
					$Loc->BlockSrc = substr($Txt,$Loc->PosEnd+1,$Loc2->PosBeg-$Loc->PosEnd-1);
					$Loc->PosEnd = $Loc2->PosEnd;
				}
				$Loc->BlockFound = true;
				return $Loc;
			} else {
				$PosBeg = $Loc2->PosEnd;
			}
		}

		return $this->meth_Misc_Alert('Block definition',$this->ChrOpen.$Loc->FullName.$this->ChrClose.'] has a \'begin\' tag, but no \'end\' tag found.');

	}

	if ($Special) {
		$Loc->PosBeg2 = false;
	} else {

		if (!$Loc->SubOk) {
			$PosBeg1 = $Loc->PosBeg;
			$PosEnd1 = $Loc->PosEnd;
		}
		if (tbs_Locator_EnlargeToTag($Txt,$Loc,$Block,true,false)===false) return $this->meth_Misc_Alert('Block definition',$this->ChrOpen.$Loc->FullName.$this->ChrClose.' can not be defined because tag <'.$Loc->PrmLst['block'].'> or </'.$Loc->PrmLst['block'].'> is not found.');
		if ($Loc->SubOk) {
			$Loc->BlockSrc = substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
		} else {
			$Loc->BlockSrc = substr($Txt,$Loc->PosBeg,$PosBeg1-$Loc->PosBeg).substr($Txt,$PosEnd1+1,$Loc->PosEnd-$PosEnd1);		
		}
	}

	$Loc->BlockFound = true;
	return $Loc;

}

function &meth_Locator_FindBlockLst(&$Txt,$BlockName,$Pos) {
// Return a locator object covering all block definitions, even if there is no block definition found.

	$LocR = &new clsTbsLocator;
	$LocR->P1 = false;
	$LocR->OnSection = false;
	$LocR->BlockNbr = 0;
	$LocR->BlockSrc = array(); // 1 to BlockNbr
	$LocR->BlockLoc = array(); // 1 to BlockNbr
	$LocR->BlockChk = array(); // 1 to BlockNbr
	$LocR->BlockName = array(); // 1 to BlockNbr
	$LocR->NoDataSrc = '';
	$LocR->SpecialBid = false;
	$LocR->HeaderFound = false;
	$LocR->FooterFound = false;
	$LocR->SectionNbr = 0;
	$LocR->SectionBid = array();       // 1 to SectionNbr
	$LocR->SectionIsSerial = array();  // 1 to SectionNbr
	$LocR->SectionSerialBid = array(); // 1 to SectionNbr
	$LocR->SectionSerialOrd = array(); // 1 to SectionNbr
	$LocR->SerialEmpty = false;

	$Bid = &$LocR->BlockNbr;
	$Sid = &$LocR->SectionNbr;

	while ($Loc = $this->meth_Locator_FindBlockNext($Txt,$BlockName,$Pos,'.',false,$LocR->P1)) {

		$Pos = $Loc->PosEnd;

		// Define the block limits
		if ($LocR->BlockFound) {
			if ( $LocR->PosBeg > $Loc->PosBeg ) $LocR->PosBeg = $Loc->PosBeg;
			if ( $LocR->PosEnd < $Loc->PosEnd ) $LocR->PosEnd = $Loc->PosEnd;
		} else {
			$LocR->BlockFound = true;
			$LocR->PosBeg = $Loc->PosBeg;
			$LocR->PosEnd = $Loc->PosEnd;
		}

		// Merge block parameters
		if (count($Loc->PrmLst)>0) $LocR->PrmLst = array_merge($LocR->PrmLst,$Loc->PrmLst);

		// Save the block and cache its tags (incrments $LocR->BlockNbr)
		$this->meth_Locator_SectionCache($Loc->BlockSrc,$BlockName,$LocR);

		// Add the text int the list of blocks
		if (isset($Loc->PrmLst['nodata'])) {
			// Nodata section
			$LocR->NoDataSrc = &$LocR->BlockSrc[$Bid];
		} elseif (isset($Loc->PrmLst['currpage'])) {
			// Special section (used for navigation bar)
			$LocR->SpecialBid = $Bid;
		} elseif (isset($Loc->PrmLst['headergrp'])) {
			// Header
			if ($LocR->HeaderFound===false) {
				$LocR->HeaderFound = true;
				$LocR->HeaderNbr = 0;
				$LocR->HeaderBid = array();       // 1 to HeaderNbr
				$LocR->HeaderPrevValue = array(); // 1 to HeaderNbr
				$LocR->HeaderField = array();     // 1 to HeaderNbr
			}
			$LocR->HeaderNbr++;
			$LocR->HeaderBid[$LocR->HeaderNbr] = $Bid;
			$LocR->HeaderPrevValue[$LocR->HeaderNbr] = false;
			$LocR->HeaderField[$LocR->HeaderNbr] = $Loc->PrmLst['headergrp'];
		} elseif (isset($Loc->PrmLst['footergrp']) or isset($Loc->PrmLst['splittergrp'])) {
			// Footer and Splitter
			if ($LocR->FooterFound===false) {
				$LocR->FooterFound = true;
				$LocR->FooterNbr = 0;
				$LocR->FooterBid = array();       // 1 to FooterNbr
				$LocR->FooterPrevValue = array(); // 1 to FooterNbr
				$LocR->FooterField = array();     // 1 to FooterNbr
				$LocR->FooterIsFooter = array();  // 1 to FooterNbr
			}
			$LocR->FooterNbr++;
			$LocR->FooterBid[$LocR->FooterNbr] = $Bid;
			$LocR->FooterPrevValue[$LocR->FooterNbr] = false;
			if (isset($Loc->PrmLst['footergrp'])) {
				$LocR->FooterField[$LocR->FooterNbr] = $Loc->PrmLst['footergrp'];
				$LocR->FooterIsFooter[$LocR->FooterNbr] = true;
			} else {
				$LocR->FooterField[$LocR->FooterNbr] = $Loc->PrmLst['splittergrp'];
				$LocR->FooterIsFooter[$LocR->FooterNbr] = false;
			}
		} elseif (isset($Loc->PrmLst['serial'])) {
			// Section	with Serial Sub-Sections
			$Src = &$LocR->BlockSrc[$Bid];
			$Loc0 = false;
			if ($LocR->SerialEmpty===false) {
				$NameSr = $BlockName.'_0';
				$x = false;
				$LocSr = $this->meth_Locator_FindBlockNext($Src,$NameSr,0,'.',false,$x);
				if ($LocSr!==false) {
					$LocR->SerialEmpty = $LocSr->BlockSrc;
					$Src = substr_replace($Src,'',$LocSr->PosBeg,$LocSr->PosEnd-$LocSr->PosBeg+1);
				}
			}
			$NameSr = $BlockName.'_1';
			$x = false;
			$LocSr = $this->meth_Locator_FindBlockNext($Src,$NameSr,0,'.',false,$x);
			if ($LocSr!==false) {
				$Sid++;
				$LocR->SectionBid[$Sid] = $Bid;
				$LocR->SectionIsSerial[$Sid] = true;
				$LocR->SectionSerialBid[$Sid] = array();
				$LocR->SectionSerialOrd[$Sid] = array();
				$SrBid = &$LocR->SectionSerialBid[$Sid];
				$SrOrd = &$LocR->SectionSerialOrd[$Sid];
				$BidParent = $Bid;
				$SrNum = 1;
				do {
					// Save previous sub-section
					$LocR->BlockLoc[$BidParent][$SrNum] = $LocSr;
					$this->meth_Locator_SectionCache($LocSr->BlockSrc,$NameSr,$LocR);
					$SrBid[$SrNum] = $Bid;
					$SrOrd[$SrNum] = $SrNum;
					$i = $SrNum;
					while (($i>1) and ($LocSr->PosBeg<$LocR->BlockLoc[$BidParent][$i-1]->PosBeg)) {
						$SrOrd[$i] = $SrOrd[$i-1];
						$SrOrd[$i-1] = $SrNum;
						$i--;
					}
					// Search next section
					$SrNum++;
					$NameSr = $BlockName.'_'.$SrNum;
					$x = false;
					$LocSr = $this->meth_Locator_FindBlockNext($Src,$NameSr,0,'.',false,$x);
				} while ($LocSr!==false);
				$SrBid[0] = $SrNum-1;
			}
		} else {
			// Normal section
			$Sid++;
			$LocR->SectionBid[$Sid] = $Bid;
			$LocR->SectionIsSerial[$Sid] = false;
		}

	}

	if ($LocR->BlockFound===false) {
		$LocR->PosBeg = 0;
		$LocR->PosEnd = strlen($Txt) - 1;
		$this->meth_Locator_SectionCache($Txt,$BlockName,$LocR);
		$LocR->SectionNbr = 1;
		$LocR->SectionBid[1] = $Bid;
		$LocR->SectionIsSerial[1] = false;
		$LocR->NoDataSrc = &$LocR->BlockSrc[$Bid];
	}

	return $LocR;

}

function meth_Merge_Field(&$Txt,$Name,&$Value,$ChrSub,$CheckSub,$RecNum) {
// Merge all the occurences of a field-locator in the text string

	$PosBeg = 0;

	if ($RecNum===false) {
		while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$PosBeg,$ChrSub)) {
			$PosBeg = $this->meth_Locator_Replace($Txt,$Loc,$Value,$CheckSub);
		}
	} else {
		while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$PosBeg,$ChrSub)) {
			if ($Loc->SubName==='#') {
				$PosBeg = $this->meth_Locator_Replace($Txt,$Loc,$RecNum,false);
			} else {
				$PosBeg = $this->meth_Locator_Replace($Txt,$Loc,$Value,$CheckSub);
			}
		}
	}

}

function meth_Merge_Block(&$Txt,&$BlockName,&$SrcId,&$Query,$PageSize,$PageNum,$RecKnown) {

	// Get source type and info
	$Src = &new clsTbsDataSource;
	$Src->BlockName = $BlockName;
	if (!$Src->DataPrepare($SrcId,$this)) return 0;

	$BlockId = 0;
	$BlockLst = explode(',',$BlockName);
	$BlockNbr = count($BlockLst);
	$WasP1 = false;
	$Pos = 0;
	$NbrRecTot = 0;
	$QueryZ = &$Query;

	while ($BlockId<$BlockNbr) {

		$RecStop = 0; // Stop the merge after this row
		$RecSpe = 0;  // Row with a special block's definition (used for the navigation bar)
		$QueryOk = true;
		$Src->BlockName = $BlockLst[$BlockId];

		// Search the block
		$LocR = $this->meth_Locator_FindBlockLst($Txt,$Src->BlockName,$Pos);

		if ($LocR->BlockFound) {
			if ($LocR->SpecialBid!==false) $RecSpe = $RecKnown;
			// OnSection
			if (isset($LocR->PrmLst['onsection'])) {
				$LocR->OnSectionFct = $LocR->PrmLst['onsection'];
				if (function_exists($LocR->OnSectionFct)) {
					$LocR->OnSection = true;
				} else {
					$this->meth_Misc_Alert('Block definition \''.$Src->BlockName.'\'','Unvalide value for the \'onsection\' parameter of the block The block '.$this->ChrOpen.$BlockName.$this->ChrClose.'. The function \''.$LocR->OnSectionFct.'\' is not found.');
				}
			}
			//Dynamic query
			if ($LocR->P1) {
				if (is_string($Query)) {
					$Src->RecSaved = false;
					$QueryZ = &tbs_Misc_UnlinkVar($Query);
					$i = 1;
					do {
						$x = 'p'.$i;
						if (isset($LocR->PrmLst[$x])) {
							$QueryZ = str_replace('%p'.$i.'%',$LocR->PrmLst[$x],$QueryZ);
							$i++;
						} else {
							$i = false;
						}
					} while ($i!==false);
				}
				$WasP1 = true;
			} elseif (($Src->RecSaved===false) and ($BlockNbr-$BlockId>1)) {
				$Src->RecSaving = true;
			}
		} else {
			if ($WasP1) {
				$QueryOk = false;
				$WasP1 = false;
			} else {
				$RecStop = 1;
			}
		}

		// Open the recordset
		if ($QueryOk) $QueryOk = $Src->DataOpen($QueryZ,$PageSize,$PageNum,$RecStop);

		// Merge sections
		if ($QueryOk) {
			if ($Src->Type===4) { // Special for Text merge
				if ($LocR->BlockFound) {
					$Src->RecNum = 1;
					$Src->CurrRec = false;
					if ($LocR->OnSection) {
						$Fct = $LocR->OnSectionFct;
						$Fct($LocRs,$Src->CurrRec,$Src->RecSet,$Src->RecNum);
					}
					$Txt = substr_replace($Txt,$Src->RecSet,$LocR->PosBeg,$LocR->PosEnd-$LocR->PosBeg+1);
				} else {
					$Src->DataAlert('Can\'t merge the block with a text value because the block definition is not found.');
				}
			} else { // Other data source type

				// Initialise
				$SecId = 0;
				$SecOk = ($LocR->SectionNbr>0);
				$SecIncr = true;
				$BlockRes = ''; // The result of the chained merged blocks
				$SerialMode = false;
				$SerialNum = 0;
				$SerialMax = 0;
				$SerialTxt = array();
				$GrpFound = ($LocR->HeaderFound or $LocR->FooterFound);

				// Main loop
				$Src->DataFetch();
				while($Src->CurrRec!==false) {

					// Headers and Footers
					if ($GrpFound) {
						$grp_change = false;
						$grp_src = '';
						if ($LocR->FooterFound) {
							$change = false;
							for ($i=$LocR->FooterNbr;$i>=1;$i--) {
								$x = $Src->CurrRec[$LocR->FooterField[$i]];
								if ($Src->RecNum===1) {
									$LocR->FooterPrevValue[$i] = $x;
								} else {
									if ($LocR->FooterIsFooter[$i]) {
										$change_i = &$change;
									} else {
										$change_i = &tbs_Misc_UnlinkVar(false);
									}
									if (!$change_i) $change_i = !($LocR->FooterPrevValue[$i]===$x);
									if ($change_i) {
										$grp_src = $this->meth_Merge_SectionCached($LocR,$LocR->FooterBid[$i],$PrevRec,$PrevNum,$PrevKey).$grp_src;
										$LocR->FooterPrevValue[$i] = $x;
									}
								}
							}
							$grp_change = ($grp_change or $change);
							$PrevRec = $Src->CurrRec;
							$PrevNum = $Src->RecNum;
							$PrevKey = $Src->RecKey;
						}
						if ($LocR->HeaderFound) {
							$change = ($Src->RecNum===1);
							for ($i=1;$i<=$LocR->HeaderNbr;$i++) {
								$x = $Src->CurrRec[$LocR->HeaderField[$i]];
								if (!$change) $change = !($LocR->HeaderPrevValue[$i]===$x);
								if ($change) {
									$grp_src .= $this->meth_Merge_SectionCached($LocR,$LocR->HeaderBid[$i],$Src->CurrRec,$Src->RecNum,$Src->RecKey);
									$LocR->HeaderPrevValue[$i] = $x;
								}
							}
							$grp_change = ($grp_change or $change);
						}
						if ($grp_change) {
							if ($SerialMode) {
								$BlockRes .= $this->meth_Merge_SectionSerial($LocR,$SecId,$SerialNum,$SerialMax,$SerialTxt);
								$SecIncr = true;
							}
							$BlockRes .= $grp_src;
						}
					}

					// Increment Section
					if ($SecIncr and $SecOk) {
						$SecId++;
						if ($SecId>$LocR->SectionNbr) $SecId = 1;
						$SerialMode = $LocR->SectionIsSerial[$SecId];
						if ($SerialMode) {
							$SerialNum = 0;
							$SerialMax = $LocR->SectionSerialBid[$SecId][0];
							$SecIncr = false;
						}
					}

					// Serial Mode Activation
					if ($SerialMode) { // Serial Merge
						$SerialNum++;
						$Bid = $LocR->SectionSerialBid[$SecId][$SerialNum];
						$SerialTxt[$SerialNum] = $this->meth_Merge_SectionCached($LocR,$Bid,$Src->CurrRec,$Src->RecNum,$Src->RecKey);
						if ($SerialNum>=$SerialMax) {
							$BlockRes .= $this->meth_Merge_SectionSerial($LocR,$SecId,$SerialNum,$SerialMax,$SerialTxt);
							$SecIncr = true;
						}
					} else { // Classic merge
						if ($Src->RecNum===$RecSpe) {
							$Bid = $LocR->SpecialBid;
						} else {
							$Bid = $LocR->SectionBid[$SecId];
						}
						$BlockRes .= $this->meth_Merge_SectionCached($LocR,$Bid,$Src->CurrRec,$Src->RecNum,$Src->RecKey);
					}

					// Next row
					if ($Src->RecNum===$RecStop) {
						$Src->CurrRec = false;
					} else {
						// $CurrRec can be set to False by the OnSection event function.
						if ($Src->CurrRec!==false) $Src->DataFetch();
					}

				} //--> while($CurrRec!==false) {

				// Serial: merge the extra the sub-blocks
				if ($SerialMode and !$SecIncr) {
					$BlockRes .= $this->meth_Merge_SectionSerial($LocR,$SecId,$SerialNum,$SerialMax,$SerialTxt);
				}

				// Footer
				if ($LocR->FooterFound) {
					if ($Src->RecNum>0) {
						for ($i=1;$i<=$LocR->FooterNbr;$i++) {
							if ($LocR->FooterIsFooter[$i]) $BlockRes .= $this->meth_Merge_SectionCached($LocR,$LocR->FooterBid[$i],$PrevRec,$PrevNum,$PrevKey);
						}
					}
				}

				// Mode Page: Calculate the value to return
				if (($PageSize>0) and ($Src->RecNum>=$RecStop)) {
					if ($RecKnown<0) { // Pass pages in order to count all records
						do {
							$Src->DataFetch();
						} while ($Src->CurrRec!==false);
					} else { // We know that there is more records
						if ($RecKnown>$Src->RecNum) $Src->RecNum = $RecKnown;
					}
				}

				// NoData
				if ($Src->RecNum===0) {
					$BlockRes = $LocR->NoDataSrc;
					if ($LocR->OnSection) {
						$Src->CurrRec = false;
						$Fct = $LocR->OnSectionFct;
						$Fct($Src->BlockName,$Src->CurrRec,$BlockRes,$Src->RecNum);
					}
				}

				// Merge the result
				$Txt = substr_replace($Txt,$BlockRes,$LocR->PosBeg,$LocR->PosEnd-$LocR->PosBeg+1);
				$Pos = $LocR->PosBeg;

			} //-> if ($SrcType===4) {...} else {...

			// Close the resource
			$Src->DataClose();

		} //-> if ($QueryOk) {..

		if (!$WasP1) {
			// Merge last record on the entire template
			$NbrRecTot += $Src->RecNum;
			if ($Src->CurrRec===false) $Src->CurrRec = array(); // For conveniant error message when a column is missing
			if ($QueryOk) $this->meth_Merge_Field($Txt,$Src->BlockName,$Src->CurrRec,'.',true,$Src->RecNum);
			$Pos = 0;
			$BlockId++;
		}

	} // -> while ($BlockId<$BlockNbr) {...

	// End of the merge
	return $NbrRecTot;

}

// Look for each 'tbs_check' block and merge them.
function meth_Merge_Check(&$Txt,$BlockName) {

	$GrpDisplay = array();
	$GrpElse = array();
	$ElseCnt = 0;

	$ElseTurn = false;
	$Continue = true;
	$P1 = false;

	while ($Continue) {

		$Pos = 0;
		while ($Loc=$this->meth_Locator_FindBlockNext($Txt,$BlockName,$Pos,'.',true,$P1)) {
			if ($Loc->BlockFound) {

				if ($ElseTurn) {
					$DelBlock = $GrpDisplay[$Loc->SubName];
					$DelField = !$DelBlock;
				} else {
					if (!isset($GrpDisplay[$Loc->SubName])) $GrpDisplay[$Loc->SubName] = false;
					if (isset($Loc->PrmLst['if'])) {
						if (tbs_Misc_CheckCondition($Loc->PrmLst['if'])) {
							$DelBlock = false;
							$GrpDisplay[$Loc->SubName] = true;
						} else {
							$DelBlock = true;
						}
						$DelField = !$DelBlock;
					} elseif(isset($Loc->PrmLst['else'])) {
						if ($GrpDisplay[$Loc->SubName]) {
							$DelBlock = true;
						} else {
							$DelBlock = false;
							$DelField = false;
							$ElseCnt++;
						}
					} elseif ($Loc->PrmLst['block']==='end') {
						$DelBlock = false;
						$DelField = false;
					} else {
						$DelBlock = false;
						$DelField = true;
					}
				}
								
				// Del parts
				if ($DelBlock) {
					if ($Loc->PosBeg2===false) {
						tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['block'],true,false);
					} else {
						$Loc->PosEnd = $Loc->PosEnd2;
					}
					$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
					$Pos = $Loc->PosBeg;
				} elseif ($DelField) {
					if ($Loc->PosBeg2!==false) $Txt = substr_replace($Txt,'',$Loc->PosBeg2,$Loc->PosEnd2-$Loc->PosBeg2+1);
					$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
					$Pos = $Loc->PosBeg;
				} else {
					$Pos = $Loc->PosEnd;
				}

			} else {
				$x = '';
				$Pos = $this->meth_Locator_Replace($Txt,$Loc,$x,false);
			}
		}

		if ($ElseTurn) {
			$Continue = false;
		} else {
			$ElseTurn = true;
			$Continue = ($ElseCnt>0);
		}

	}

}

function meth_Merge_PhpVar(&$Txt,$HtmlConv) {
// Merge the PHP global variables of the main script.

	$Pref = &$this->VarPrefix;
	$PrefL = strlen($Pref);
	$PrefOk = ($PrefL>0);

	if ($HtmlConv===false) {
		$HtmlCharSet = $this->HtmlCharSet;
		$this->HtmlCharSet = false;
	}

	// Then we scann all fields in the model
	$x = '';
	$Pos = 0;
	while ($Loc = $this->meth_Locator_FindTbs($Txt,'var',$Pos,'.')) {
		if ($Loc->SubNbr>0) {
			if ($Loc->SubLst[0]==='') {
				$Pos = $this->meth_Merge_System($Loc);
			} elseif ($PrefOk and (substr($Loc->SubLst[0],0,$PrefL)!==$Pref)) {
				if (isset($Loc->PrmLst['noerr'])) {
					$Pos = $this->meth_Locator_Replace($Txt,$Loc,$x,false);
				} else {
					$this->meth_Misc_Alert('Merge PHP global variables','Can\'t merge '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' because allowed prefix is set to \''.$Pref.'\'.',true);
					$Pos = $Loc->PosEnd + 1;
				}
			} elseif (isset($GLOBALS[$Loc->SubLst[0]])) {
				$Pos = $this->meth_Locator_Replace($Txt,$Loc,$GLOBALS,true);
			} else {
				if (isset($Loc->PrmLst['noerr'])) {
					$Pos = $this->meth_Locator_Replace($Txt,$Loc,$x,false);
				} else {
					$Pos = $Loc->PosEnd + 1;
					$this->meth_Misc_Alert('Merge PHP global variables','Can\'t merge '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' because there is no PHP global variable named \''.$Loc->SubLst[0].'\'.',true);
				}
			}
		}
	}

	if ($HtmlConv===false) $this->HtmlCharSet = $HtmlCharSet;

}

function meth_Merge_System(&$Loc) {
// This function enables to merge TBS special fields

	if (isset($Loc->SubLst[1])) {
		switch ($Loc->SubLst[1]) {
		case 'now':
			$x = mktime();
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'version':
			$x = '2.01';
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'script_name':
			if (isset($_SERVER)) { // PHP<4.1.0 compatibilty
				$x = tbs_Misc_GetFilePart($_SERVER['PHP_SELF'],1);
			} else {
				global $HTTP_SERVER_VARS;
				$x = tbs_Misc_GetFilePart($HTTP_SERVER_VARS['PHP_SELF'],1);
			}
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'template_name':
			return $this->meth_Locator_Replace($this->Source,$Loc,$this->_LastFile,false);
		case 'template_date':
			$x = filemtime($this->_LastFile);
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'template_path':
			$x = tbs_Misc_GetFilePart($this->_LastFile,0);
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'name':
			$x = 'TinyButStrong';
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'logo':
			$x = '**TinyButStrong**';
			return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
		case 'charset':
			return $this->meth_Locator_Replace($this->Source,$Loc,$this->HtmlCharSet,false);
		case 'tplvars':
			if ($Loc->SubNbr==2) {
				$x = implode(',',array_keys($this->TplVars));
				return $this->meth_Locator_Replace($this->Source,$Loc,$x,false);
			} else {
				if (isset($this->TplVars[$Loc->SubLst[2]])) {
					array_shift($Loc->SubLst);
					array_shift($Loc->SubLst);
					$Loc->SubNbr = $Loc->SubNbr - 2;
					return $this->meth_Locator_Replace($this->Source,$Loc,$this->TplVars,true);
				} else {
					$this->meth_Misc_Alert('System Fields','Can\'t merge ['.$Loc->FullName.'] because property TplVars doesn\'t have any item named \''.$Loc->SubLst[2].'\'.');
					return $Loc->PosBeg+1;
				}
			}
		case '':
			$this->meth_Misc_Alert('System Fields','Can\'t merge ['.$Loc->FullName.'] because it doesn\'t have any requested keyword.');
			return $Loc->PosBeg+1;
		default:
			$this->meth_Misc_Alert('System Fields','Can\'t merge ['.$Loc->FullName.'] because \''.$Loc->SubLst[1].'\' is an unknown keyword.');
			return $Loc->PosBeg+1;
		}
	} else {
		$this->meth_Misc_Alert('System Fields','Can\'t merge ['.$Loc->FullName.'] because it doesn\'t have any subname.');
		return $Loc->PosBeg+1;
	}

}

function meth_Merge_Special($Type) {
// Proceed to one of the special merge

	if ($Type==='*') $Type = 'onload,onshow,var';

	$TypeLst = split(',',$Type);
	foreach ($TypeLst as $Type) {
		switch ($Type) {
		case 'var':	$this->meth_Merge_PhpVar($this->Source,true); break;
		case 'onload': $this->meth_Merge_Auto2($this->Source,'onload',true,true); break;
		case 'onshow': $this->meth_Merge_Auto2($this->Source,'onshow',false,true); break;
		}
	}

}

function meth_Merge_SectionCached(&$LocR,&$BlockId,&$CurrRec,&$RecNum,&$RecKey) {

	$Txt = $LocR->BlockSrc[$BlockId];

	if ($LocR->OnSection) {
		$Txt0 = $Txt;
		$Fct = $LocR->OnSectionFct;
		$Fct($LocR->BlockName[$BlockId],$CurrRec,$Txt,$RecNum);
		if ($Txt0===$Txt) {
			$LocLst = &$LocR->BlockLoc[$BlockId];
			$iMax = $LocLst[0];
			$PosMax = strlen($Txt);
			$DoUnCached = &$LocR->BlockChk[$BlockId];
		} else {
			$iMax = 0;
			$DoUnCached = true;
		}
	} else {
		$LocLst = &$LocR->BlockLoc[$BlockId];
		$iMax = $LocLst[0];
		$PosMax = strlen($Txt);
		$DoUnCached = &$LocR->BlockChk[$BlockId];
	}

	if ($RecNum===false) { // Erase all fields

		$x = '';

		// Chached locators
		for ($i=$iMax;$i>0;$i--) {
			if ($LocLst[$i]->PosBeg<$PosMax) {
				$this->meth_Locator_Replace($Txt,$LocLst[$i],$x,false);
				if ($LocLst[$i]->Enlarged) {
					$PosMax = $LocLst[$i]->PosBeg;
					$LocLst[$i]->PosBeg = $LocLst[$i]->PosBeg0;
					$LocLst[$i]->PosEnd = $LocLst[$i]->PosEnd0;
					$LocLst[$i]->Enlarged = false;
				}
			}
		}

		// Unchached locators
		if ($DoUnCached) {
			$BlockName = &$LocR->BlockName[$BlockId];
			$Pos = 0;
			while ($Loc = $this->meth_Locator_FindTbs($Txt,$BlockName,$Pos,'.')) $Pos = $this->meth_Locator_Replace($Txt,$Loc,$x,false);
		}		

	} else {

		// Chached locators
		for ($i=$iMax;$i>0;$i--) {
			if ($LocLst[$i]->PosBeg<$PosMax) {
				if ($LocLst[$i]->IsRecInfo) {
					if ($LocLst[$i]->RecInfo==='#') {
						$this->meth_Locator_Replace($Txt,$LocLst[$i],$RecNum,false);
					} else {
						$this->meth_Locator_Replace($Txt,$LocLst[$i],$RecKey,false);
					}
				} else {
					$this->meth_Locator_Replace($Txt,$LocLst[$i],$CurrRec,true);
				}
				if ($LocLst[$i]->Enlarged) {
					$PosMax = $LocLst[$i]->PosBeg;
					$LocLst[$i]->PosBeg = $LocLst[$i]->PosBeg0;
					$LocLst[$i]->PosEnd = $LocLst[$i]->PosEnd0;
					$LocLst[$i]->Enlarged = false;
				}
			}
		}

		// Unchached locators
		if ($DoUnCached) {
			$BlockName = &$LocR->BlockName[$BlockId];
			foreach ($CurrRec as $key => $val) {
				$Pos = 0;
				$Name = $BlockName.'.'.$key;
				while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$Pos,'.')) {
					$Pos = $this->meth_Locator_Replace($Txt,$Loc,$val,true);
				}
			}
			$Pos = 0;
			$Name = $BlockName.'.#';
			while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$Pos,'.')) $Pos = $this->meth_Locator_Replace($Txt,$Loc,$RecNum,true);
			$Pos = 0;
			$Name = $BlockName.'.$';
			while ($Loc = $this->meth_Locator_FindTbs($Txt,$Name,$Pos,'.')) $Pos = $this->meth_Locator_Replace($Txt,$Loc,$RecKey,true);
		}

	}

	return $Txt;

}

function meth_Merge_SectionSerial(&$LocR,&$SecId,&$SerialNum,&$SerialMax,&$SerialTxt) {

	$Txt = $LocR->BlockSrc[$LocR->SectionBid[$SecId]];
	$LocLst = &$LocR->BlockLoc[$LocR->SectionBid[$SecId]];
	$OrdLst = &$LocR->SectionSerialOrd[$SecId];

	// Prepare the Empty Item
	if ($SerialNum<$SerialMax) {
		if ($LocR->SerialEmpty===false) {
			$x = false;
		} else {
			$EmptySrc = &$LocR->SerialEmpty;
		}
	}

	// All Items
	for ($i=$SerialMax;$i>0;$i--) {
		$Sr = $OrdLst[$i];
		if ($Sr>$SerialNum) {
			if ($LocR->SerialEmpty===false) {
				$k = $LocR->SectionSerialBid[$SecId][$Sr];
				$EmptySrc = $this->meth_Merge_SectionCached($LocR,$k,$x,$x,$x); // $x is false
			}
			$Txt = substr_replace($Txt,$EmptySrc,$LocLst[$Sr]->PosBeg,$LocLst[$Sr]->PosEnd-$LocLst[$Sr]->PosBeg+1);
		} else {
			$Txt = substr_replace($Txt,$SerialTxt[$Sr],$LocLst[$Sr]->PosBeg,$LocLst[$Sr]->PosEnd-$LocLst[$Sr]->PosBeg+1);
		}
	}

	// Update variables
	$SerialNum = 0;
	$SerialTxt = array();

	return $Txt;

}

function meth_Merge_Auto2(&$Txt,$Name,$TplVar,$AcceptGrp) {
// onload - onshow
	$GrpDisplayed = array();
	$GrpExclusive = array();
	$P1 = false;
	$Pos = 0;
	$PosMax = 0;
	$NbrSelf = 0;
	if ($AcceptGrp) {
		$ChrSub = '_';
	} else {
		$ChrSub = '';
	}

	while ($Loc=$this->meth_Locator_FindBlockNext($Txt,$Name,$Pos,$ChrSub,true,$P1)) {

		// Check for self embeded files
		if ($Loc->PosEnd>$PosMax) {
			$PosMax = $Loc->PosEnd;
			$NbrSelf = 0;
		} else {
			$NbrSelf++;
			if ($NbrSelf>64) return $this->meth_Misc_Alert('Automatic fields','The field '.$this->ChrOpen.$Loc->FullName.$this->ChrClose.' can\'t be merged because the limit (64) is riched. You maybe have self-included templates.');
		}

		if ($Loc->BlockFound) {

			if (!isset($GrpDisplayed[$Loc->SubName])) {
				$GrpDisplayed[$Loc->SubName]=false;
				$GrpExclusive[$Loc->SubName]= !($AcceptGrp and ($Loc->SubName===''));
			}
			$Displayed = &$GrpDisplayed[$Loc->SubName];
			$Exclusive = &$GrpExclusive[$Loc->SubName];

			$DelBlock = false;
			$DelField = false;
			if ($Displayed and $Exclusive) {
				$DelBlock = true;
			} else {
				if (isset($Loc->PrmLst['when'])) {
					if (isset($Loc->PrmLst['several'])) $Exclusive=false;
					$x = $Loc->PrmLst['when'];
					$this->meth_Merge_PhpVar($x,true);
					if (tbs_Misc_CheckCondition($x)) {
						$DelField = true;
						$Displayed = true;
					} else {
						$DelBlock = true;
					}
				} elseif(isset($Loc->PrmLst['default'])) {
					if ($Displayed) {
						$DelBlock = true;
					} else {
						$Displayed = true;
						$DelField = true;
					}
					$Exclusive = true; // No more block displayed for the group after VisElse
				}
			}
							
			// Del parts
			if ($DelField) {
				if ($Loc->PosBeg2!==false) $Txt = substr_replace($Txt,'',$Loc->PosBeg2,$Loc->PosEnd2-$Loc->PosBeg2+1);
				$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
				$Pos = $Loc->PosBeg;
			} else {
				if ($Loc->PosBeg2===false) {
					tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['block'],true,false);
				} else {
					$Loc->PosEnd = $Loc->PosEnd2;
				}
				if ($DelBlock) {
					$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
				} else {
					// Merge the block as if it was a field
					$x = '';
					$this->meth_Locator_Replace($Txt,$Loc,$x,false);
				}
				$Pos = $Loc->PosBeg;
			}

		} else {
			// Check for Template Var
			if ($TplVar) {
				if (isset($Loc->PrmLst['tplvars'])) {
					$Ok = false;
					foreach ($Loc->PrmLst as $Key => $Val) {
						if ($Ok) {
							$this->TplVars[$Key] = $Val;
						} else {
							if ($Key==='tplvars') $Ok = true;
						}
					}
				}
			}
			$x = '';
			$this->meth_Locator_Replace($Txt,$Loc,$x,false);
			$Pos = $Loc->PosBeg;
		}

	}

	return count($GrpDisplayed);

}

function meth_Merge_NavigationBar(&$Txt,$BlockName,$Options,$PageCurr,$RecCnt,$PageSize) {

	// Get block parameters
	$PosBeg = 0;
	$PrmLst = array();
	while ($Loc = $this->meth_Locator_FindTbs($Txt,$BlockName,$PosBeg,'.')) {
		if (isset($Loc->PrmLst['block'])) $PrmLst = array_merge($PrmLst,$Loc->PrmLst);
		$PosBeg = $Loc->PosEnd;
	}

	// Prepare options
	if (!is_array($Options)) $Options = array('navsize'=>intval($Options));
	$Options = array_merge($Options,$PrmLst);

	// Default options
	if (!isset($Options['navsize'])) $Options['navsize'] = 10;
	if (!isset($Options['navpos'])) $Options['navpos'] = 'step';
	if (!isset($Options['navdel'])) $Options['navdel'] = '';
	if (!isset($Options['pagemin'])) $Options['pagemin'] = 1;

	// Check options
  if ($Options['navsize']<=0) $Options['navsize'] = 10;
	if ($PageSize<=0) $PageSize = 1;
	if ($PageCurr<$Options['pagemin']) $PageCurr = $Options['pagemin'];

	$CurrPos = 0;
	$CurrNav = array('curr'=>$PageCurr,'first'=>$Options['pagemin'],'last'=>-1,'bound'=>false);

	// Calculate displayed PageMin and PageMax
	if ($Options['navpos']=='centred') {
		$PageMin = $Options['pagemin']-1+$PageCurr - intval(floor($Options['navsize']/2));
	} else {
		// Display by block
		$PageMin = $Options['pagemin']-1+$PageCurr - ( ($PageCurr-1) % $Options['navsize']);
	}
	$PageMin = max($PageMin,$Options['pagemin']);
	$PageMax = $PageMin + $Options['navsize'] - 1;

	// Calculate previous and next pages
	$CurrNav['prev'] = $PageCurr - 1;
	if ($CurrNav['prev']<$Options['pagemin']) {
		$CurrNav['prev'] = $Options['pagemin'];
		$CurrNav['bound'] = $Options['pagemin'];
	}
	$CurrNav['next'] = $PageCurr + 1;
	if ($RecCnt>=0) {
		$PageCnt = $Options['pagemin']-1 + intval(ceil($RecCnt/$PageSize));
		$PageMax = min($PageMax,$PageCnt);
		$PageMin = max($Options['pagemin'],$PageMax-$Options['navsize']+1);
	} else {
		$PageCnt = $Options['pagemin']-1;
	}
	if ($PageCnt>=$Options['pagemin']) {
		if ($PageCurr>=$PageCnt) {
			$CurrNav['next'] = $PageCnt;
			$CurrNav['last'] = $PageCnt;
			$CurrNav['bound'] = $PageCnt;
		} else {
			$CurrNav['last'] = $PageCnt;
		}
	}	

	// Display or hide the bar
	if ($Options['navdel']=='') {
		$Display = true;
	} else {
		$Display = (($PageMax-$PageMin)>0);
	}

	// Merge general information
	$Pos = 0;
	while ($Loc = $this->meth_Locator_FindTbs($Txt,$BlockName,$Pos,'.')) {
		$Pos = $Loc->PosBeg + 1;
		$x = strtolower($Loc->SubName);
		if (isset($CurrNav[$x])) {
			$Val = $CurrNav[$x];
			if ($CurrNav[$x]==$CurrNav['bound']) {
				if (isset($Loc->PrmLst['endpoint'])) {
					$Val = '';
				}
			}
			$this->meth_Locator_Replace($Txt,$Loc,$Val,false);
		}
	}

	// Merge pages
	$Query = '';
	if ($Display) {
		$Data = array();
		$RecSpe = 0;
		$RecCurr = 0;
		for ($PageId=$PageMin;$PageId<=$PageMax;$PageId++) {
			$RecCurr++;
			if ($PageId==$PageCurr) $RecSpe = $RecCurr;
			$Data[] = array('page'=>$PageId);
		}
		$this->meth_Merge_Block($Txt,$BlockName,$Data,$Query,0,0,$RecSpe);
		if ($Options['navdel']!='') { // Delete the block definition tags
			$PosBeg = 0;
			while ($Loc = $this->meth_Locator_FindTbs($Txt,$Options['navdel'],$PosBeg,'.')) {
				$PosBeg = $Loc->PosBeg;
				$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
			}
		}
	} else {
		if ($Options['navdel']!='') {
			$SrcType = 'text';
			$this->meth_Merge_Block($Txt,$Options['navdel'],$SrcType,$Query,0,0,0);
		}
	}

}

// Convert a string to Html with several options
function meth_Html_Conv(&$Txt,&$BrConv,&$WhiteSp) {

	if ($this->HtmlCharSet==='') {
		$Txt = htmlentities($Txt); // Faster
	} else {
		if ($this->_HtmlCharFct) {
			$Fct = $this->HtmlCharSet;
			$Txt = $Fct($Txt);
		} else {
			$Txt = htmlentities($Txt,ENT_COMPAT,$this->HtmlCharSet);
		}
	}

	if ($WhiteSp) {
		$check = '  ';
		$nbsp = '&nbsp;';
		do {
			$pos = strpos($Txt,$check);
			if ($pos!==false) $Txt = substr_replace($Txt,$nbsp,$pos,1);
		} while ($pos!==false);
	}

	if ($BrConv) $Txt = nl2br($Txt);

}

// Standard alert message provided by TinyButStrong, return False is the message is cancelled.
function meth_Misc_Alert($Source,$Message,$NoErr=false) {
	$x = '<br /><b>TinyButStrong Error</b> ('.$Source.'): '.htmlentities($Message);
	if ($NoErr) $x = $x.' <em>This message can be cancelled using parameter \'noerr\'.</em>';
	$x = $x."<br />\n";
	$x = str_replace($this->ChrOpen,$this->ChrProtect,$x);
	echo $x;
	return false;
}

function meth_Cache_Save($CacheFile,&$Txt) {
	$fid = @fopen($CacheFile, 'w');
	if ($fid===false) {
		$this->meth_Misc_Alert('Cache System','The cache file \''.$CacheFile.'\' can not be saved.');
		return false;
	} else {
		flock($fid,2); // acquire an exlusive lock
		fwrite($fid,$Txt);
		flock($fid,3); // release the lock
		fclose($fid);
		return true;
	}
}

} // class clsTinyButStrong

// *********************************************

function tbs_Misc_UnlinkVar($NewVal) {
	return $NewVal;
}

function tbs_Misc_GetStrId($Txt) {
	$Txt = strtolower($Txt);
	$Txt = str_replace('-','_',$Txt);
	$x = '';
	$i = 0;
	$iMax = strlen($Txt2);
	while ($i<$iMax) {
		if (($Txt[$i]==='_') or (($Txt[$i]>='a') and ($Txt[$i]<='z')) or (($Txt[$i]>='0') and ($Txt[$i]<='9'))) {
			$x .= $Txt[$i];
			$i++;
		} else {
			$i = $iMax;
		}
	}
	return $x;
}

function tbs_Misc_CheckCondition($Str) {
// Check if an expression like "exrp1=expr2" is true or false.

	// Find operator and position
	$Ope = '=';
	$Len = 1;
	$Max = strlen($Str)-1;
	$Pos = strpos($Str,$Ope);
	if ($Pos===false) {
		$Ope = '+';
		$Pos = strpos($Str,$Ope);
		if ($Pos===false) return false;
		if (($Pos>0) and ($Str[$Pos-1]==='-')) {
			$Ope = '-+'; $Pos--; $Len=2;
		} elseif (($Pos<$Max) and ($Str[$Pos+1]==='-')) {
			$Ope = '+-'; $Len=2;
		} else {
			return false;
		}
	} else {
		if ($Pos>0) {
			$x = $Str[$Pos-1];
			if ($x==='!') {
				$Ope = '!='; $Pos--; $Len=2;
			} elseif ($Pos<$Max) {
				$y = $Str[$Pos+1];
				if ($y==='=') {
					$Len=2;
				} elseif (($x==='+') and ($y==='-')) {
					$Ope = '+=-'; $Pos--; $Len=3;
				} elseif (($x==='-') and ($y==='+')) {
					$Ope = '-=+'; $Pos--; $Len=3;
				}
			} else {
			}
		}
	}

	// Read values
	$Val1  = trim(substr($Str,0,$Pos));
	$Nude1 = tbs_Misc_DelDelimiter($Val1,'\'');
	$Val2  = trim(substr($Str,$Pos+$Len));
	$Nude2 = tbs_Misc_DelDelimiter($Val2,'\'');

	// Compare values
	if ($Ope==='=') {
		return (strcasecmp($Val1,$Val2)==0);
	} elseif ($Ope==='!=') {
		return (strcasecmp($Val1,$Val2)!=0);
	} else {
		if ($Nude1) $Val1 = (float) $Val1;
		if ($Nude2) $Val2 = (float) $Val2;
		if ($Ope==='+-') {
			return ($Val1>$Val2);
		} elseif ($Ope==='-+') {
			return ($Val1 < $Val2);
		} elseif ($Ope==='+=-') {
			return ($Val1 >= $Val2);
		} elseif ($Ope==='-=+') {
			return ($Val1<=$Val2);
		} else {
			return false;
		}
	}

}

function tbs_Misc_DelDelimiter(&$Txt,$Delim) {
// Delete the string delimiters
	$len = strlen($Txt);
	if (($len>1) and ($Txt[0]===$Delim)) {
		if ($Txt[$len-1]===$Delim) $Txt = substr($Txt,1,$len-2);
		return false;
	} else {
		return true;
	}
}

function tbs_Misc_GetFile(&$Txt,$File) {
// Load the content of a file into the text variable.
	$Txt = '';
	$fd = @fopen($File, 'r'); // 'rb' if binary for some OS
	if ($fd===false) return false;
	$fs = @filesize($File); // return False for an URL
	if ($fs===false) {
		while (!feof($fd)) $Txt .= fread($fd,4096);
	} else {
		if ($fs>0) $Txt = fread($fd,$fs);
	}	
	fclose($fd);
	return true;
}

function tbs_Misc_GetFilePart($File,$Part) {
	$Pos = strrpos($File,'/');
	if ($Part===0) { // Path
		if ($Pos===false) {
			return '';
		} else {
			return substr($File,0,$Pos+1);
		}
	} else { // File
		if ($Pos===false) {
			return $File;
		} else {
			return substr($File,$Pos+1);
		}
	}
}

function tbs_Misc_Format(&$Loc,&$Value) {
// This function return the formated representation of a Date/Time or numeric variable using a 'VB like' format syntax instead of the PHP syntax.

	global $_tbs_FrmSimpleLst;

	$FrmStr = $Loc->PrmLst['frm'];
	$CheckNumeric = true;
	if (is_string($Value)) $Value = trim($Value);

	// Manage Multi format strings
	if (strpos($FrmStr,'|')!==false) {

		global $_tbs_FrmMultiLst;

		// Save the format if it doesn't exist
		if (isset($_tbs_FrmMultiLst[$FrmStr])) {
			$FrmLst = &$_tbs_FrmMultiLst[$FrmStr];
		} else {
			$FrmLst = explode('|',$FrmStr); // syntax : PostiveFrm|NegativeFrm|ZeroFrm|NullFrm
			$FrmNbr = count($FrmLst);
			if (($FrmNbr<=1) or ($FrmLst[1]==='')) {
				$FrmLst[1] = &$FrmLst[0]; // negativ
				$FrmLst['abs'] = false;
			} else {
				$FrmLst['abs'] = true;
			}
			if (($FrmNbr<=2) or ($FrmLst[2]==='')) $FrmLst[2] = &$FrmLst[0]; // zero
			if (($FrmNbr<=3) or ($FrmLst[3]==='')) $FrmLst[3] = ''; // null
			$_tbs_FrmMultiLst[$FrmStr] = $FrmLst;
		}

		// Select the format
		if (is_numeric($Value)) {
			if (is_string($Value)) $Value = 0.0 + $Value;
			if ($Value>0) {
				$FrmStr = &$FrmLst[0];
			} elseif ($Value<0) {
				$FrmStr = &$FrmLst[1];
				if ($FrmLst['abs']) $Value = abs($Value);
			} else { // zero
				$FrmStr = &$FrmLst[2];
				$Minus = '';
			}
			$CheckNumeric = false;
		} else {
			$Value = ''.$Value;
			if ($Value==='') {
				return $FrmLst[3];
			} else {
				return $Value;
			}
		}

	}

	if ($FrmStr==='') return ''.$Value;

	// Retrieve the correct simple format
	if (!isset($_tbs_FrmSimpleLst[$FrmStr])) tbs_Misc_FormatSave($FrmStr);

	$Frm = &$_tbs_FrmSimpleLst[$FrmStr];

	switch ($Frm['type']) {
	case 'num' :
		// NUMERIC
		if ($CheckNumeric) {
			if (is_numeric($Value)) {
				if (is_string($Value)) $Value = 0.0 + $Value;
			} else {
				return ''.$Value;
			}
		}
		if ($Frm['PerCent']) $Value = $Value * 100;
		$Value = number_format($Value,$Frm['DecNbr'],$Frm['DecSep'],$Frm['ThsSep']);
		return substr_replace($FrmStr,$Value,$Frm['Pos'],$Frm['Len']);
		break;
	case 'date' :
		// DATE
		if (is_string($Value)) {
			if ($Value==='') return '';
			$x = strtotime($Value);
			if ($x===-1) {
				if (!is_numeric($Value)) $Value = 0;
			} else {
				$Value = &$x;
			}
		} else {
			if (!is_numeric($Value)) return ''.$Value;
		}
		if (isset($Loc->PrmLst['locale'])) {
			return strftime($Frm['str_loc'],$Value);
		} else {
			return date($Frm['str_us'],$Value);
		}
		break;
	default:
		return $Frm['string'];
		break;
	}

}

function tbs_Misc_FormatSave(&$FrmStr) {

	global $_tbs_FrmSimpleLst;

	$nPosEnd = strrpos($FrmStr,'0');

	if ($nPosEnd!==false) {

		// Numeric format
		$nDecSep = '.';
		$nDecNbr = 0;
		$nDecOk = true;

		if (substr($FrmStr,$nPosEnd+1,1)==='.') {
			$nPosEnd++;
			$nPosCurr = $nPosEnd;
		} else {
			$nPosCurr = $nPosEnd - 1;
			while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
				$nPosCurr--;
			}
			if (($nPosCurr>=1) and ($FrmStr[$nPosCurr-1]==='0')) {
				$nDecSep = $FrmStr[$nPosCurr];
				$nDecNbr = $nPosEnd - $nPosCurr;
			} else {
				$nDecOk = false;
			}
		}

		// Thousand separator
		$nThsSep = '';
		if (($nDecOk) and ($nPosCurr>=5)) {
			if ((substr($FrmStr,$nPosCurr-3,3)==='000') and ($FrmStr[$nPosCurr-4]!=='') and ($FrmStr[$nPosCurr-5]==='0')) {
				$nPosCurr = $nPosCurr-4;
				$nThsSep = $FrmStr[$nPosCurr];
			}
		}

		// Pass next zero
		if ($nDecOk) $nPosCurr--;
		while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
			$nPosCurr--;
		}

		// Percent
		$nPerCent = (strpos($FrmStr,'%')===false) ? false : true;

		$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'num','Pos'=>($nPosCurr+1),'Len'=>($nPosEnd-$nPosCurr),'ThsSep'=>$nThsSep,'DecSep'=>$nDecSep,'DecNbr'=>$nDecNbr,'PerCent'=>$nPerCent);

	} else { // if ($nPosEnd!==false)

		// Date format
		$FrmPHP = '';
		$FrmLOC = '';
		$Local = false;
		$StrIn = false;
		$iMax = strlen($FrmStr);
		$Cnt = 0;

		for ($i=0;$i<$iMax;$i++) {

			if ($StrIn) {
				// We are in a string part
				if ($FrmStr[$i]===$StrChr) {
					if (substr($FrmStr,$i+1,1)===$StrChr) {
						$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
						$FrmLOC .= $FrmStr[$i];
						$i++;
					} else {
						$StrIn = false;
					}
				} else {
					$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
					$FrmLOC .= $FrmStr[$i];
				}
			} else {
				if (($FrmStr[$i]==='"') or ($FrmStr[$i]==='\'')) {
					// Check if we have the opening string char
					$StrIn = true;
					$StrChr = $FrmStr[$i];
				} else {
					$Cnt++;
					if     (strcasecmp(substr($FrmStr,$i,4),'yyyy')===0) { $FrmPHP .= 'Y'; $FrmLOC .= '%Y'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'yy'  )===0) { $FrmPHP .= 'y'; $FrmLOC .= '%y'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'mmmm')===0) { $FrmPHP .= 'F'; $FrmLOC .= '%B'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'mmm' )===0) { $FrmPHP .= 'M'; $FrmLOC .= '%b'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'mm'  )===0) { $FrmPHP .= 'm'; $FrmLOC .= '%m'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'m'   )===0) { $FrmPHP .= 'n'; $FrmLOC .= '%m'; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'wwww')===0) { $FrmPHP .= 'l'; $FrmLOC .= '%A'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'www' )===0) { $FrmPHP .= 'D'; $FrmLOC .= '%a'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'w'   )===0) { $FrmPHP .= 'w'; $FrmLOC .= '%u'; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'dddd')===0) { $FrmPHP .= 'l'; $FrmLOC .= '%A'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'ddd' )===0) { $FrmPHP .= 'D'; $FrmLOC .= '%a'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'dd'  )===0) { $FrmPHP .= 'd'; $FrmLOC .= '%d'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'d'   )===0) { $FrmPHP .= 'j'; $FrmLOC .= '%d'; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'hh'  )===0) { $FrmPHP .= 'H'; $FrmLOC .= '%H'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'nn'  )===0) { $FrmPHP .= 'i'; $FrmLOC .= '%M'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'ss'  )===0) { $FrmPHP .= 's'; $FrmLOC .= '%S'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'xx'  )===0) { $FrmPHP .= 'S'; $FrmLOC .= ''  ; $i += 1; }
					else {
						$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
						$FrmLOC .= $FrmStr[$i]; // protected char
						$Cnt--;
					}
				}
			} //-> if ($StrIn) {...} else

		} //-> for ($i=0;$i<$iMax;$i++)

		if ($Cnt>0) {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'date','str_us'=>$FrmPHP,'str_loc'=>$FrmLOC);
		} else {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'else','string'=>$FrmStr);
		}

	} // if ($nPosEnd!==false) {...} else

}

function tbs_Locator_ReadPrm(&$Txt,$Pos,$ChrsPrm,$ChrsEqu,$ChrsStr,$ChrsOpen,$ChrsClose,$ChrEnd,$LenMax,&$Loc,&$PosEnd) {
/* This function reads parameters that follow the Begin Position, and it returns parameters in an array
$Pos       : position in $Txt where the scan begins
$ChrsPrm   : a string that contains all characters that can be a parameter separator (typically : space and ;)
$ChrsEqu   : a string that contains all characters that can be an equal symbol (used to get prm value )
$ChrsStr   : a string that contains all characters that can be a string delimiters (typically : ' and ")
$ChrsOpen  : a string that contains all characters that can be an opening bracket (typically : ( )
$ChrsClose : a string that contains all characters that can be an closing bracket (typically : ( )
$ChrEnd    : the character that marks the end of the parameters list.
$LenMax    : the maximum of characters to read (enables to not read all document when parameters have an unvalide syntax)
$Loc       : the current TBS locator
$PosEnd    : (returned value) the position of the $ChrEnd in the $Txt string
*/

	// variables initialisation
	$PosCur = $Pos;           // The cursor position
	$PosBuff = true;          // True if the current char has to be added to the buffer
	$PosEnd = false;          // True if the end char has been met
	$PosMax = strlen($Txt)-1; // The max position that the cursor can go
	if ($LenMax>0) {
		if ($PosMax>$PosDeb+$LenMax) {
			$PosMax = $PosDeb+$LenMax;
		}
	}

	$PrmNbr = 0;
	$PrmName = '';
	$PrmBuff = '';
	$PrmPosBeg = false;
	$PrmPosEnd = false;
	$PrmEnd  = false;
	$PrmPosEqu  = false; // Position of the first equal symbol
	$PrmChrEqu  = '';    // Char of the first equal symbol
	$PrmCntOpen = 0;     // Number of bracket inclusion. 0 means no bracket encapuslation.
	$PrmIdxOpen = false; // Index of the current opening bracket in the $ChrsOpen array. False means we are not inside a bracket.
	$PrmCntStr = 0;      // Number of string delimiters found.
	$PrmIdxStr = false;  // Index of the current string delimiter. False means we are not inside a string.
	$PrmIdxStr1 = false; // Save the first string delimiter found.

	do {

		if ($PosCur>$PosMax) return;

		if ($PrmIdxStr===false) {

			// we are not inside a string, we check if it's the begining of a new string
			$PrmIdxStr = strpos($ChrsStr,$Txt[$PosCur]);

			if ($PrmIdxStr===false) {
				// we are not inside a string, we check if we are not inside brackets
				if ($PrmCntOpen===0) {
					// we are not inside brackets
					if ($Txt[$PosCur]===$ChrEnd) {// we check if it's the end of the parameters list
						$PosEnd = $PosCur;
						$PrmEnd = true;
						$PosBuff = false;
					} elseif (strpos($ChrsEqu,$Txt[$PosCur])!==false) { // we check if it's an equal symbol
							if ($PrmPosEqu===false) {
							if (trim($PrmBuff)!=='') {
								$PrmPosEqu = $PosCur;
								$PrmChrEqu = $Txt[$PosCur];
							}
						} elseif ($PrmChrEqu===' ') {
							if ($PosCur==$PrmPosEqu+1) {
								$PrmPosEqu = $PosCur;
								$PrmChrEqu = $Txt[$PosCur];
							}
						}
					} elseif (strpos($ChrsPrm,$Txt[$PosCur])!==false) { // we check if it's a parameter separator
						$PosBuff = false;
						if ($Txt[$PosCur]===' ') {// The space char can be a parameter separator only in HTML locators
							if ($PrmBuff!=='') {
								$PrmEnd = true;
							}
						} else { //-> if ($Txt[$PosCur]===' ') {...
							// We have a ';' separator
							$PrmEnd = true;
						}
					} else {
						// check if it's an opening bracket
						$PrmIdxOpen = strpos($ChrsOpen,$Txt[$PosCur]);
						if ($PrmIdxOpen!==false) {
							$PrmCntOpen++;
						}
					}
				} else { //--> if ($PrmCntOpen==0)
					// we are inside brackets, we have to check if there is another opening bracket or a closing bracket
					if ($Txt[$PosCur]===$ChrsOpen[$PrmIdxOpen]) {
						$PrmCntOpen++;
					} elseif ($Txt[$PosCur]===$ChrsClose[$PrmIdxOpen]) {
						$PrmCntOpen--;
					}
				}
			} else { //--> if ($IdxStr===false)
				// we meet a new string
				$PrmCntStr++; // count the number of string delimiters met for the current parameter
				if ($PrmCntStr===1) $PrmIdxStr1=$PrmIdxStr; // save the first delimiter for the current parameter
			} //--> if ($IdxStr===false)

		} else { //--> if ($IdxStr===false)

			// we are inside a string,

			if ($Txt[$PosCur]===$ChrsStr[$PrmIdxStr]) {// we check if we are on a char delimiter
				if ($PosCur===$PosMax) {
					$PrmIdxStr = false;
				} else {
					// we check if the next char is also a string delimiter, is it's so, the string continue
					if ($Txt[$PosCur+1]===$ChrsStr[$PrmIdxStr]) {
						$PosCur++; // the string continues
					} else {
						$PrmIdxStr = false; // the string ends
					}
				}
			}

		} //--> if ($IdxStr===false)

		// Check if it's the end of the scan
		if ($PosEnd===false) {
			if ($PosCur>=$PosMax) {
				$PosEnd = $PosCur; // end of the scan
				$PrmEnd = true;
			}
		}

		// Add the current char to the buffer
		if ($PosBuff) {
			$PrmBuff .= $Txt[$PosCur];
			if ($PrmPosBeg===false) $PrmPosBeg = $PosCur;
			$PrmPosEnd = $PosCur;
		} else {
			$PosBuff = true;
		}

		// analyze the current parameter
		if ($PrmEnd===true) {
			if (strlen($PrmBuff)>0) {
				if (($PrmNbr===0) and ($Loc->SubOk) ) {
					// Set the SubName value
					$Loc->SubName = $PrmBuff;
					$PrmEquMode = 0;
				} else {
					if ($PrmPosEqu===false) {
						$PrmName = trim($PrmBuff);
						$PrmBuff = true;
					} else {
						$PrmName = trim(substr($PrmBuff,0,$PrmPosEqu-$PrmPosBeg));
						$PrmBuff = trim(substr($PrmBuff,$PrmPosEqu-$PrmPosBeg+1));
						if ($PrmCntStr===1) tbs_Misc_DelDelimiter($PrmBuff,$ChrsStr[$PrmIdxStr1]);
					}
					$Loc->PrmLst[$PrmName] = $PrmBuff;
				}
				$PrmNbr++; // Useful for subname identification
				$PrmBuff = '';
				$PrmPosBeg = false;
				$PrmCntStr = 0;
				$PrmCntOpen = 0;
				$PrmIdxStr = false;
				$PrmIdxOpen = false;
				$PrmPosEqu = false;
			}
			$PrmEnd  = false;
		}

		// next char
		$PosCur++;

	} while ($PosEnd===false);

}

function tbs_Locator_EnlargeToStr(&$Txt,&$Loc,$StrBeg,$StrEnd) {
/*
This function enables to enlarge the pos limits of the Locator.
If the search result is not correct, $PosBeg must not change its value, and $PosEnd must be False.
This is because of the calling function.
*/

	// Search for the begining string
	$Pos = $Loc->PosBeg;
	$Ok = false;
	do {
		$Pos = strrpos(substr($Txt,0,$Pos),$StrBeg[0]);
		if ($Pos!==false) {
			if (substr($Txt,$Pos,strlen($StrBeg))===$StrBeg) $Ok = true;
		}
	} while ( (!$Ok) and ($Pos!==false) );

	if ($Ok) {
		$PosEnd = strpos($Txt,$StrEnd,$Loc->PosEnd + 1);
		if ($PosEnd===false) {
			$Ok = false;
		} else {
			$Loc->PosBeg = $Pos;
			$Loc->PosEnd = $PosEnd + strlen($StrEnd) - 1;
		}
	}

	return $Ok;

}

function tbs_Locator_EnlargeToTag(&$Txt,&$Loc,$Tag,$ReadPrm,$ReturnSrc) {
//Modify $Loc, return false if tags not found, returns the source of the locator if $ReturnSrc=true

	if ($Tag==='') { return false; }
	elseif ($Tag==='row') {$Tag = 'tr'; }
	elseif ($Tag==='opt') {$Tag = 'option'; }

	$Encaps = 1;
	$Extend = 0;
	if ($ReadPrm) {
		if (isset($Loc->PrmLst['encaps'])) $Encaps = abs(intval($Loc->PrmLst['encaps']));
		if (isset($Loc->PrmLst['extend'])) $Extend = intval($Loc->PrmLst['extend']);
	}

	$TagO = tbs_Html_FindTag($Txt,$Tag,true,$Loc->PosBeg-1,false,$Encaps,false);
	if ($TagO===false) return false;
	$TagC = tbs_Html_FindTag($Txt,$Tag,false,$Loc->PosEnd+1,true,$Encaps,false);
	if ($TagC==false) return false;

	// It's ok, we get the text string between the locators (including the locators!)
	$Ok = true;
	$PosBeg = $TagO->PosBeg;
	$PosEnd = $TagC->PosEnd;

	// Extend
	if ($Extend===0) {
		if ($ReturnSrc) {
			$Ok = '';
			if ($Loc->PosBeg>$TagO->PosEnd) $Ok .= substr($Txt,$TagO->PosEnd+1,min($Loc->PosBeg,$TagC->PosBeg)-$TagO->PosEnd-1);
			if ($Loc->PosEnd<$TagC->PosBeg) $Ok .= substr($Txt,max($Loc->PosEnd,$TagO->PosEnd)+1,$TagC->PosBeg-max($Loc->PosEnd,$TagO->PosEnd)-1);
		}
	} else { // Forward
		$TagC = true;
		for ($i=$Extend;$i>0;$i--) {
			if ($TagC!==false) {
				$TagO = tbs_Html_FindTag($Txt,$Tag,true,$PosEnd+1,true,1,false);
				if ($TagO!==false) {
					$TagC = tbs_Html_FindTag($Txt,$Tag,false,$TagO->PosEnd+1,true,0,false);
					if ($TagC!==false) {
						$PosEnd = $TagC->PosEnd;
					}
				}
			}
		}
		$TagO = true;
		for ($i=$Extend;$i<0;$i++) { // Backward
			if ($TagO!==false) {
				$TagC = tbs_Html_FindTag($Txt,$Tag,false,$PosBeg-1,false,1,false);
				if ($TagC!==false) {
					$TagO = tbs_Html_FindTag($Txt,$Tag,true,$TagC->PosBeg-1,false,0,false);
					if ($TagO!==false) {
						$PosBeg = $TagO->PosBeg;
					}
				}
			}
		}
	} //-> if ($Extend!==0) {

	$Loc->PosBeg = $PosBeg;
	$Loc->PosEnd = $PosEnd;

	return $Ok;

}

function tbs_Html_Max(&$Txt,&$Nbr) {
// Limit the number of HTML chars

	$pMax = strlen($Txt)-1;
	$p=0;
	$n=0;
	$in = false;
	$ok = true;

	while ($ok) {
		if ($in) {
			if ($Txt[$p]===';') {
				$in = false;
				$n++;
			}
		} else {
			if ($Txt[$p]==='&') {
				$in = true;
			} else {
				$n++;
			}
		}
		if (($n>=$Nbr) or ($p>=$pMax)) {
			$ok = false;
		} else {
			$p++;
		}
	}

	if (($n>=$Nbr) and ($p<$pMax)) $Txt = substr($Txt,0,$p).'...';

}

function tbs_Html_IsHtml(&$Txt) {
// This function returns True if the text seems to have some HTML tags.

	// Search for opening and closing tags
	$pos = strpos($Txt,'<');
	if ( ($pos!==false) and ($pos<strlen($Txt)-1) ) {
		$pos = strpos($Txt,'>',$pos + 1);
		if ( ($pos!==false) and ($pos<strlen($Txt)-1) ) {
			$pos = strpos($Txt,'</',$pos + 1);
			if ( ($pos!==false)and ($pos<strlen($Txt)-1) ) {
				$pos = strpos($Txt,'>',$pos + 1);
				if ($pos!==false) return true;
			}
		}
	}

	// Search for special char
	$pos = strpos($Txt,'&');
	if ( ($pos!==false)  and ($pos<strlen($Txt)-1) ) {
		$pos2 = strpos($Txt,';',$pos+1);
		if ($pos2!==false) {
			$x = substr($Txt,$pos+1,$pos2-$pos-1); // We extract the found text between the couple of tags
			if (strlen($x)<=10) {
				if (strpos($x,' ')===false) return true;
			}
		}
	}

	// Look for a simple tag
	$Loc1 = tbs_Html_FindTag($Txt,'BR',true,0,true,0,false); // line break
	if ($Loc1!==false) return true;
	$Loc1 = tbs_Html_FindTag($Txt,'HR',true,0,true,0,false); // horizontal line
	if ($Loc1!==false) return true;

	return false;

}

function tbs_Html_GetPart(&$Txt,$Tag,$WithTags=false,$CancelIfEmpty=false) {
// This function returns a part of the HTML document (HEAD or BODY)
// The $CancelIfEmpty parameter enables to cancel the extraction when the part is not found.

	$x = false;

	$LocOpen = tbs_Html_FindTag($Txt,$Tag,true,0,true,0,false);
	if ($LocOpen!==false) {
		$LocClose = tbs_Html_FindTag($Txt,$Tag,false,$LocOpen->PosEnd+1,true,0,false);
		if ($LocClose!==false) {
			if ($WithTags) {
				$x = substr($Txt,$LocOpen->PosBeg,$LocClose->PosEnd - $LocOpen->PosBeg + 1);
			} else {
				$x = substr($Txt,$LocOpen->PosEnd+1,$LocClose->PosBeg - $LocOpen->PosEnd - 1);
			}
		}
	}

	if ($x===false) {
		if ($CancelIfEmpty) {
			$x = $Txt;
		} else {
			$x = '';
		}
	}

	return $x;

}

function tbs_Html_InsertAttribute(&$Txt,&$Attr,$Pos) {
	// Check for XHTML end characters
	if ($Txt[$Pos-1]==='/') {
		$Pos--;
		if ($Txt[$Pos-1]===' ') $Pos--;
	}
	// Insert the parameter
	$Txt = substr_replace($Txt,$Attr,$Pos,0);
}

function &tbs_Html_FindTag(&$Txt,$Tag,$Opening,$PosBeg,$Forward,$Encaps,$WithPrm) {
/* This function is a smarter issue to find an HTML tag.
It enables to ignore full opening/closing couple of tag that could be inserted before the searched tag.
It also enables to pass a number of encapsulations.
To ignore encapsulation and opengin/closing just set $Encaps=0.
*/
	if ($Forward) {
		$Pos = $PosBeg - 1;
	} else {
		$Pos = $PosBeg + 1;
	}
	$TagIsOpening = false;
	$TagClosing = '/'.$Tag;
	if ($Opening) {
		$EncapsEnd = $Encaps;
	} else {
		$EncapsEnd = - $Encaps;
	}
	$EncapsCnt = 0;
	$TagOk = false;

	do {

		// Look for the next tag def
		if ($Forward) {
			$Pos = strpos($Txt,'<',$Pos+1);
		} else {
			if ($Pos<=0) {
				$Pos = false;
			} else {
				$Pos = strrpos(substr($Txt,0,$Pos - 1),'<');
			}
		}

		if ($Pos!==false) {
			// Check the name of the tag
			if (strcasecmp(substr($Txt,$Pos+1,strlen($Tag)),$Tag)==0) {
				$PosX = $Pos + 1 + strlen($Tag); // The next char
				$TagOk = true;
				$TagIsOpening = true;
			} elseif (strcasecmp(substr($Txt,$Pos+1,strlen($TagClosing)),$TagClosing)==0) {
				$PosX = $Pos + 1 + strlen($TagClosing); // The next char
				$TagOk = true;
				$TagIsOpening = false;
			}

			if ($TagOk) {
				// Check the next char
				if (($Txt[$PosX]===' ') or ($Txt[$PosX]==='>')) {
					// Check the encapsulation count
					if ($EncapsEnd==0) {
						// No encaplusation check
						if ($TagIsOpening!==$Opening) $TagOk = false;
					} else {
						// Count the number of encapsulation
						if ($TagIsOpening) {
							$EncapsCnt++;
						} else {
							$EncapsCnt--;
						}
						// Check if it's the expected count
						if ($EncapsCnt!=$EncapsEnd) $TagOk = false;
					}
				} else {
					$TagOk = false;
				}
			} //--> if ($TagOk)

		}
	} while (($Pos!==false) and ($TagOk===false));

	// Search for the end of the tag
	if ($TagOk) {
		$Loc = &new clsTbsLocator;
		if ($WithPrm) {
			$PosEnd = 0;
			tbs_Locator_ReadPrm($Txt,$PosX,' ','=','\'"','','','>',0,$Loc,$PosEnd);
		} else {
			$PosEnd = strpos($Txt,'>',$PosX);
			if ($PosEnd===false) {
				$TagOk = false;
			}
		}
	}

	// Result
	if ($TagOk) {
		$Loc->PosBeg = $Pos;
		$Loc->PosEnd = $PosEnd;
		return $Loc;
	} else {
		return false;
	}

}

function tbs_Html_MergeItems(&$Txt,&$Loc,&$SelValue,&$SelArray,$NewEnd) {
// Merge items of a list, or radio or check buttons.
// At this point, the Locator is already merged with $SelValue.

	if ($Loc->PrmLst['selected']===true) {
		$IsList = true;
		$MainTag = 'SELECT';
		$ItemTag = 'OPTION';
		$ItemPrm = 'selected';
	} else {
		$IsList = false;
		$MainTag = 'FORM';
		$ItemTag = 'INPUT';
		$ItemPrm = 'checked';
	}
	if (isset($Loc->PrmLst['selbounds'])) $MainTag = $Loc->PrmLst['selbounds'];
	$ItemPrmZ = ' '.$ItemPrm.'="'.$ItemPrm.'"';

	$TagO = tbs_Html_FindTag($Txt,$MainTag,true,$Loc->PosBeg-1,false,0,false);

	if ($TagO!==false) {

		$TagC = tbs_Html_FindTag($Txt,$MainTag,false,$Loc->PosBeg,true,0,false);
		if ($TagC!==false) {

			// We get the main block without the main tags
			$MainSrc = substr($Txt,$TagO->PosEnd+1,$TagC->PosBeg - $TagO->PosEnd -1);

			if ($IsList) {
				$Item0Beg = $Loc->PosBeg - ($TagO->PosEnd+1);
				$Item0Src = '';
			} else {
				// we delete the merged value
				$MainSrc = substr_replace($MainSrc,'',$Loc->PosBeg - ($TagO->PosEnd+1), strlen($SelValue));
			}

			// Now, we going to scan all of the item tags
			$Pos = 0;
			$SelNbr = 0;
			$Item0Ok = false;
			while ($Pos!==false) {
				$ItemLoc = tbs_Html_FindTag($MainSrc,$ItemTag,true,$Pos,true,0,true);
				if ($ItemLoc===false) {
					$Pos = false;
				} else {

					// we get the value of the item
					$ItemValue = false;
					$Select = true;

					if ($IsList) {
						// Look for the end of the item
						$OptCPos = strpos($MainSrc,'<',$ItemLoc->PosEnd+1);
						if ($OptCPos===false) $OptCPos = strlen($MainSrc);
						if (($Item0Ok===false) and ($ItemLoc->PosBeg<$Item0Beg) and ($Item0Beg<=$OptCPos)) {
							// If it's the original item, we save it and delete it.
							if (($OptCPos+1<strlen($MainSrc)) and ($MainSrc[$OptCPos+1]==='/')) {
								$OptCPos = strpos($MainSrc,'>',$OptCPos);
								if ($OptCPos===false) {
									$OptCPos = strlen($MainSrc);
								} else {
									$OptCPos++;
								}
							}
							$Item0Src = substr($MainSrc,$ItemLoc->PosBeg,$OptCPos-$ItemLoc->PosBeg);
							$MainSrc = substr_replace($MainSrc,'',$ItemLoc->PosBeg,strlen($Item0Src));
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) tbs_Html_InsertAttribute($Item0Src,$ItemPrmZ,$ItemLoc->PosEnd-$ItemLoc->PosBeg);
							$OptCPos = min($ItemLoc->PosBeg,strlen($MainSrc)-1);
							$Select = false;
							$Item0Ok = true;
						} else {
							if (isset($ItemLoc->PrmLst['value'])) {
								$ItemValue = $ItemLoc->PrmLst['value'];
							} else { // The value of the option is its caption.
								$ItemValue = substr($MainSrc,$ItemLoc->PosEnd+1,$OptCPos - $ItemLoc->PosEnd - 1);
								$ItemValue = str_replace(chr(9),' ',$ItemValue);
								$ItemValue = str_replace(chr(10),' ',$ItemValue);
								$ItemValue = str_replace(chr(13),' ',$ItemValue);
								$ItemValue = trim($ItemValue);
							}
						}
						$Pos = $OptCPos;
					} else {
						if ((isset($ItemLoc->PrmLst['name'])) and (isset($ItemLoc->PrmLst['value']))) {
							if (strcasecmp($Loc->PrmLst['selected'],$ItemLoc->PrmLst['name'])==0) {
								$ItemValue = $ItemLoc->PrmLst['value'];
							}
						}
						$Pos = $ItemLoc->PosEnd;
					}

					if ($Select) {
						// we look if we select the item
						$Select = false;
						if ($SelArray===false) {
							if (strcasecmp($ItemValue,$SelValue)==0) {
								if ($SelNbr==0) $Select = true;
							}
						} else {
							if (array_search($ItemValue,$SelArray,false)!==false) $Select = true;
						}
						// Select the item
						if ($Select) {
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) {
								tbs_Html_InsertAttribute($MainSrc,$ItemPrmZ,$ItemLoc->PosEnd);
								$Pos = $Pos + strlen($ItemPrmZ);
								if ($IsList and ($ItemLoc->PosBeg<$Item0Beg)) $Item0Beg = $Item0Beg + strlen($ItemPrmZ);
							}
							$SelNbr++;
						}
					}

				} //--> if ($ItemLoc===false) { ... } else {
			} //--> while ($Pos!==false) {

			if ($IsList) {
				// Add the original item if it's not found
				if (($SelArray===false) and ($SelNbr==0)) $MainSrc = $MainSrc.$Item0Src;
				$NewEnd = $TagO->PosEnd+1 + strlen($MainSrc);
			} else {
				$NewEnd = $Loc->PosBeg;
			}

			$Txt = substr_replace($Txt,$MainSrc,$TagO->PosEnd+1,$TagC->PosBeg-$TagO->PosEnd-1);

		} //--> if ($TagC!==false) {
	} //--> if ($TagO!==false) {

}

function tbs_Cache_IsValide($CacheFile,$TimeOut) {
// Return True if there is a existing valid cache for the given file id.
	if (file_exists($CacheFile)) {
		if (time()-filemtime($CacheFile)>$TimeOut) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function tbs_Cache_File($Dir,$CacheId,$Mask) {
// Return the cache file path for a given Id.
	if (strlen($Dir)>0) {
		if ($Dir[strlen($Dir)-1]<>'/') {
			$Dir .= '/';
		}
	}
	return $Dir.str_replace('*',$CacheId,$Mask);
}

function tbs_Cache_DeleteAll($Dir,$Mask) {

	if (strlen($Dir)==0) {
		$Dir = '.';
	}
	if ($Dir[strlen($Dir)-1]<>'/') {
		$Dir .= '/';
	}
	$DirObj = dir($Dir);
	$Nbr = 0;
	$PosL = strpos($Mask,'*');
	$PosR = strlen($Mask) - $PosL - 1;

	// Get the list of cache files
	$FileLst = array();
	while ($FileName = $DirObj->read()) {
		$FullPath = $Dir.$FileName;
		if (strtolower(filetype($FullPath))==='file') {
			if (strlen($FileName)>=strlen($Mask)) {
				if ((substr($FileName,0,$PosL)===substr($Mask,0,$PosL)) and (substr($FileName,-$PosR)===substr($Mask,-$PosR))) {
					$FileLst[] = $FullPath;
				}
			}
		}
	}
	// Delete all listed files
	foreach ($FileLst as $FullPath) {
		@unlink($FullPath);
		$Nbr++;
	}

	return $Nbr;

}

?>