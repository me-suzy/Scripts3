Installation Instructions
newest mod - funkydunk 2003
==========================================================================================

1 - Apply the configpatch.sql to your database using the patch/upgrade facility in admin
2 - copy the files included in the zip file to your xcart directory

Options:
==========================================================================================
The various options are controllable through the admin centre-> general settings ->appearance
This will enable you to switch of the links to the last two weeks, last month etc in the newest products display,
and also to control whether the link is displayed in the specials menu.

Note:
==========================================================================================
If gift certificated are not enabled on your website, simply replace the code in customer/home.tpl where it says:

{if $active_modules.Gift_Certificates ne "" or $active_modules.Product_Configurator ne ""}
{include file="customer/special.tpl"}
{/if}

with

{include file="customer/special.tpl"}

A new home.tpl has not been included as this would overwrite any changes you had made to the layout of your site...hope you understand :)

This modification is based on a 3.4.7 version x-cart Gold copyright RRF (http://www.rrf.ru)