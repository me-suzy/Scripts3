<?php
class parse {
 var $search = array();
 var $replace = array();
 var $wrapwidth = 75;
 var $smilies = array();
 var $hilight = "";
 var $showimages = 0;
 var $docensor = 0;
 var $censorwords = array();
 var $censorcover = '';
 var $imgsearch = "";
 var $imgreplace = "";
 var $censorsearch=array();
 var $censorreplace=array();
  
 var $done = array(); 
 var $cuturls = 0;
 
 // (php-) & code parse 
 var $usecode = 0;
 var $index = array();
 var $hash = "";
 var $tempsave = array();
 
   
 function parse($docensor=0,$wrapwidth=0,$getsmilies=0,$getbbcode=0,$showimages=0,$hilight="",$usecode=1,$cuturls=1) {
  if($getsmilies==1) $this->getsmilies();
  if($hilight) $this->hilight=urldecode($hilight);
  if($getbbcode==1) $this->getbbcode();
  if($docensor==1) {
   $this->docensor=1;
   global $censorwords, $censorcover;
   $this->censorwords=explode("\n",preg_replace("/\s*\n\s*/","\n",trim($censorwords)));
   $this->censorcover=$censorcover;
  }
  if($wrapwidth) $this->wrapwidth=$wrapwidth;
  if($showimages) $this->showimages=$showimages;
  $this->prepareimages();
  $this->cuturls=$cuturls;
    
  if($usecode==1) {
   $this->usecode=1;
   /*
   $this->tempsave['php'] = array();
   $this->tempsave['code'] = array();
   $this->index['php'] = -1;
   $this->index['code'] = -1;
   */
   $this->hash = substr(md5(uniqid(microtime())),0,6);
  }
 }

 function getsmilies() {
  global $db, $n;
  $i=0;
  $result=$db->query("SELECT smiliecode, smiliepath, smilietitle FROM bb".$n."_smilies ORDER BY smilieorder ASC");
  while($row=$db->fetch_array($result)) {
   $this->smilies[$i]=$row;
   $i++;
  }
  $db->free_result($result);
  $this->done['smilies']=1;
 }
 
 function getbbcode() {
  global $db, $n;
  if($this->hilight) {
   $hilightwords=preg_replace("/[\/:;'\"\(\)\[\]?!#{}%\-+\\\\]/s","",str_replace("/","\/",quotemeta($this->hilight)));
   $hilightwords=preg_replace("/\s{2,}/"," ",$hilightwords);
   $hilightwords=preg_split("/[\s]/", strtolower($hilightwords), -1, PREG_SPLIT_NO_EMPTY);
   while(list($key,$word)=each($hilightwords)) {
    if($word=="and" || $word=="or" || $word=="not") continue;
    
    $word = str_replace("*", "[0-9a-z]*", $word);
    $word = str_replace("_", "[0-9a-z]{1}", $word);
    
    if(!isset($hilightstring)) $hilightstring=$word;
    else $hilightstring.="|".$word;
   }
   if(isset($hilightstring)) {
    $this->search[]="/(^|\s|\]|>|\")($hilightstring)(([,\.]{1}[\s[\"<$]+)|\s|\[|\"|<|$)/i";
    //$this->search[]="/(^|\s|\]|>|\"|\.|,)($hilightstring)(,|\.|\s|\[|\"|<|$)/i";
    $this->replace[]="\\1<hilight>\\2</hilight>\\3"; 
   }
  }
   
  $this->search[]="/\[list=(['\"]?)([^\"']*)\\1](.*)\[\/list((=\\1[^\"']*\\1])|(\]))/esiU";
  $this->replace[]="\$this->formatlist('\\3', '\\2')"; 
  $this->search[]="/\[list](.*)\[\/list\]/esiU";	
  $this->replace[]="\$this->formatlist('\\1')"; 
  $this->search[]="/\[url=(['\"]?)([^\"']*)\\1](.*)\[\/url\]/esiU";
  $this->replace[]="\$this->formaturl('\\2','\\3')";
  $this->search[]="/\[url]([^\"]*)\[\/url\]/eiU";	
  $this->replace[]="\$this->formaturl('\\1')";
  $this->search[]="/javascript:/i";
  $this->replace[]="java script:";
  $this->search[]="/vbscript:/i";
  $this->replace[]="vb script:";
  $this->search[]="/about:/i";
  $this->replace[]="about :";
    
  $threeparams = "/\[%s=(['\"]?)([^\"']*),([^\"']*)\\1](.*)\[\/%s\]/siU";
  $twoparams = "/\[%s=(['\"]?)([^\"']*)\\1](.*)\[\/%s\]/siU";
  $oneparam = "/\[%s](.*)\[\/%s\]/siU"; 
 
  $result = $db->query("SELECT bbcodetag,bbcodereplacement,params,multiuse FROM bb".$n."_bbcodes");

  while($row = $db->fetch_array($result)) {
   if($row['params']==1) $search = sprintf($oneparam, $row['bbcodetag'], $row['bbcodetag']);
   if($row['params']==2) $search = sprintf($twoparams, $row['bbcodetag'], $row['bbcodetag']);
   if($row['params']==3) $search = sprintf($threeparams, $row['bbcodetag'], $row['bbcodetag']);
        	
   for($i=0;$i<$row['multiuse'];$i++) {
    $this->search[] = $search;
    $this->replace[] = $row['bbcodereplacement'];
   }
  }
  $this->done['bbcode']=1;
 }
 
 function prepareimages() {
  global $allowdynimg;
  
  if($allowdynimg==1) $this->imgsearch="/\[img]([^\"]*)\[\/img\]/siU";	
  else $this->imgsearch="/\[img]([^\"\?\&]*\.(gif|jpg|jpeg|bmp|png))\[\/img\]/siU";
  if($this->showimages==1) $this->imgreplace="<img src=\"\\1\" border=0>";
  else $this->imgreplace="<a href=\"\\1\" target=\"_blank\">\\1</a>";
 }
  
 function convertHTML($post,$x=true) {
  $post = str_replace("&lt;","&amp;lt;",$post);
  $post = str_replace("&gt;","&amp;gt;",$post);
  $post = str_replace("<","&lt;",$post);
  $post = str_replace(">","&gt;",$post);
  if($x) {
   $post = str_replace("{","&#123;",$post);
   $post = str_replace("}","&#125;",$post);
  }
  return $post;
 }
 
 function censor($post) {
  if(count($this->censorsearch)==0 || count($this->censorreplace)==0) {
   reset($this->censorwords);
   while(list($key,$censor)=each($this->censorwords)) {
    $censor=trim($censor);
    if(!$censor) continue;
   
    if(preg_match("/\{([^=]+)=([^=]*)\}/si",$censor,$exp)) {  
     $this->censorsearch[] = "/(^|\s|\]|>|\")(".$this->preg_quote($exp[1]).")(([,\.]{1}[\s[\"<$]+)|\s|\[|\"|<|$)/i";                		
     $this->censorreplace[] = "\\1".$exp[2]."\\3";
    }
    elseif(preg_match("/\{([^=]+)\}/si",$censor,$exp)) {
     $this->censorsearch[] = "/(^|\s|\]|>|\")(".$this->preg_quote($exp[1]).")(([,\.]{1}[\s[\"<$]+)|\s|\[|\"|<|$)/i";                		
     $this->censorreplace[] = "\\1".str_repeat($this->censorcover, strlen($exp[1]))."\\3";
    }
    elseif(preg_match("/([^=]+)=([^=]*)/si",$censor,$exp)) {
     $this->censorsearch[] = "/".$this->preg_quote($exp[1])."/i"; 	                		
     $this->censorreplace[] = $exp[2];
    }
    else {
     $this->censorsearch[] = "/".$this->preg_quote($censor)."/i"; 	                		
     $this->censorreplace[] = str_repeat($this->censorcover, strlen($censor));
    }
   }
  }
  if(count($this->censorsearch)>0 && count($this->censorreplace)>0) return preg_replace($this->censorsearch, $this->censorreplace, $post);
  else return $post;
 }
 
 function doparse($post,$allowsmilies,$allowhtml,$allowbbcode,$allowimages) {
  $post = $this->textwrap($post,$this->wrapwidth,1);
  if($this->usecode==1) {
   $this->tempsave['php'] = array();
   $this->tempsave['code'] = array();
   $this->index['php'] = -1;
   $this->index['code'] = -1;
   $post=preg_replace("/(\[(php|code)\])([^\\4\\1]*)(\[\/\\2\])/eiU","\$this->cachecode('\\3','\\2')",$post);
  }
  if($allowhtml==0) $post=$this->convertHTML($post,false);
  else {
   $post=preg_replace("/<([\/]?)script([^>]*)>/i","&lt;\\1script\\2&gt;",$post);
   $post=preg_replace("/(<table[^>]*>)([^\\3]*)(<\/table>)/eiU","\"\\1\".\$this->formatTableTR('\\2').\"\\3\"",$post);
  }
  
  $post = nl2br($post);
  if($allowsmilies==1) {
   if($this->done['smilies']!=1) $this->getsmilies(); 
   for($i=0;$i<count($this->smilies);$i++) $post=str_replace($this->smilies[$i]['smiliecode'],makeimgtag($this->smilies[$i]['smiliepath'],$this->smilies[$i]['smilietitle']),$post);
  }
  if($allowbbcode==1) {
   if($this->done['bbcode']!=1) $this->getbbcode(); 
   $post = preg_replace($this->search, $this->replace, $post);
  }
  else {
   $post=preg_replace("/javascript:/i","java script:",$post);
   $post=preg_replace("/vbscript:/i","vb script:",$post);
  }
  if($allowimages!=0) $post = preg_replace($this->imgsearch, $this->imgreplace, $post);
  if($this->usecode==1 && ($this->index['php']!=-1 || $this->index['code']!=-1)) $post=$this->replacecode($post);
  if($this->docensor==1) $post = $this->censor($post);
  
  return $post;
 }

 function textwrap($post, $wrapwidth=0, $inpost=0) {
  if($wrapwidth==0) $wrapwidth=$this->wrapwidth;
  if($post) {
   if($inpost==1) return preg_replace("/([^\n\r ?&\.\/<>\"\\-]{".$wrapwidth."})/i"," \\1\n",$post);
   else return preg_replace("/([^\n\r -]{".$wrapwidth."})/i"," \\1\n",$post);
  }
 }
 
 function cachecode($code,$mode) {
  $mode=strtolower($mode);
  $this->index[$mode]++;
  $this->tempsave[$mode][$this->index[$mode]]=$code;
  return "{".$this->hash."_".$mode."_".$this->index[$mode]."}";
 }
 
 function replacecode($post) {
  reset($this->tempsave);
  while(list($mode,$val)=each($this->tempsave)) {
   while(list($varnr,$code)=each($val)) $post=str_replace("{".$this->hash."_".$mode."_".$varnr."}",$this->codeformat($code,$mode),$post);
  }
  return $post;
 }
 
 function codeformat($code,$mode) {
  global $tpl;
  
  if($mode=="php") {
   $phptags=0;
   $code = str_replace("\\\"","\"",$code);

   if(!strpos($code,"<?") && substr($code,0,2)!="<?") {
    $phptags=1;
    $code="<? ".trim($code)." ?>";
   }
   ob_start();
   $oldlevel=error_reporting(0);
   highlight_string($code);
   error_reporting($oldlevel);
   $buffer = ob_get_contents();
   ob_end_clean();
   
   $buffer = str_replace("<code>", "", $buffer);
   $buffer = str_replace("</code>", "", $buffer);
   
   if($phptags==1) $buffer=preg_replace("/([^\\2]*)(&lt;\?&nbsp;)(.*)(&nbsp;.*\?&gt;)([^\\4]*)/si","\\1\\3\\5",$buffer);
   
   $buffer=str_replace("{","&#123;",$buffer);
   $buffer=str_replace("}","&#125;",$buffer);
   
   eval ("\$code = \"".$tpl->get("codephptag")."\";");
  }
  else {
   $code=str_replace("\\\"","\"",$code); 
   $code=htmlspecialchars($code); 
   $code=str_replace(" ","&nbsp;",$code); 
   $code=nl2br($code);
   
   $code=str_replace("{","&#123;",$code);
   $code=str_replace("}","&#125;",$code);
    
   eval ("\$code = \"".$tpl->get("codetag")."\";");
  }
       
  return $code;
 }
  
 function formaturl($url, $title="", $maxwidth=60, $width1=40, $width2=-15) {
  if(!trim($title)) $title=$url;
  if(!preg_match("/[a-z]:\/\//si", $url)) $url = "http://$url";
  if($this->cuturls==1 && strlen($title)>$maxwidth && !strstr(strtolower($title),"[img]") && !strstr(strtolower($title),"<img")) $title = substr($title,0,$width1)."...".substr($title,$width2);
  return "<a href=\"$url\" target=\"_blank\">".str_replace("\\\"", "\"", $title)."</a>";
 }
 
 function formatlist($list, $listtype="") {
  $listtype = ifelse(!trim($listtype), "",  " type=\"$listtype\"");
  $list = str_replace("\\\"","\"",$list);
  if ($listtype) return "<ol$listtype>".str_replace("[*]","<li>", $list)."</ol>";
  else return "<ul>".str_replace("[*]","<li>", $list)."</ul>";
 }
 
 function formatTableTR($text) {
  $text=preg_replace("/(<\/tr>)[^\\2]*(<tr[^>]*>)/iU","\\1\\2",stripslashes(trim($text)));
  $text=preg_replace("/(<tr[^>]*>)([^\\3]*)(<\/tr>)/eiU","\"\\1\".\$this->formatTableTD('\\2').\"\\3\"",$text);
  return $text;
 }
 
 function formatTableTD($text) {
  $text=preg_replace("/(<\/td>)[^\\2]*(<td[^>]*>)/iU","\\1\\2",stripslashes(trim($text)));
  $text=preg_replace("/(<\/th>)[^\\2]*(<th[^>]*>)/iU","\\1\\2",$text);
  return $text;
 }
 
 function preg_quote($text) {
  $text = preg_quote($text);
  $text = str_replace("/","\/",$text);
  return $text;
 }
}
?>