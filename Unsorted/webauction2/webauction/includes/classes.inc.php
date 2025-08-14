<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
class datum{

		var $de_date;			//datumstring in deutschen Format
		var $us_date;			//datumsstring in US-Format
		var $db_date;			//datumsstring im format für die phpauktion-datenbank
		var $day;				//der Tag (%%)
		var $month;				//der Monat (%%)
		var $year;				//das JAHR (%%%%)
		var $error; 			//fehler

		function setRawdate ($datum){
			if (ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})",$datum,$regs)) {  		// USA Date
			$DATE = explode("/",$datum);
			$birth_day 	= $DATE[1];
			$birth_month 	= $DATE[0];
			$birth_year 	= $DATE[2];
			$date_decoded = true;
			}
		
			if (ereg("([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{2,4})",$datum,$regs)) { 			//German Date
				$DATE = explode(".",$datum);
				$birth_day 	= $DATE[0];
				$birth_month 	= $DATE[1];
				$birth_year 	= $DATE[2];
				$date_decoded = true;
				}

			if ($date_decoded) {
				if ($birth_day >31 or $birth_day < 1) $date_decoded = false; //primitive check for valid day
				if ($birth_month >12 or $birth_month < 1) $date_decoded = false;
				if (($birth_year >99 and $birth_year <1850) or $birth_year < 0) $date_decoded = false;
				}
			if ($date_decoded) {
				$this->day = sprintf ("%02d",$birth_day);
				$this->month = sprintf ("%02d",$birth_month);
				if ($birth_year <100) $birth_year = ($birth_year + 1900);
				$this->year = sprintf ("%04d",$birth_year);
			}else{
				$this->error = "## falsches RawDate Format ##";
			}
			$this->db_date = $this->year.$this->month.$this->day;
			$this->de_date = $this->day.".".$this->month.".".$this->year;
			$this->us_date = $this->month."/".$this->day."/".$this->year;
		if ($this->error) return (1);
		}
		
		
		function setDBdate($datum){
		if (ereg("([0-9]{4})([0-9]{2})([0-9]{2})",$datum,$regs)){
					$tempdatum = substr($datum,6,2).".".			//tag
							 substr($datum,4,2).".".		//monat
							 substr($datum,0,4);			//Jahr
					$this->setRawdate($tempdatum);
			}else{
			$this->error = "## falsches db_date Format ##";
			}
		if ($this->error) return (1);
		}
		function checkage(){
		$altgenug = 0;
		$jahr = date ("Y");
		$monat = date ("n");
		$tag = date ("j");
		if (($jahr-$this->year)>18)$altgenug=1;
		if ((($jahr-$this->year)==18)and(($monat-$this->month)>0))$altgenug=1;
		if ((($jahr-$this->year)==18)and(($monat-$this->month)==0)and($tag-$this->day>=0)) $altgenug=1;
		if ($altgenug == 0)  $this->error = "zu jung";
		if (($jahr-$this->year)>120){$altgenug=0;$this->error="zu alt";}
		return ($altgenug);
		}
}
/*
$datum = new datum;
$datum->setRawdate ("02.05.1971");
//$datum->setDBdate ("19710216");
echo "der db-datum string lautet: ".$datum->db_date."<br>";
echo "der de-datum string lautet: ".$datum->de_date."<br>";
echo "der us-datum string lautet: ".$datum->us_date."<br>";
echo "der day string lautet: ".$datum->day."<br>";
echo "der month string lautet: ".$datum->month."<br>";
echo "der year string lautet: ".$datum->year."<br>";
echo "der error string lautet: ".$datum->error."<br>";
echo "der checkage string lautet: ".$datum->checkage()."<br>";
if ($datum->checkage()) echo "der user ist alt genug: "; else echo"zu jung!";
echo "<br><br>der error string lautet: ".$datum->error."<br>";

*/

?>