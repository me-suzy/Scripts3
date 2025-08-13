BK_TODAY=`echo \`date\``
BK_PREFIX="auctions_backup"
USERNAME="  "
mysqldump -e -p --add-drop-table --add-locks -l $USERNAME > "$BKPREFIX $BK_TODAY"
