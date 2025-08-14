<?php
/****************************************************************/
/*                         phpStatus                            */
/*                       footer.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/

$copyright = "Copyright &copy; 2003 Hinton Web Hosting";

$template->getFile(array(
                   'footer' => 'admin/footer.tpl')
);
$template->add_vars(array(
                   'COPYRIGHT' => $copyright)
);
$template->parse("footer");
?>