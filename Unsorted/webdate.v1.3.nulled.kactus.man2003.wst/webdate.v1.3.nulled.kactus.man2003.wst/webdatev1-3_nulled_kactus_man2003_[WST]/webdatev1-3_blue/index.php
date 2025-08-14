<?
    require "engine/load_configuration.pml";
    require "engine/scheduler.pml";
    
    session_cache_limiter('none');
    
    $timestamp=time();
    $timeout=$timestamp-$timeoutseconds;

    $db = c();
	if($action == order && $order_n != "")
	{

		include "engine/card_process.pml";

		if($payment_status == success)
		{
			$page = payment_success;
		}
		else
		{
			$page = payment_failed;
		}
	}

	include "engine/login_pages.pml";
    session_start();
	//  Locating current page

	if($page == logout)
	{
        q("DELETE FROM dt_usersonline WHERE userid='$sAuth'");
		$page = index;
		$sAuth = "";

		setcookie("sAuth");
	}

    if ($profile_in_one_step)
    {
    if ($page == "create_profile" && $login != "" && $pswd != "")
    {
        $fMember = f(q("select * from dt_members where login='$login' and pswd='$pswd'"));

        if($fMember[ id ] != "")
        {
			setcookie("sAuth", $fMember[ id ]);
			$sAuth = $fMember[ id ];
        }
    }
    }
    
	if(IsRequiredLogin($page) && $sAuth == "")
	{
		$page = sign_in;
	}

	if(!isset($page) && !isset($current_page))
	{
	 	$current_page = index;
	}

	if(!empty($page))
	{
        if ($page != "view_profile")
        {
	 	     setcookie("current_page", $page);
        }
		$current_page = $page;
	}



	$logged_in = 0;

	//	Handling actions

	if($action == login)
	{
		if($login == "" || $pswd == "")
		{
			$error = "Invalid username or password entered!";
		}
		else
		{
			$fMember = f(q("select * from dt_members where login='$login' and pswd='$pswd'"));

			if($fMember[ id ] == "")
			{
				$error = "The username or password you entered is incorrect.";
			}
			else
			{
				setcookie("sAuth", $fMember[ id ]);
				$sAuth = $fMember[ id ];

				$logged_in = 1;
                q("UPDATE dt_profile set lastlogin=".time()." WHERE member_id=".$fMember[id]);
			}
		}
	}

	if(!$logged_in && isset($sAuth))
	{
		$fMember = f(q("select * from dt_members where id='$sAuth'"));

		if($fMember[ id ] == "")
		{
			setcookie("sAuth");
		}
		else
		{
			$logged_in = 1;
		}
	}

	//	EOF Handling actions

	if($current_page == sign_in && $logged_in)
	{
		$current_page = members_area;
	}




    $ses_id = session_id();

	if($logged_in)
	{
		$member_code = sysGetProfileCode();

                $fExists = f(q("SELECT id from dt_usersonline where session_id='$ses_id'"));
                if ($fExists[id] == "")
                {
                   q("INSERT INTO dt_usersonline (timestamp, ip, login, userid, session_id) VALUES ($timestamp,'$REMOTE_ADDR','$fMember[login]', $fMember[id], '$ses_id')");
                }
                else
                {
                   q("UPDATE dt_usersonline set timestamp=$timestamp, login = '$fMember[login]', userid = $fMember[id] where session_id='$ses_id'");
                }
    }
    else
    {
                $fExists = f(q("SELECT id from dt_usersonline where session_id='$ses_id'"));
                if ($fExists[id] == "")
                {
                   q("INSERT INTO dt_usersonline (timestamp, ip, login, session_id) VALUES ($timestamp,'$REMOTE_ADDR','', '$ses_id')");
                }
                else
                {
                   q("UPDATE dt_usersonline set timestamp=$timestamp where session_id='$ses_id'");
                }
    }
    q("DELETE FROM dt_usersonline WHERE (timestamp<$timeout)");
    $rUsers = q("select DISTINCT ip from dt_usersonline where (login = '')and(timestamp>$timeout)");
    $guestson = (int)nr($rUsers);

    $rUsers = q("select DISTINCT userid, login from dt_usersonline where (login <> '')and(timestamp>$timeout)");
    $registeredon = (int)nr($rUsers);


    include "templates/page_top.ihtml";
    include "engine/pages/$current_page.pml";
    include "templates/page_bottom.ihtml";

	d($db);
?>
