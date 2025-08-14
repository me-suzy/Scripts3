#!/bin/sh

#
# $Id: spam.sh,v 1.4 2002/05/14 09:52:10 mav Exp $
#
# Newsletter mailling script
#

#
# To make this script use sendmail instead of mail
# comment out the line with mail_prog definition
#
mail_prog="mail"
sendmail_prog="sendmail"

#
# Get mail list
#
if [ "${1}" != "" ] 
then 
	mail_list=`cat "${1}"`
fi

#
# Get mail subject
#
if [ "${2}" != "" ] 
then 
	mail_subj=`cat "${2}"`
fi

#
# Get mail body
#
mail_body="${3}"

#
# Get mail "From"
#
if [ "${4}" != "" ] 
then 
	mail_from="${4}"
fi

#
# Send mail to all in maillist
#
for target in $mail_list
	do
	if [ x$mail_prog != x ]; then
		$mail_prog -s "$mail_subj" "$target" < $mail_body
	else
		(echo "To: $target"; echo "From: $mail_from"; echo "Subject: $mail_subj"; echo; cat $mail_body) | $sendmail_prog $target
	fi
done

#
# Delete files
#
rm "$1" "$2" "$3"
