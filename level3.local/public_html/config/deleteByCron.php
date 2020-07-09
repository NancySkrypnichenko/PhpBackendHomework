<?php
// list of command that shold put in the cron config


//*/10 * * * * mysql -u root -1605 -D library_base -e "DELETE FROM books WHERE is_active = 0"
//*/10 * * * * mysql -u root -1605 -D library_base -e "DELETE FROM pairs WHERE id_books is null"
//0 1 * * * mysqldump -u root -1605 library_base > /home/nancy/PhpstormProjects/level3.local/dump/dump.sql
// */10 * * * * /home/nancy/www/level3.local/public_html/static/PreFiles/deletePreFilesByCron.php



