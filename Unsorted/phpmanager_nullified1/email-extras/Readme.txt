(You probably need to turn word wrap on and print this)

I figured out how to do these things based on the phpManager update that allows you to resend the Activiation Email, and some post back and forths with Locutus about bcc's and receipt generation.

I've followed the below outlined steps myself, uploaded and tested them and they work great for me.  But as with all changes, backup the files to be changed so you can easily go back if you encounter any problems.

This adds the following email functionality to phpManager:

A.  a receipt will no longer be automatically generated when you first activate an account.  However, all subsequent invoices will cause a receipt to be generated and sent.  That's functionality I wanted, but if you don't, before you replace your add.php with the new one, copy the following to the enclosed new add.php on the line right after this code:  $date =  strftime("$dateformat",time());

                                              include ("../../emails/receipt.inc");

B   a bcc (separate copy)  will be sent to you of the activation email when you activate an account

C.  a bcc (separate copy) will be sent to you of the resent activiation email when you resend an activation email in the View Client area

D.  a bcc of the invoice will be sent to you with the subject line:  Invoice Record

E.  a  "Send Final Notice Email" choice button has been added to the View Client area

F.  When you click on the Send Final Notice Email button in View Client, an email with the subject line
 "Overdue Payment - Final Notice" will be sent to the client

G.  a bcc (separate copy) will be sent to you of the Final Notice with the subject line "Final Notice Record"

-----------------------------------------------------------------

If you don't want a particular function listed above, you'll have to revise the code yourself accordingly.

To install:

1) (a)  Replace "yourphpmanagerfolder/admin/clientmanager/add.php" with the new add.php file
1) (b)  Replace "yourphpmanagerfolder/admin/clientmanager/modify.php" with the new modify.php file
1) (c)  Replace "yourphpmanagerfolder/admin/clientmanager/invoice.php" with the new invoice.php file

2) Add the following code to your "yourphpmanagerfolder/templates/admin/clientmanager/viewclients.inc" file:

<button onclick="window.location='modify.php?id=<?php echo $id ?>&finalnoticeemail=yes'" class="formfield">Re-Send Final Notice Email</button>

3) Upload the following includes to your yourphpmanagerfolder/email directory:

activationbcc.inc
invoicerecord.inc
finalnotice.inc
finalnoticebcc.inc

NOTE:  You won't be able to edit these emails in the admin panel of phpManager.  I couldn't figure out how to add them, sorry.  At any rate, before you upload the new includes,  you should take a look at them with a text editor to see if you want to make any changes to the wording before uploading.

The next step,  I'm not 100% sure you need to do, but since a change  was instructed in the Resend activation email update, I included a like change in my language file.  I'm sure Locutus will be able to clarify the need or lack thereof for this step when he reviews this customization, but I do know adding it didn't break anything:

4) Add the following code to your language files:

$finalnoticeemail_sent = "Final notice email sent";