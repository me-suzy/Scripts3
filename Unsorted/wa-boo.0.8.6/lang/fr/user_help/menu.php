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
                
                <a href="welcome.php" target="mainFrame">Bienvenue</a>
                    <br>
                    <br>
                <a href="contacts.php" target="mainFrame">Contacts</a>
                    <br>
                    <br>
                <a href="account.php" target="mainFrame">Compte/groupes</a>
                    <br>
                    <br>
                <a href="prefs.php" target="mainFrame">Préférences</a>
                    <br>
                    <br>
                <a href="impex.php" target="mainFrame">Import/Export</a>
                    <br>
                    <br>
                <a href="clean.php" target="mainFrame">Entretien</a>
                    <br>
                    <br>
                <a href="GPL.php" target="mainFrame">Licence GPL</a>
                    <br>
                    <br>
                <a href="credits.php" target="mainFrame">Crédits</a>
                    <br>
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