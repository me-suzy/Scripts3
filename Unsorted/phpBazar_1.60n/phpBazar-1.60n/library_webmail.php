<?
#################################################################################################
#
#  project              : phpBazar
#  filename             : library_webmail.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : WebMail Library
#
#################################################################################################



class phpmailer

{

    /////////////////////////////////////////////////

    // PUBLIC VARIABLES

    /////////////////////////////////////////////////



    /**

     * Sets the From email address for the message. Default value is "root@localhost".

     * @public

     * @type string

     */

    var $From               = "root@localhost";



    /**

     * Sets the From name of the message. Default value is "Root User".

     * @public

     * @type string

     */

    var $FromName          = "Root User";



    /**

     * Sets the Sender email of the message. If not empty, will be sent via -f to sendmail

     * or as 'MAIL FROM' in smtp mode. Default value is "".

     * @public

     * @type string

     */

    var $Sender            = "";



    /**

     * Sets the Subject of the message. Default value is "".

     * @public

     * @type string

     */

    var $Subject           = "";



    /**

     * Sets the Body of the message.  This can be either an HTML or text body.

     * If HTML then run IsHTML(true). Default value is "".

     * @public

     * @type string

     */

    var $Body               = "";



    /**

     * Sets word wrapping on the message. Default value is 0 (off).

     * @public

     * @type int

     */

    var $WordWrap          = 0;



    /**

     *  Turns Microsoft mail client headers on and off. Default value is false (off).

     *  @public

     *  @type bool

     */

    var $UseMSMailHeaders = false;





    /////////////////////////////////////////////////

    // PRIVATE VARIABLES

    /////////////////////////////////////////////////



    /**

     *  Holds all "To" addresses.

     *  @type array

     */

    var $to              = "";



    /**

     *  Holds all "CC" addresses.

     *  @type array

     */

    var $cc              = "";



    /**

     *  Holds all "BCC" addresses.

     *  @type array

     */

    var $bcc             = "";



    /**

     *  Holds all "Reply-To" addresses.

     *  @type array

     */

    var $ReplyTo         = "";



    /**

     *  Holds all string and binary attachments.

     *  @type array

     */

    var $attachment      = array();





    /////////////////////////////////////////////////

    // RECIPIENT METHODS

    /////////////////////////////////////////////////





    function AddCustomHeader($name = "") {

        return true;

    }



    function AddAddress($address, $name = "") {

        $this->to = trim($address);

        $this->toname = trim($name);



    }



    function AddCC($address, $name = "") {

        $this->cc = trim($address);

    }



    function AddBCC($address, $name = "") {

        $this->bcc = trim($address);

    }





    /////////////////////////////////////////////////

    // MAIL SENDING METHODS

    /////////////////////////////////////////////////



    /**

     * Creates message and assigns Mailer. If the message is

     * not sent successfully then it returns false.  Returns bool.

     * @public

     * @returns bool

     */

    function Send() {

	global $database,$timestamp,$ip;



        if(count($this->to) < 1)

        {

            $this->error_handler("You must provide at least one recipient email address");

            return false;

        }



	$att1=$this->attachment[0];

	$att2=$this->attachment[1];

	$att3=$this->attachment[2];



	$fromquery=mysql_db_query($database,"SELECT id FROM userdata WHERE email='$this->From'");

	$from=mysql_fetch_array($fromquery);

	$toquery=mysql_db_query($database,"SELECT id FROM userdata WHERE email='$this->to'");

	$to=mysql_fetch_array($toquery);



	$result=mysql_db_query($database,"INSERT INTO webmail (fromid,fromname,fromemail,toid,toname,toemail,timestamp,ip,subject,text,attachment1,attachment2,attachment3)

				    VALUES ('$from[id]','$this->FromName','$this->From','$to[id]','$this->toname','$this->to','$timestamp','$ip','$this->Subject','$this->Body','$att1','$att2','$att3')");

	if(!result)

	{

            return false;

        }



        return true;

    }





    /////////////////////////////////////////////////

    // ATTACHMENT METHODS

    /////////////////////////////////////////////////



    function AddAttachment($path, $name = "", $encoding = "", $type = "") {

	global $timestamp,$bazar_dir,$webmail_path;

        if(!@is_file($path))

        {

            $this->error_handler(sprintf("Could not access [%s] file", $path));

            return false;

        }



        $filename = basename($path);

        if($name == "")

            $name = $filename;



        // Append to $attachment array

	$temp=explode(".","$name");

	$ext=".".$temp[(count($temp)-1)];



	if ($ext!=".txt" && $ext!=".doc" && $ext!=".pdf" &&

	    $ext!=".gif" && $ext!=".jpg" && $ext!=".png" &&

	    $ext!=".zip" && $ext!=".gz") $ext=".txt";



        $cur = count($this->attachment);

	$picturename=$timestamp+$cur.$ext;

        $this->attachment[$cur] = $picturename;

        copy("$path","$bazar_dir/$webmail_path/$picturename");

        if (!is_file("$bazar_dir/$webmail_path/$picturename")) {

	   died ("$bazar_dir/$webmail_path is not a dir or not writeable!");

	}



        return true;

    }



}

// End of class

?>

