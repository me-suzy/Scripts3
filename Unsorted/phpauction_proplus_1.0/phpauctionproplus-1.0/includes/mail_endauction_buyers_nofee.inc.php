#
# --Send winner email address to seller
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
#                        <#i_qty#>              Auction End date/time
#           <#w_report#>            Winner report/list
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

Dear <#w_name#>,

Congratulations, you have won the auction at <#c_sitename#>.
Unfortunately, your account does not have enough credits to pay the Final Value
Fee that is  charged for all auctions that close with winner(s).

The auction's information is below:

Title: <#i_title#>
Item: <#i_description#>
Quantity: <#i_qty#>
End Date: <#i_ends#>
Bids: <#i_currentbid#>

Please access <#c_siteurl#> and login to add credits to your
account to receive the seller's contact information.

If you have received this message in error, please reply to this email,
write to <#c_adminemail#>, or visit <#c_sitename#> at <#c_siteurl#>.

