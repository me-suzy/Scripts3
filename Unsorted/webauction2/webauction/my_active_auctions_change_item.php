<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

        include "includes/messages.inc.php";
        include "includes/config.inc.php";
        include "includes/countries.inc.php";
        include "includes/relations.inc";
        include "includes/auction_types.inc.php";
        include "includes/durations.inc.php";
        include "includes/payments.inc.php";
        include "includes/datacheck.inc.php";



        if ($id && $password && $nick)
        {
                $sql="SELECT * FROM ".$dbfix."_auctions WHERE id=\"". AddSlashes($id)."\" AND current_bid='0.0000'";

                $res=mysql_query ($sql);
                if ($res)
                {
                        if (mysql_num_rows($res)>0)
                        {
                                $arr=mysql_fetch_array ($res);
                                if ($TPL_password==$arr[password])
                                {
                                        $id=$arr[id];
                                        $title=$arr[title];
                                        $description=$arr[description];
                                        $pict_url=$arr[pict_url];
                                        $auction_type=$arr[auction_type];
                                        $quantity=$arr[quantity];
                                   //$minimum_bid=$arr[minimum_bid];
                                        $minimum_bid = substr($arr[minimum_bid],0,2);
                                        $reserve_price = substr($arr[reserve_price],0,2);
                                  //$reserve_price=$arr[reserve_price];
                                        $location_zip=$arr[location_zip];
                                        $category=$arr[category];
                                        $payment=$arr[payment];
                                        $shipping=$arr[shipping];
                                        $international=$arr[international];

if ( $shipping == '1' )
   {
                $TPL_shipping_value = $MSG_038;
   }
   else
   {
                $TPL_shipping_value = $MSG_032;
   }

if ( $international )
   {
                $TPL_international_value = ", $MSG_033";
   }
   else
   {
                $TPL_international_value = ", $MSG_043";
   }



           /*----------------------------------------------------------*/

                                        $T=        "<SELECT NAME=\"atype\">\n";
                        reset($auction_types); while(list($key,$val)=each($auction_types)){
                                $T.=
                                        "        <OPTION VALUE=\"".
                                        $key.
                                        "\" ".
                                        (($key==$atype)?"SELECTED":"")
                                        .">".$val."</OPTION>\n";
                        }
                        $T.="</SELECT>\n";
                        $TPL_auction_type = $T;

                             /*----------------------------------------------------------*/

            $T=        "<SELECT NAME=\"duration\">\n";
                        reset($durations); while(list($key,$val)=each($durations)){
                                $T.=
                                        "        <OPTION VALUE=\"".
                                        $key.
                                        "\" ".
                                        (($key==$duration)?"SELECTED":"")
                                        .">".$val."</OPTION>\n";
                        }
                        $T.="</SELECT>\n";
                        $TPL_durations_list = $T;



         /*----------------------------------------------------------*/
                        $T=        "<SELECT NAME=\"menge\">\n";
                        reset($relations); while(list($key,$val)=each($relations)){
                                $T.=
                                        "        <OPTION VALUE=\"".
                                        $key.
                                        "\" ".
                                        (($key==$relation)?"SELECTED":"")
                                        .">".$val."</OPTION>\n";
                        }
                        $T.="</SELECT>\n";
                        $TPL_relations_list = $T;

          /*----------------------------------------------------------*/

           $T=        "<SELECT NAME=\"country\">\n";
                        reset($countries); while(list($key,$val)=each($countries)){
                                $T.=
                                        "        <OPTION VALUE=\"".
                                        $key.
                                        "\" ".
                                        (($key==$country)?"SELECTED":"")
                                        .">".$val."</OPTION>\n";
                        }
                        $T.="</SELECT>\n";
                        $TPL_countries_list = $T;

          /*----------------------------------------------------------*/

                  $T=        "";
                        reset($payments); while(list($key,$val)=each($payments)){
                                $T.=
                                        "<INPUT TYPE=CHECKBOX NAME=\"payment".$key."\" ".
                                        ( (!empty(${"payment".$key}))?"CHECKED":"")
                                        ."> $std_font".$val."</FONT><BR>";
                        }
                        $TPL_payments_list = $T;

          /*----------------------------------------------------------*/

                                        include "header.php";
                                        include "header_change_auctions_php3.html";
                                        include "templates/change_auctions_php32.html";
                                        include "footer.php";

                                }
                                else
                                {
                                        $TPL_err=1;
                                        $TPL_errmsg=$ERR_101;
                                }
                        }
                        else
                        {
                                $TPL_err=1;
                                $TPL_errmsg=$ERR_100;
                        }
                }
                else
                {
                        $TPL_err=1;
                        $TPL_errmsg=$ERR_001;
                }
        }
        else
        {
                $TPL_err=1;
                $TPL_errmsg=$ERR_112;
        }


?>
