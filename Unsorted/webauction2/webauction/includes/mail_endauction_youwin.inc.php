# --Send winner notification
#
#                         This file contains the message your customers
#                         will receive when someone sends them an auction.
#                        Lines starting with # will be skipped.
#                        Blank lines will be maintained.
#
#                        Change the message below as needed considering the
#                        following tags to reflect your customer's personal data:
#
#        --------------------------------------------------------
#                        TAG SYNTAX                                EFFECT
#        --------------------------------------------------------
#
#                         <#n_bid#>             Neues Höchstgebot
#                        <#w_name#>              Winner Name
#                        <#w_nick#>              Winner Nickname
#                        <#s_name#>              Seller Name
#                        <#s_nick#>              Seller Nickname
#                        <#s_email#>             Seller email
#                        <#s_address#>           Seller Address
#                        <#s_city#>              Seller City
#                        <#s_prov#>              Seller State/Province
#                        <#s_country#>           Seller Country
#                        <#s_zip#>               Seller Zip Code
#                        <#s_phone#>             Seller Phone
#                        <#i_title#>             auction item title
#                        <#i_description#>       auction item description
#                        <#i_url#>               URL to view auction
#                        <#i_ends#>              Auction End date/time
#           <#c_sitename#>          Auction Site Name
#           <#c_siteurl#>           main URL of auction site
#           <#c_adminemail#>        email address of Auction site webmaster
#        --------------------------------------------------------
#
#                        USAGE:
#                        Insert the above tags in the text of your message
#                        where you want each value to appear.
#                        Modify the message to reflect your needs.
#                        Change [...] with to your correct data.
#
#
#

Liebe/r <#w_name#>,

Herzlichen Glückwunsch.

Sie erhielten den Zuschlag für die Auktion <#i_title#>

Auktions Daten
------------------------------------
Titel: <#i_title#>

Ihr Gebot: <#o_bid#>

Auktions Ende: <#i_ends#>

URL: <#i_url#>

Vekäufer Benutzername: <#s_nick#>

Verkäufer Adresse:
<#s_name#>
<#s_address#>
<#s_zip#>
<#s_city#>
<#s_prov#>

Verkäufer Telefon: <#s_phone#>

Verkäufer E-Mail Adresse: <#s_email#>

Bitte hinterlassen Sie nach dem Ende Ihrer Transaktion eine Bewertung unter: 
<#s_feedurl#>

Hier nun die nächsten Schritte:

*Käufer und Verkäufer sollten innerhalb von drei Geschäftstagen miteinander Kontakt aufnehmen,
 um den Verkauf abzuschliessen. Wenn der Verkäufer sich nicht um eine Kontaktaufnahme bemüht,
 kommt der Vertrag nicht zustande und der Verkäufer erhält unter Umständen von Ihnen eine negative Bewertung.
*Helfen Sie anderen <#c_sitename#>-Mitgliedern,indem Sie eine Bewertung Ihrer Transaktion einreichen.
 Hinweis: Als Meistbietender sollten Sie möglichst bald Ihre Zahlung an den Verkäufer senden.

Wir freuen uns, dass Sie bei dieser Auktion erfolgreich waren,und hoffen,Sie demnächst wieder bei <#c_sitename#>
zu sehen!

Weiterhin frohes Handeln wünscht Ihnen <#c_sitename#>

<#c_siteurl#>