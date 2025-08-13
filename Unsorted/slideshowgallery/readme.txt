*************************************************
*                                                                              
*                35mm Slide Gallery 2.0
*                                                               
*************************************************


#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################




Version 4 now with upload module, delete module, comments and real thumbnails.


Feel free to modify the code.
Nothing will be charged as it is distributed under GENERAL PUBLIC LICENSE.
Email me (support@andymack.com) if you use this script in your site. 


1. Upload everything to to the same directory except the following files. (upload php files in ASCII mode)
    -slide.psd (photoshop 6 file, in case you want to change the design of the slide mount)
    -slidev.psd  (photoshop 6 file, in case you want to change the design of the slide mount)
    -GPL.TXT
    -readme.txt

2. Maintain the same hierarchy of both directories and files.
     /index.php
     /config.php
     /delete.php (optional)
     /upload.php (optional)
     /popup.php
     /thumbs.php
     /header.inc
     /footer.inc
     /gallery.css
     /slide_01.gif
     /slide_02.gif
     /slide_04.gif
     /slide_05.gif
     /slidev_01.gif
     /slidev_02.gif
     /slidev_04.gif
     /slidev_05.gif
     /imagefolder1/  (put images here, you can change the name)
     /imagefolder1/album.txt  (album description file, you can modify it or delete it)
     /imagefolder1/koalalikefather.jpg  (image for testing)
     /imagefolder1/koalalikefather.jpg.txt  (corresponding caption file)
     /imagefolder2/  (put images here, you can change the name)

3. edit the variables in the config.php file

5. The script will still work without the upload.php module, however, if you use the upload module to upload files, change the mode of the root directory as well as imagefolder1 and imagefolder2 (if you use them) to 777.

6. The default LOGIN and PASSWORD to the upload.php are both 'admin'. They can be changed in the config.php file.

4. Redesign the slide mounts if necessary

5. Upload images to imagefolder1 and imagefolder2 (rename these directories at your will)

6. Add caption for images if you want to. If your image file is called "hello.jpg", your caption file for that image should be "hello.jpg.txt". 

7. Upload a description file "album.txt" for that particular album if needed. It works fine even without it.

8.  Create more directories if needed.

9. Delete the whole directory using the Delete Module. All files within the directory will be deleted as well as the directory.