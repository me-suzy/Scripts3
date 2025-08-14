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
                         Import et Export [Menu]
                    </div>
                    
                    <br>
                   
                    <span class="hlpfont">
                        Ces fonctions d'Import et d'export permettent à wa-boo de communiquer avec les autres applications. Les fonctions d'entretien complêtent la panoplie d'import-export.
                        Ces deux fonctions sont clairement documentées lors de leur réalisation, mais nous rappelons ici les grandes lignes.
                        <br><br>Attention ! Alors que l'export permet d'exporter tous les types de contacts (y compris le type GROUPE - voir rubrique Contacts), l'import crée toujours des contacts de type PRIVE (tout du moins dans cette version). 
                        <br>
                    </span>
                    
                    <br>
                    <span class="hlpsubtitle">
                        Import : 
                    </span>
                    <span class="hlpfont">
                        L'import permet d'intégrer dans wa-boo des informations provenant d'un autre logiciel.
                        La procédure est composée de 4 phases successives (en considérant que vous disposez déjà d'un fichier exporté à partir d'un autre logiciel)
                        <br>- Etape 1 : Choix du fichier dans votre ordinateur et envoi au serveur wa-boo
                        <br>- Etape 2 : Analyse du fichier. wa-boo vous indique le nombre de lignes, de colonnes, le nombre de contacts maximum dans la base.
                        <br>- Etape 3 : Sélection des champs à importer. Vous indiquez à wa-boo les champs que vous désirez importer.
                        <br>- Etape 4 : Contrôle des doublons. Attention !, pour fonctionner, cette option doit être activée à l'installation d'une part. 
                        D'autre part, si vous commencez l'import à partir d'une base contenant des doublons, cette étape sera ignorée
                        <br>- Etape 5 : Résultat de l'importation. wa-boo précise ce qu'il s'est passé lors de l'importation (nombre de contacts, de doublons, etc.).
                         
                    </span>
                   
                    <br><br>
                    <span class="hlpsubtitle">
                        Export :
                    </span>
                   
                    <span class="hlpfont">
                        L'export permet de créer un fichier composé de tout ou partie de vos contacts afin de les réimporter dans un autre logiciel (ou dans wa-boo).
                        <br>- Etape 1 : Sélection des contacts,
                        <br>- Etape 2 : Récupération du fichier. Vous pouvez choisir de télécharger le fichier ou d'afficher son contenu dans votre navigateur.  

                    </span>
                    <br>
                    <br>
                    
                </td>
            </tr>
        </table>
        
    </body>
</html>