<?PHP 
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
class USPS { 
    var $server; 
    var $user; 
    var $pass; 
    var $service; 
    var $dest_zip; 
    var $orig_zip; 
    var $pounds; 
    var $ounces; 
    var $container = "None"; 
    var $size = "REGULAR"; 
    var $machinable; 
     
    function setServer($server) { 
        $this->server = $server; 
    } 

    function setUserName($user) { 
        $this->user = $user; 
    } 

    function setPass($pass) { 
        $this->pass = $pass; 
    } 

    function setService($service) { 
        /* Must be: Express, Priority, or Parcel */ 
        $this->service = $service; 
    } 
     
    function setDestZip($sending_zip) { 
        /* Must be 5 digit zip (No extension) */ 
        $this->dest_zip = $sending_zip; 
    } 

    function setOrigZip($orig_zip) { 
        $this->orig_zip = $orig_zip; 
    } 

    function setWeight($pounds, $ounces=0) { 
        /* Must weight less than 70 lbs. */ 
        $this->pounds = $pounds; 
        $this->ounces = $ounces; 
    } 

    function setContainer($cont) { 
        /* 
        Valid Containers 
                Package Name             Description 
        Express Mail 
                None                For someone using their own package 
                0-1093 Express Mail         Box, 12.25 x 15.5 x 
                0-1094 Express Mail         Tube, 36 x 6 
                EP13A Express Mail         Cardboard Envelope, 12.5 x 9.5 
                EP13C Express Mail         Tyvek Envelope, 12.5 x 15.5 
                EP13F Express Mail         Flat Rate Envelope, 12.5 x 9.5 

        Priority Mail 
                None                For someone using their own package 
                0-1095 Priority Mail        Box, 12.25 x 15.5 x 3 
                0-1096 Priority Mail         Video, 8.25 x 5.25 x 1.5 
                0-1097 Priority Mail         Box, 11.25 x 14 x 2.25 
                0-1098 Priority Mail         Tube, 6 x 38 
                EP14 Priority Mail         Tyvek Envelope, 12.5 x 15.5 
                EP14F Priority Mail         Flat Rate Envelope, 12.5 x 9.5 
         
        Parcel Post 
                None                For someone using their own package 
        */ 

        $this->container = $cont; 
    } 

    function setSize($size) { 
        /* Valid Sizes 
        Package Size                Description        Service(s) Available 
        Regular package length plus girth     (84 inches or less)    Parcel Post 
                                        Priority Mail 
                                        Express Mail 

        Large package length plus girth        (more than 84 inches but    Parcel Post 
                            not more than 108 inches)    Priority Mail 
                                        Express Mail 

        Oversize package length plus girth   (more than 108 but        Parcel Post 
                             not more than 130 inches) 

        */ 
        $this->size = $size; 
    } 

    function setMachinable($mach) { 
        /* Required for Parcel Post only, set to True or False */ 
        $this->machinable = $mach; 
    } 
     
    function getPrice() { 
        // may need to urlencode xml portion 
        $str = $this->server. "?API=Rate&XML=<RateRequest%20USERID=\""; 
        $str .= $this->user . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\"><Service>"; 
        $str .= $this->service . "</Service><ZipOrigination>" . $this->orig_zip . "</ZipOrigination>"; 
        $str .= "<ZipDestination>" . $this->dest_zip . "</ZipDestination>"; 
        $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>"; 
        $str .= "<Container>" . $this->container . "</Container><Size>" . $this->size . "</Size>"; 
        $str .= "<Machinable>" . $this->machinable . "</Machinable></Package></RateRequest>"; 

        $fp = fopen($str, "r");  
        while(!feof($fp)){  
            $result = fgets($fp, 500);  
            $body.=$result; 
        }  
        fclose($fp); 

        # note: using split for systems with non-perl regex (don't know how to do it in sys v regex) 
        if (!ereg("Error", $body)) { 
            $split = split("<Postage>", $body);  
            $body = split("</Postage>", $split[1]); 
            $price = $body[0]; 
            return($price); 
        } else { 
            return(false); 
        } 
    } 
} 

/* 

Copyright (c) 2000, Jason Costomiris 
All rights reserved. 

Props to shawnblue@radiotakeover.com for the original idea which I have cannibalized herein.  You go boy. 

Don't be scared, it's just a BSD-ish license. 

Redistribution and use in source and binary forms, with or without 
modification, are permitted provided that the following conditions 
are met: 

1. Redistributions of source code must retain the above copyright notice, 
   this list of conditions and the following disclaimer. 
2. Redistributions in binary form must reproduce the above copyright notice, 
   this list of conditions and the following disclaimer in the 
   documentation and/or other materials provided with the distribution. 
3. All advertising materials mentioning features or use of this software 
   must display the following acknowledgement: 
   This product includes software developed by Jason Costomiris. 
4. The name of the author may not be used to endorse or promote products 
   derived from this software without specific prior written permission. 

THIS SOFTWARE IS PROVIDED BY THE AUTHOR COPYRIGHT HOLDERS AND CONTRIBUTORS 
``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED 
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY 
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND 
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF 
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 

*/ 


  class Ups { 

    function upsProduct($prod){ 
     /* 

     1DM == Next Day Air Early AM 
     1DA == Next Day Air 
     1DP == Next Day Air Saver 
     2DM == 2nd Day Air Early AM 
     2DA == 2nd Day Air 
     3DS == 3 Day Select 
     GND == Ground 
     STD == Canada Standard 
     XPR == Worldwide Express 
     XDM == Worldwide Express Plus 
     XPD == Worldwide Expedited 
       
    */ 
      $this->upsProductCode = $prod; 
    } 
     
    function origin($postal, $country){ 
      $this->originPostalCode = $postal; 
      $this->originCountryCode = $country; 
    } 

    function dest($postal, $country){ 
      $this->destPostalCode = $postal; 
      $this->destCountryCode = $country; 
    } 

    function rate($foo){ 
      switch($foo){ 
        case "RDP": 
          $this->rateCode = "Regular+Daily+Pickup"; 
          break; 
        case "OCA": 
          $this->rateCode = "On+Call+Air"; 
          break; 
        case "OTP": 
          $this->rateCode = "One+Time+Pickup"; 
          break; 
        case "LC": 
          $this->rateCode = "Letter+Center"; 
          break; 
        case "CC": 
          $this->rateCode = "Customer+Counter"; 
          break; 
      } 
    } 

    function container($foo){ 
          switch($foo){ 
        case "CP":            // Customer Packaging 
          $this->containerCode = "00"; 
          break; 
        case "ULE":        // UPS Letter Envelope 
          $this->containerCode = "01";         
          break; 
        case "UT":            // UPS Tube 
          $this->containerCode = "03"; 
          break; 
        case "UEB":            // UPS Express Box 
          $this->containerCode = "21"; 
          break; 
        case "UW25":        // UPS Worldwide 25 kilo 
          $this->containerCode = "24"; 
          break; 
        case "UW10":        // UPS Worldwide 10 kilo 
          $this->containerCode = "25"; 
          break; 
      } 
    } 
     
    function weight($foo){ 
      $this->packageWeight = $foo; 
    } 

    function rescom($foo){ 
          switch($foo){ 
        case "RES":            // Residential Address 
          $this->resComCode = "1"; 
          break; 
        case "COM":            // Commercial Address 
          $this->resComCode = "0"; 
          break; 
          } 
    } 

    function getQuote(){ 
          $upsAction = "3"; // You want 3.  Don't change unless you are sure. 
      $url = join("&",  
               array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes", 
                     "10_action=$upsAction", 
                     "13_product=$this->upsProductCode", 
                     "14_origCountry=$this->originCountryCode", 
                     "15_origPostal=$this->originPostalCode", 
                     "19_destPostal=$this->destPostalCode", 
                     "22_destCountry=$this->destCountryCode", 
                     "23_weight=$this->packageWeight", 
                     "47_rateChart=$this->rateCode", 
                     "48_container=$this->containerCode", 
                     "49_residential=$this->resComCode" 
           ) 
                ); 
      $fp = fopen($url, "r"); 
      while(!feof($fp)){ 
        $result = fgets($fp, 500); 
        $result = explode("%", $result); 
        $errcode = substr($result[0], -1); 
        switch($errcode){ 
          case 3: 
            $returnval = $result[8]; 
                break; 
          case 4: 
            $returnval = $result[8]; 
            break; 
          case 5: 
            $returnval = $result[1]; 
            break; 
          case 6: 
            $returnval = $result[1]; 
            break; 
        } 
      } 
      fclose($fp); 
          if(!$returnval) { $returnval = "error"; } 
      return $returnval; 
    } 
  } 

?>

