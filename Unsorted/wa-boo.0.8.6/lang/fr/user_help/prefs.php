<?
    include ("../../../includes/global_vars.php");
    include ("../../../includes/fotools.php");

    session_start();
?>
<html>
    <head>
    <title>wa-boo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <style><? include ("../../../includes/css.php"); ?></style> 
    </head>

    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="../../../images/bg1.gif">
        <table border="0" cospan="0">
            <tr>
                <td>
                    <div align="center" class="hlptitle">
                         Préférences [Menu]
                    </div>
                    <br>
                   
                    <span class="hlpfont">
                        Cette page d'ajuster finement l'interface et les réglages d'wa-boo<br>
                    </span>
                   
                    <br>
                    <span class="hlpsubtitle">
                        Catégories : 
                    </span>
                    <span class="hlpfont">
                        Chaque utilisateur dispose de 10 catégories maximun, qu'il peut définir librement pour organiser et rechercher ses contacts.
                    </span><br><br>
                    
                    <span class="hlpsubtitle">
                        Liste des contacts : 
                    </span>
                    <span class="hlpfont">
                        Chaque utilisateur peut choisir 4 champs qui seront affichés dans la page de recherche. Ceci lui permet de personnaliser les informations affichées.
                    </span>
                    <br><br>
                                        
                    <span class="hlpsubtitle">
                        Survol des contacts : 
                    </span>
                    <span class="hlpfont">
                        Chaque utilisateur peut choisir 6 champs qui seront affichés dans la page de recherche lorsque la souris survole l'icône de consultation. 
                        Avec les 4 champs affichés dans la page, c'est en tout 10 champs qui sont consultables immédiatement.
                    </span>
                    <br><br>
                    
                    <span class="hlpsubtitle">
                        Recherche : 
                    </span>
                    <span class="hlpfont">
                        Ces options permettent de configurer le moteur de recherche pour les prochaines utilisations. Les types de contacts, les catégories, les groupes peuvent ainsi être adaptés aux besoins de chaque utilisateur. 
                        <br> 
                        Attention ! l'utilisateur peut modifier durant sa session les différentes options du moteur sans que ces modifications soient enregistrées.
                    </span>
                    <br><br>
                    
                                                   
                    <span class="hlpsubtitle">
                        Divers : 
                    </span>
                    <span class="hlpfont">
                        Diverses options qui seront prises en compte dès la validation de la page : la langue d'affichage, l'affichage du champ 'nom' en MAJUSCULES (quel que soit la façon dont il a été saisi), la confirmation ou non des suppressions 
                        (utile pour les utilisateurs prudent de laisser le système demander une confirmation avant suppression d'un contact) et le nombre de contacts par page (permet de réduire la taille de la page et de naviguer à l'aide de flêches <<- et ->>). 
                        la valeur 0 [zéro] désactive la pagination, tous les contacts sont affichés sur la même page.
                    </span>
                    <br><br>
                                                   
                    <span class="hlpsubtitle">
                        Couleurs et polices : 
                    </span>
                    <span class="hlpfont">
                        Ces préférences permettent de définr les couleurs et tailles des différents éléments de l'interface graphique d'wa-boo. 
                        En cliquant sur la palette, vous affichez les 156 couleurs Web standard. Vous pouvez aussi saisir une couleur par sa composition (ex : #AA2255).
                        
                    </span>
                    <br><br>
                 
                </td>
            </tr>
        </table>
        
    </body>
</html>