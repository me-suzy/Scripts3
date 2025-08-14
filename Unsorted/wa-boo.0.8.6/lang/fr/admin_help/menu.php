<?
    include ("../../../includes/global_vars.php");
    include ("../../../includes/fotools.php");

    session_start();
    $menuColor = buttonFontColor($bgcolor)
?>
<html>
    <head>
        <title>Untitled Document</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <style>
            <? include ("../../../includes/css.php"); ?>
            a:hover {color: #999999; text-decoration: underline}
            a {color: <? echo buttonFontColor($s_user_context["color"]["menu"]); ?>; text-decoration: none}
        </style> 
    </head>

    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

    <table>
        <tr bgcolor="<? echo $s_user_context["color"]["menu"] ?>">
            <td class="hlpmenu">
                
                <a href="welcome.php" target="mainFrame">Généralités</a>
                    <br>
                    <br>
                <a href="credits.php" target="mainFrame">Crédits</a>
                    <br>
                    <br>
                
                <div align="center">
                    <img src="../../../images/gfi_1.gif">
                </div>
            </td>
        </tr>
    </table>
    </body>
</html>