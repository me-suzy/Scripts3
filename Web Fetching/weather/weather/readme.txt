/////////////////////////////////////////////////////
//                                                 //
//                   EASYWEATHER                   //
//                 Version 1.15                    //
//                                                 //
//                 Copyright 2004                  //
//                  I Ketut Sukandia               //
//          http://www.balidwipa.com/   //        
//            http://www.nusa-penida.com //
//              All Rights Reserved                //
//                                                 //
//          In using this script you               //
//           agree to the following:               //
//                                                 //
//      This script may be used and modified       //
//              freely as long as this             //
//             copyright remains intact.           //
//                                                 //
//     You may not distibute this script, or       //
//           any modifications of it.              //
//                                                 //
//   A link must be provided on the website that   //
//             uses the script to:                 //
//          http://www.balidwipa.com/              //
//                                                 //
//      Any breaches of these conditions           //
//        will result in legal action.             //
//                                                 //
//      This script is distributed with            //
//        no warranty, free of charge.             //
//                                                 //
/////////////////////////////////////////////////////

Easy Weather is a PHP script that allows you to add any current city weather information on your site. It is very easy to install. You can just cut and paste the code to any of your PHP page and the code should work out of the box. No mysql database is required, but you must find out your ACID code at www.msnbc.com, and replace our acid code to match your city weather information. The information can be found on the source code.

EasyWeather will work with  PHP Version 4.0.2 or greater with windows or linux platform. 
It is best to make sure you have the latest version installed to ensure the script works as intended. 
Please make a contribution you may have and send in to info@nusa-penida.com, i will include for the next issue!

   From this version you will have :

   1. From your favourite html editor like Dreamweaver or frontpage you can easily add or modify your city weather :
      
      - add any city location name
      - add any city location code 
        ( can be found from http://www.msnbc.com/news/wea_front.asp?ta=y&tab=BW&tp=&ctry=&cp1=1 )
      - edit, Delete the data 
      - set one city as a default

   2. Once you set a city as a default you will have your city weather and the user can also check the weather of other city by dropdown menu (as long as you include it)
   
   3. No database is required to run this script. 


   ************
   INSTALLATION
   ************

   1. Unzip the weather.zip to your local hardrive
   2. change the accid code to your city ID code for index.php, metar.php and weather.php ( can be found from http://www.msnbc.com/news/wea_front.asp?ta=y&tab=BW&tp=&ctry=&cp1=1 )
      to match your city current weather as default in index.php, weather.php, metar.php.
      or you can just cut & paste the code to your php page!
      metar.php is for single city weather information and weather.php with the 5days weather forecast.
	  You can also include above file on your php documents!
   3. Put all the files to your server, it's nice to install on your /weather/ forlder and please leave the icon on its own forlder given. 
      i.e /weather/i/

   ************
   Tested
   ************

   Demo for This version has been tested running on apache server with php (windows & linux). To see how its running you can visiting our site
   http://www.nusa-penida.com/weather/index.php
   http://www.nusa-penida.com/weather/metar.php
   http://www.nusa-penida.com/weather/weather.php


   Enjoy !!
   www.balidwipa.com 
   www.nusa-penida.com

Upgrade:
* just replace your previous installation, but please change design first to math to your site.

ChangeLog:
20 Octobel 2004 (ver. 1.15)
make a little change to the php code, due to the code did not work and load for too long time and time out caused

10 October 2002 (Ver. 1.12)
- Add weather icon and no longer images link to msnbc.com weather icon

02 August 2002 (Ver. 1.11)
Fixed bug:
- Since the msnbc.com change their weather source  from accuweather.com to weather.com, my easy weather did not work, fixed some of code to get it working.
- Some picture of weather forecest in some of city may be broken or the temparature indicate -18 (minus), it's mean the information source is also unavailable from the resource, it's not a bug.
- this will change for the next issue
- MSNBC.com also change the accid code like : IDXX0005, etc..from WRRR (for Bali Code)
  you can find your city code at http://www.msnbc.com/news/wea_front.asp?tab=BW&ta=y&inst=n&action=&pos=&cty=
  (just see their html source code) and change the accid code if your already install our easy weather 

12 October 2001 (ver. 1.10)
fix some bugs, some temparature did not run corectly.

01 June 2001 (Ver. 1.0)
Initial release. 

end of install instruction!

=======for Indonesian weather forecast, the following code can insert into your index.php eather page====
please make a cosmetic change to fit to your site! (assume that your weather page is in index.php and under weather folder!, if not, please insert your weather file name in front of the question mark 
(ex: <a href="/weather/weather.php?accid=IDXX0005"><b>Bali</b></a> 

<!-- start copy and paste here-->
 <table cellspacing=0 cellpadding=0 width=366 
                                border=0>
        <tbody> 
        <tr> 
          <td valign=top width=183><a class=a-12-b 
                                href="/weather/?accid=IDXX0001">Amahai</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0002">Ambon</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0080">Ampenan/Selaparang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0003">Amurang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0004">Baa</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0005"><b>Bali</b></a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0006">Balikpapan</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0007">Bandung</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0008">Bangil</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0009">Banjarmasin</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0010">Banyuwangi</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0011">Batang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0012">Bengkulu</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0085">Biak/Mokmer</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0082">Bima</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0013">Binjai</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0014">Bogor</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0015">Cibatu</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0016">Cikampek</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0072">Cilacap</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0017">Cirebon</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0068">Curug/Budiarto</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0018">Delitua</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0019"><b>Denpasar</b></a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0086">Fak-Fak/Torea</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0077">Gorontalo/Jalaluddin</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0020">Gresik</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0021">Indramayu</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0022">Jakarta</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0069">Jakarta/Soekarno-Hatta</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0089">Jayapura/Sentani</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0023">Jepara</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0075">Kalianget Madura 
            Island</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0024">Karawang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0025">Kayuagung</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0026">Klaten</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0090">Kokonao/Timuka</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0067">Kotabaru</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0027">Kretek</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0028">Kupang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0029">Lais</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0030">Langsa</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0031">Manado</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0032">Mataram</a><br>
          </td>
          <td valign=top width=183><a class=a-12-b 
                                href="/weather/?accid=IDXX0083">Maumere/Wai Oti</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0033">Medan</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0065">Muaratewe/Beringin</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0087">Nabire</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0034">Natal</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0035">Padang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0066">Palangkaraya/Panarung</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0036">Palembang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0078">Palu/Mutiara</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0037">Pamekasan</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0062">Pangkalpinang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0038">Pasuruan</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0039">Pedjongkoran</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0040">Pekalongan</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0041">Piru</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0064">Pontianak/Supadio</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0042">Probolinggo</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0043">Purwakarta</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0044">Rangkasbitung</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0061">Rengat/Japura</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0045">Samarinda</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0073">Sangkapura Bawean 
            Island</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0046">Semarang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0047">Serang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0059">Sibolga/Pinangsori</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0048">Singaraja</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0049">Situbondo</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0050">Subang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0081">Sumbawa Besar</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0051">Sungaigerong</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0052">Surabaya</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0074">Surabaya/Perak</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0053">Tanjungbalai</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0054">Tanjungkarang-Telukbetung</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0063">Tanjungpandan/Buluh 
            Tumbang</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0055">Tanjungpinang</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0060">Tarempa</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0056">Tebingtinggi</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0057">Tegal</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0079">Ujung Pandang/Hasanuddin</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0084">Waingapu/Mau Hau</a><br>
            <a class=a-12-b 
                                href="/weather/?accid=IDXX0088">Wamena/Wamena</a><br>
            <a 
                                class=a-12-b 
                                href="/weather/?accid=IDXX0058">Yogyakarta</a></td>
        </tr>
        </tbody> 
      </table>
<!-- end copy and paste here-->