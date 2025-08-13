<?php

/* D.E. Classifieds v1.03 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

function cnfg($the_key){


  $cnfg = array(



  /*****************************************
   *
   * DO NOT CHANGE ANYTHING 
   * ABOVE THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   */

   
  /* This if the info that you'll need for 
   * connecting to and talking to you database.
   * You may have to get this info from your 
   * web hosting provider.
   */
  'dbHost'         => "", 
  'dbUser'         => "",
  'dbPass'         => "", 
  'dbName'         => "",


  /* deHome - path to index file.
   */
  'deHome'         => "http://domain.com/path/to/installation/index.php", 

  /* deDir - path to classifieds directory. 
   * Include trailing slash (/) 
   */
  'deDir'          => "http://domain.com/path/to/installation/", 


  /* This is the name of the site.
   * This shouln't be confused with the domain name 
   * of the site, although the two could be the same.
   * For example if the name of your classified ads 
   * site is "Big Joe's Classifieds", then you should 
   * set the value of 'siteName' to "Big Joe's Classifieds" .
   * The name of the site may be used in outgoing emails, and 
   * in other situations when the application needs to 
   * refer to the site that it belongs to.
   */
  'siteName' => "D.E. Classifieds Demo", 


  
  /* These values determine which template files 
   * are used for a particular page.
   * All the keys are prefixed with "tmplt_".
   * After the "tmplt_" prefix is the exact 
   * name of the php file(minus the .php extension 
   * that uses the template file.
   * 
   * Example: 
   * 'tmplt_add_item' => "html_template1.php", 
   * 
   * In the example "add_item.php" uses the template 
   * file named "html_template1.php" .
   *
   * Caution:
   *  The value should only be the file name of the 
   *  template file.  It should not contain the path 
   *  to the file itself. The path to the file should 
   *  be supplied in the "path_cnfg.php" file in the 
   *  'pathToTemplatesDir' value.  
   *  Whatever template file you use should
   *  be kept in your "templates" directory.
   *
   */
  'tmplt_add_item' => "html_template1.php", 

  'tmplt_add_item_results' => "html_template1.php", 
   
  'tmplt_details' => "html_template1.php",   

  'tmplt_edit' => "html_template1.php", 
  
  'tmplt_index' => "html_template1.php", 

  'tmplt_log_in' => "html_template1.php", 

  'tmplt_log_out' => "html_template1.php", 
  
  'tmplt_register' => "html_template1.php", 
 
  'tmplt_search' => "html_template1.php", 

  'tmplt_select_to_add' => "html_template1.php", 

  'tmplt_showCat' => "html_template1.php", 

  'tmplt_verify_registration' => "html_template1.php", 


  /* replyEmail -  This is the email address shown when new users
   * registered.  
   * If this value is not a valid, deliverable email address it could cause 
   * problems with some ISPs.
   */
  'replyEmail'     => "webmaster@domain.com", 


  /******************************
   SETTING THE VALUES BELOW THIS POINT IS OPTIONAL, 
   ALTHOUGH DOING SO WILL GIVE YOU MUCH MORE CONTROL 
   OVER HOW YOUR SITE LOOKS ANS BEHAVES.
   ****************/

  /* logOutMessage - The message the user recieves when 
   * they log off of the site.
   * I'll probably move this to another file in future versions.
   */
  'logOutMessage'  => "You have successfully logged out.<BR>", 


  /* expireAdsDays - The number of days the ad 
   * will expire in after it's been posted.
   * Do not quote.
   */
  'expireAdsDays' => 30, 

  'expireTempUsersDays' => 7, 

  
  /* rowsOfAdsTableWidth - 
   * This sets the width of the table 
   * where rows of results are displayed 
   * after searching, and when browsing 
   * categories.
   * Width is relative to the element that 
   * this table is contained in.(ie relative to its parent table)
   */
  'rowsOfAdsTableWidth' => "80%", 

  /* viewAdsRowColor1 -
   * viewAdsRowColor2 -
   * These are the colors of the rows 
   * where people are browsing/searching ads.
   */
  'viewAdsRowColor1' => "#CCCC88", 

  'viewAdsRowColor2' => "#FFFF66", 


  /* rowsOfEditAdsTableWidth - 
   * This sets the width of the table 
   * on the edit page.
   */
  'rowsOfEditAdsTableWidth' => "100%",   


  /* editAdsRowColor1 -
   * editAdsRowColor2 -
   * These are the colors of the rows 
   * on the page where the user edits their ads.
   */
  'editAdsRowColor1' => "#CC6668", 

  'editAdsRowColor2' => "#FF6666", 


  /* mainCatsCols - Number of columns 
   * for category navigation. 
   * This number should not be quoted. 
   */
  'mainCatsCols' => 1, 

  /* mainCatsBufferCols - 
   * Space between columns for category navigation. 
   *
   * Right way: 'mainCatsBufferCols' => "20",
   * Right way: 'mainCatsBufferCols' => "20%",
   * Wrong way: 'mainCatsBufferCols' => 20px
   * 
   * This value has no effect is mainCatsCols is set to 1 .
   */
  'mainCatsBufferCols' => "10", 


  /* Width of table that top-level categories will 
   * be displayed in. 
   * Should be quoted.
   */
  'mainCatsTableWidth' => "100%", 

  'mainCatsTableSpace' => "1", 

  'mainCatsTablePad' => "1", 

  /* The max width for each column.
   * 
   */
  'mainCatsTdWidth' => "100%", 

  /* Set to true If you want the list of top-level categories to be 
   * centered inside of their parent table, false if you don't.  
   * This should not be quoted.
   */
  'mainCatsCenter' => true,

  /* Number of columns for sub-category navigation. 
   * Should NOT be quoted. 
   */
  'subCatsCols' => 3, 


  /* Same rules apply for subCatsBufferCols 
   * as for mainCatsBufferCols.
   */
  'subCatsBufferCols' => "12%", 


  /* Width of table that sub categories will 
   * be displayed in. 
   * Should be quoted.
   */
  'subCatsTableWidth' => "100%", 

  'subCatsTableSpace' => "3", 

  'subCatsTablePad' => "3", 

  /* The max width for each sub-cat column
   * Just a bare number.
   */
  'subCatsTdWidth' => "25%", 

  /* Set to true If you want the sub categories to be 
   * centered inside of their parent table, false if you don't.  
   * This should not be quoted.
   */
  'subCatsCenter' => true,

  'logInFormCaption' => "LogIn", 

  'logInFormCaptionFontColor' => "#000000", 

  'logInFormCaptionBgColor' => "#FFFF66", 
  

  'logInFormWidth' => "120",

  'logInFormBgColor' => "#CCCCCC", 

  'logInStatusWidth' => "150",

  'logInStatusBgColor' => "#FFFFFF", 


  /* userPassMinLength -
   * userPassMaxLength -
   * User will get an error if he/she tries to 
   * choose a password shorter than 'userPassMinLength' 
   * or longer than 'userPassMaxLength' .
   */
  'userPassMinLength' => 5,
   
  /* If you want user passwords to be able 
   * to be longer than 20 characters then 
   * you'll need to change the definition of the 
   * password field in the std_users table.
   */
  'userPassMaxLength' => 20, 


  /* userNameMinLength -
   * userNameMaxLength -
   * Same rules apply as for the above. 
   */
  'userNameMinLength' => 5,

  'userNameMaxLength' => 20



  /* DO NOT CHANGE ANYTHING 
   * BELOW THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   *
   ******************************************/





    ) ;



  return $cnfg[$the_key];


} // end function get_cnfg_varrs()


?>
