<?



    setcookie("testcookie");

    setcookie("testcookie2","","");

    setcookie("testcookie3","","","/");

    setcookie("testcookie4");

    setcookie("testcookie5","","");

    setcookie("testcookie6","","");

    setcookie("testcookie7","","","/","$HTTP_HOST");

    setcookie("testcookie8","","","/","$HTTP_HOST");

    setcookie("testcookie9");

    setcookie("testcookie10","","");

    setcookie("testcookie11","","","/","$HTTP_HOST");

    setcookie("testcookie12");



    $time = mktime()+600;

    $date = gmstrftime("%A, %d-%b-%Y %H:%M:%S", (mktime()+6400));



    header("Set-Cookie: testcookie=present;");

    header("Set-Cookie: testcookie2=present; expires=$date");

    header("Set-Cookie: testcookie3=present; expires=$date; path=/");



    setcookie("testcookie4", "present");

    setcookie("testcookie5", "present", (time()+6400));

    setcookie("testcookie6", "present", "(time()+6400)");

    setcookie("testcookie7", "present", (time()+6400), "/", "$HTTP_HOST");

    setcookie("testcookie8", "present", "(time()+6400)", "/", "$HTTP_HOST");



    print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie9=present\">\n";

    print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie10=present; expires=$date\">\n";

    print "<meta http-equiv=\"Set-Cookie\" content=\"testcookie11=present; expires=$date; path=/\">\n";



    print "<script>document.cookie = 'testcookie12' + '=' + 'present';</script>\n";



    sleep(1);



    echo "sent 12 TestCookies to your Browser ... checkout your stored Cookies ...";



    exit;



?>