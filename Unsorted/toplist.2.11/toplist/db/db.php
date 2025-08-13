<?

// DB Connection function, don't edit this.

@mysql_connect("$dbhost","$dbuser","$dbpass") or die('Database connection error');
@mysql_select_db("$dbname") or die('Database error!');

// DB Options function

function db_options(){
        global $option;
        $wyn = "SELECT * FROM toplista_options";
        $wykonaj = mysql_query($wyn);
        $option = mysql_fetch_array($wykonaj);
};

function db_functions(){
        global $function;
        $wyn = "SELECT * FROM toplista_functions";
        $wykonaj = mysql_query($wyn);
        $function = mysql_fetch_array($wykonaj);
};

function banned($level){
        global $REMOTE_ADDR;
        $wyn = "SELECT * FROM toplista_banned WHERE typeofban='$level'";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        $bannedip = $tab[0];
        if($bannedip == $REMOTE_ADDR){
        echo "Banned!";
        die();
        }
        }

};

@db_functions();

?>
