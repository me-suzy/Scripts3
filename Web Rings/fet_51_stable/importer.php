<?php

$action=$_REQUEST['action'];

if($action=='add'){
    $selected=$_REQUEST['toadd'];
    if(!is_array($selected) || count($selected)<1){
        $action='check';
    } else {
        require 'settings.php';
        require 'includes/main.inc.php';
        require 'includes/services.inc.php';
        require 'includes/settings_uploader.inc.php';
        $settings=new Main_settings;
        $function=new Service_functions;
        $trader=new Traders_data;
        $log=new Logger;
        if(!$function->read_traders()){
            echo "<center><b><font size=2 face=arial color=red>ERROR: Current trades database files are not readable.</font></b>";
            exit;
        }
        $prevfet=$_REQUEST['prevfet'];
        $prev_data1=@file($prevfet.'/secured/fetdata/fetmembers1');
        $prev_data2=@file($prevfet.'/secured/fetdata/fetmembers2');
        if(!is_array($prev_data1)){
            $alert='<center><br><br><font size=2 face=arial color=red>Previous database file '.$prevfet.'/secured/fetdata/fetmembers1  not found, not readable or empty</font><br><br>';
            exit;
        }
        if(!is_array($prev_data2)){
            $alert='<center><br><br><font size=2 face=arial color=red>Previous database file '.$prevfet.'/secured/fetdata/fetmembers2  not found, not readable or empty</font><br><br>';
            exit;
        }

        foreach($prev_data1 as $rpl){
              if(strlen($rpl)<10) continue;
              list($dom,$url,$ratio,$ttype,$mint,$pr_tr,$pr_ds,$pr_an)=explode('|',trim($rpl));
              if(!$dom || $dom=='exout' || $dom=='filtered' || $dom=='noref' || $dom=='notrade' || $dom=='notrades' || $dom=='nocookie' || $dom=='error' || $dom=='gallery') continue;
              $prev_url["$dom"]=$url;
              $prev_ratio["$dom"]=$ratio;
              $prev_ttype["$dom"]=$ttype;
              $prev_mint["$dom"]=$mint;
              $prev_pr_tr["$dom"]=$pr_tr;
              $prev_pr_ds["$dom"]=$pr_ds;
              $prev_pr_an["$dom"]=$pr_an;
        }
        foreach($prev_data2 as $rpl){
              if(strlen($rpl)<10) continue;
              list($dom,$sname,$email,$nick,$icq,$top,$da,$ipa)=explode('|',trim($rpl));
              if(!$dom) continue;
              $prev_sname["$dom"]=$sname;
              $prev_email["$dom"]=$email;
              $prev_nick["$dom"]=$nick;
              $prev_icq["$dom"]=$icq;
              $prev_top["$dom"]=$top;
        }

        foreach($selected as $dom){
            if($trader->url["$dom"] || !$prev_url["$dom"]) continue;

            $trader->url["$dom"]=$prev_url["$dom"];
            $trader->set_ratio["$dom"]=$prev_ratio["$dom"];
            switch($prev_ttype["$dom"]){
                case 'b': $trader->trade_type["$dom"]=1; break;
                case 'p': $trader->trade_type["$dom"]=1; break;
                case 'c': $trader->trade_type["$dom"]=4; break;
                case 'r': $trader->trade_type["$dom"]=3; break;
                case 't': $trader->trade_type["$dom"]=5; break;
                default: $trader->trade_type["$dom"]=0; break;
            }
            $trader->proxy_an["$dom"]=1;
            $trader->proxy_ds["$dom"]=1;
            $trader->proxy_tr["$dom"]=1;
            $trader->badref_in["$dom"]=1;
            $trader->main_pages=array();
            $trader->click_rule["$dom"]='';
            $trader->content_skim["$dom"]=50;
            $trader->mintrade["$dom"]=$prev_mint["$dom"];
            $trader->nocookie_pr["$dom"]=100;
            $trader->badref_click["$dom"]=1;
            $trader->badref_cl_pr["$dom"]=100;
            $trader->proxy_cl_pr["$dom"]=100;
            $trader->nojs_rule["$dom"]=1;
            $trader->site_name["$dom"]=$prev_sname["$dom"];
            $trader->e_mail["$dom"]=$prev_email["$dom"];
            $trader->nick["$dom"]=$prev_icq["$dom"];
            $trader->icq["$dom"]=$prev_icq["$dom"];
            if($prev_top["$dom"]=='y') $trader->toplist_allowed["$dom"]=1; else $trader->toplist_allowed["$dom"]=0;
            $trader->time_added["$dom"]=time();
        }

        if($function->write_traders()){
            echo "<center><b><font size=2 face=arial>All done.</font></b></center>";
        } else {
            echo "<center><b><font size=2 face=arial color=red>Error saving data in current database.</font></b></center>";
        }
        exit;

    }
}

if($action=='check'){
    $prevfet=$_REQUEST['prevfet'];
    $prev_data1=file($prevfet.'/secured/fetdata/fetmembers1');
    $prev_data2=file($prevfet.'/secured/fetdata/fetmembers2');

    if(!is_array($prev_data1)){
        $alert='<center><br><br><font size=2 face=arial color=red>Previous database file '.$prevfet.'/secured/fetdata/fetmembers1  not found, not readable or empty</font><br><br>';
        $action=false;;
    } elseif(!is_array($prev_data2)){
        $alert='<center><br><br><font size=2 face=arial color=red>Previous database file '.$prevfet.'/secured/fetdata/fetmembers2  not found, not readable or empty</font><br><br>';
        $action=false;;
    } else {
          foreach($prev_data1 as $rpl){
              if(strlen($rpl)<10) continue;
              list($dom,$url,$ratio,$ttype,$mint,$pr_tr,$pr_ds,$pr_an)=explode('|',trim($rpl));
              if(!$dom || $dom=='exout' || $dom=='filtered' || $dom=='noref' || $dom=='notrade' || $dom=='notrades' || $dom=='nocookie' || $dom=='error' || $dom=='gallery') continue;
              $prev_url["$dom"]=$url;
              $prev_ratio["$dom"]=$ratio;
              $prev_ttype["$dom"]=$ttype;
              $prev_mint["$dom"]=$mint;
              $prev_pr_tr["$dom"]=$pr_tr;
              $prev_pr_ds["$dom"]=$pr_ds;
              $prev_pr_an["$dom"]=$pr_an;
          }
          foreach($prev_data2 as $rpl){
              if(strlen($rpl)<10) continue;
              list($dom,$sname,$email,$nick,$icq,$top,$da,$ipa)=explode('|',trim($rpl));
              if(!$dom) continue;
              $prev_sname["$dom"]=$sname;
              $prev_email["$dom"]=$email;
              $prev_nick["$dom"]=$nick;
              $prev_icq["$dom"]=$icq;
              $prev_top["$dom"]=$top;
          }
          if(is_array($prev_url)){

              require 'settings.php';
              require 'includes/main.inc.php';
              require 'includes/services.inc.php';
              require 'includes/settings_uploader.inc.php';
              $settings=new Main_settings;
              $function=new Service_functions;
              $trader=new Traders_data;
              $log=new Logger;
              if($function->read_traders()){
                  echo "
                    <head>
                    <style>
                    <!--
                    td {font-family: arial; font-size:11 px;}
                    -->
                    </style>
                    </head>
                    <center>
                        <font size=2 face=arial><b>Choose trades to import.</b></font><br><br>
                        <form method=post>
                        <input type=hidden name=action value='add'>
                        <table width=800 border=1 cellpadding=2 cellspacing=0>
                        <tr align=center>
                        <td width=2%>-</td>
                        <td width=30%>Domain, URL, Site Name</td>
                        <td width=15%>Webmaster</td>
                        <td width=10%>Trade Type</td>
                        <td width=10%>Ratio</td>
                        <td width=10%>Min trade</td>
                        <td width=10%>Show In toplist</td>
                        </tr>

                  ";
                  $newtr=0;
                  foreach($prev_url as $dom=>$url){
                      if($trader->url["$dom"]) continue;
                      $newtr++;
                      switch($prev_ttype["$dom"]){
                          case 'b': $ttp='Normal'; break;
                          case 'p': $ttp='Prod'; break;
                          case 'c': $ttp='Capped'; break;
                          case 'r': $ttp='Ratio'; break;
                          case 't': $ttp='Boost'; break;
                          default: $ttp='Stopped'; break;
                      }
                      if($prev_top["$dom"]=='y') $stp='Yes'; else $stp='No';
                      echo "<tr>
                            <td><input type=checkbox name='toadd[]' value='".$dom."'></td>
                            <td align=center><b>".$dom."</b><br>".$url."<br>".$prev_sname["$dom"]."</td>
                            <td align=center>".$prev_nick["$dom"]."<br>".$prev_icq["$dom"]."<br>".$prev_email["$dom"]."</td>
                            <td align=center>".$ttp."</td>
                            <td align=center>".$prev_ratio["$dom"]."</td>
                            <td align=center>".$prev_mint["$dom"]."</td>
                            <td align=center>".$stp."</td>
                            </tr>
                      ";
                  }
                  if($newtr>0){
                    echo "</table><br>
                            <input type=hidden name=prevfet value='".$prevfet."'>
                           <input type=submit value='Import Trades'>
                           </form>
                    ";
                  } else {
                      echo "<br><br><font size=2 face=arial><b>You already have all trades from the previous installation in the new database.</b></font>";
                  }
                  exit;
              } else {
                $alert='<br><br><font size=2 face=arial color=red>Current database files are not found or not readable</font><br><br>';
                $action=false;
              }
          } else {
              $alert='<br><br><font size=2 face=arial color=red>Previous database files are empty</font><br><br>';
              $action=false;
          }
    }
}

if(!$action){
    echo "<center>
        <font size=2 face=arial>FET 3.x, 4.x -> FET 5.x Trades Importer.<br><br><br><br>Please input correct path below:</font><br>
        <form method=post>
        ".$alert."
        <table width=700 border=0>
        <tr>
        <td><font size=2 face=arial>Path to FET installation below 5.x</font></td>
        <td><input type=text name=prevfet size=40 value='".$prevfet."'></td>
        </tr>
        <tr>
        <td colspan=2 align=center><input type=submit value=Done></td>
        </tr>
        </table>
        <input type=hidden name=action value=check>
        </form>
    ";
    exit;
}

?>
