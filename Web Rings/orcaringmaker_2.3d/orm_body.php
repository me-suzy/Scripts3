<?php /* ***** Orca Ringmaker - Body File ********************* */

/* ***************************************************************
* Orca Ringmaker v2.3d
*  A comprehensive web ring creation and managment script
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
************************************************************ */ ?>

<div id="orm_main">

  <?php if ($dData['online']) echo loadTemplate("menu"); /* *** See orm_template_menu.html *** */ ?> 

  <div id="orm_content">
    <?php if (count($eData['success']) || count($eData['error'])) { ?> 
      <ul id="orm_notify">
        <?php foreach ($eData['success'] as $success) echo "<li>".strFunx($success, "1100")."</li>\n    "; ?> 
        <?php foreach ($eData['error'] as $error) echo "<li class=\"orm_warn\">".strFunx($error, "1100")."</li>\n    "; ?> 
      </ul>
    <?php } ?>

    <?php switch ($rData['event']) {

      /* ***** Add Site *************************************** */
      case "Add":
        switch ($rData['success']) {
          case "Add-Complete": ?> 
            <p><?php echo $_ORMPG['add.complete.message']; ?></p>
            <textarea rows="5" cols="45" class="orm_tacode"><?php echo $_ORMPG['add.complete.code']; ?></textarea> 
            <?php break;

          case "Add-Verify": ?> 
            <p><?php echo $_ORMPG['add.verify.message']; ?></p>
            <?php break;

          default:
            $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $_ORMPG['add.form.header']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_buser" title="<?php echo $_ORMPG['add.form.username.title']; ?>"><?php echo $_ORMPG['add.form.username.text']; ?></label>
                      <input type="text" name="user" size="10" id="orm_buser"<?php if ($_ORMPG['add.form.username.post']) echo " value=\"{$_ORMPG['add.form.username.post']}\""; ?> />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bpass1" title="<?php echo strFunx($lang['pagem'], "0101"); ?>"><?php echo $lang['termi']; ?></label>
                      <input type="password" name="pass1" size="10" id="orm_bpass1" />
                      <input type="password" name="pass2" size="10" id="orm_bpass2" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bemail"><?php echo strFunx($lang['pagen'], "0101"); ?></label>
                      <input type="text" name="email" size="30" id="orm_bemail"<?php if (isset($_POST['email'])) echo " value=\"".strFunx($_POST['email'], "0101")."\""; ?> />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bowner" title="<?php echo strFunx($lang['pagep'], "0101"); ?>"><?php echo $lang['pageo']; ?></label>
                      <input type="text" name="owner" size="20" id="orm_bowner"<?php if (isset($_POST['owner'])) echo " value=\"".strFunx($_POST['owner'], "0101")."\""; ?> />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_btitle"><?php echo $lang['pageq']; ?></label>
                      <input type="text" name="title" size="30" id="orm_btitle"<?php if (isset($_POST['title'])) echo " value=\"".strFunx($_POST['title'], "0101")."\""; ?> />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bURI"><?php echo $lang['pager']; ?></label>
                      <input type="text" name="URI" size="45" id="orm_bURI" value="<?php echo (isset($_POST['URI'])) ? strFunx($_POST['URI'], "0101") : "http://"; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bdescription" title="<?php echo strFunx($lang['paget'], "0101"); ?>"><?php echo $lang['pages']; ?></label>
                      <textarea rows="5" cols="50" name="description" id="orm_bdescription" class="orm_tasans"><?php if (isset($_POST['description'])) echo strFunx($_POST['description'], "0101"); ?></textarea>
                    </td>
                  </tr>
                  <?php if ($vData['authimage']) { ?> 
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_bauth" title="<?php echo strFunx($lang['auth2'], "0101"); ?>"><?php echo $lang['auth3']; ?></label>
                        <img src="<?php echo $_SERVER['PHP_SELF']; ?>?Auth&<?php echo $md5; ?>" alt="" /><br />
                        <input type="text" name="auth" id="orm_bauth" size="6" id="orm_bauth" />
                        <input type="hidden" name="authcheck" value="<?php echo $md5; ?>" />
                      </td>
                    </tr>
                  <?php } ?> 
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="reset" value="<?php echo $lang['termj']; ?>" />
                      <input type="hidden" name="event" value="Add" />
                      <input type="submit" value="<?php echo $lang['term5']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>

        <?php }
        break;


      /* ***** Ring Setup ************************************* */
      case "Setup":
        $bkg = 0; ?> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
            <thead>
              <tr>
                <th><?php echo $lang['pagej']; ?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bringname"><?php echo $lang['pag_a']; ?></label>
                  <input type="text" name="ringname" size="30" id="orm_bringname" value="<?php echo strFunx($vData['ringname'], "0101"); ?>" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bringemail"><?php echo $lang['pag_b']; ?></label>
                  <input type="text" name="ringemail" size="30" id="orm_bringemail" value="<?php echo strFunx($vData['ringemail'], "0101"); ?>" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bsmtp" title="<?php echo strFunx($lang['pag_d'], "0101"); ?>"><?php echo $lang['pag_c']; ?></label>
                  <input type="text" name="smtp" size="30" id="orm_bsmtp" value="<?php echo strFunx($vData['smtp'], "0101"); ?>" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_btimezone" title="<?php echo strFunx($lang['p___v'], "0101"); ?>"><?php echo $lang['p___w']; ?></label>
                  <input type="text" name="timezone" size="5" id="orm_btimezone" value="<?php echo strFunx($vData['timezone'], "0101"); ?>" maxlength="5" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_btzoffset" title="<?php echo strFunx($lang['p___x'], "0101"); ?>"><?php echo $lang['p___y']; ?></label>
                  <input type="text" name="tzoffset" size="5" id="orm_btzoffset" value="<?php echo strFunx($vData['tzoffset'], "0101"); ?>" maxlength="5" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bhelpflag" title="<?php echo strFunx($lang['p___z'], "0101"); ?>"><?php echo $lang['page_']; ?></label>
                  <?php echo $lang['termm']; ?>: <input type="radio" name="helpflag" size="5" id="orm_bhelpflag" <?php if ($vData['helpflag']) echo "checked=\"checked\" "; ?>value="1" onclick="document.getElementById('orm_bhelpfile').disabled='';" />
                  <?php echo $lang['termn']; ?>: <input type="radio" name="helpflag" size="5" <?php if (!$vData['helpflag']) echo "checked=\"checked\" "; ?>value="0" onclick="document.getElementById('orm_bhelpfile').disabled='disabled';" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bhelpfile" title="<?php echo strFunx($lang['page0'], "0101"); ?>"><?php echo $lang['pag__']; ?></label>
                  <?php if (!@file_exists($vData['helpfile'])) { ?> 
                    <small class="orm_warn"><?php printf($lang['Page_'], $vData['helpfile']); ?></small><br />
                  <?php } ?> 
                  <input type="text" name="helpfile" size="30" id="orm_bhelpfile" value="<?php echo strFunx($vData['helpfile'], "0101"); ?>" />
                  <?php if (!$vData['helpflag']) { ?> 
                    <script type="text/javascript"><!--
                      document.getElementById('orm_bhelpfile').disabled='disabled';
                    // --></script>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bauthimage" title="<?php echo strFunx($lang['pag_0'], "0101"); ?>"><?php echo $lang['pa___']; ?></label>
                  <select name="authimage" size="1" id="orm_bauthimage">
                    <option value="0"<?php if (!$vData['authimage']) echo " selected=\"selected\""; ?>><?php echo $lang['termo']; ?></option>
                    <?php for ($x = 1; $x <= 5; $x++) { ?> 
                      <option value="<?php echo $x; ?>"<?php if ($vData['authimage'] == $x) echo " selected=\"selected\""; ?>><?php echo $lang['termp'].": ".sprintf($lang['pa__0'], $x); ?></option>
                    <?php } ?> 
                  </select>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bsitelimit" title="5 ~ 50"><?php echo $lang['pag_f']; ?></label>
                  <input type="text" name="sitelimit" size="5" id="orm_bsitelimit" value="<?php echo strFunx($vData['sitelimit'], "0101"); ?>" maxlength="2" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bstatlimit" title="5 ~ 50"><?php echo $lang['pag_g']; ?></label>
                  <input type="text" name="statlimit" size="5" id="orm_bstatlimit" value="<?php echo strFunx($vData['statlimit'], "0101"); ?>" maxlength="2" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bstatcachetype" title="<?php echo strFunx($lang['p___h'], "0101"); ?>"><?php echo $lang['p___i']; ?></label>
                  <select size="1" name="statcachetype" id="orm_bstatcachetype">
                    <option value="none"<?php if ($vData['statcachetype'] == "none") echo " selected=\"selected\""; ?>><?php echo $lang['p___j']; ?></option>
                    <option value="hourly"<?php if ($vData['statcachetype'] == "hourly") echo " selected=\"selected\""; ?>><?php echo $lang['p___k']; ?></option>
                    <option value="daily"<?php if ($vData['statcachetype'] == "daily") echo " selected=\"selected\""; ?>><?php echo $lang['p___l']; ?></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bstaticon" title="<?php echo strFunx($lang['p____'], "0101"); ?>"><?php echo $lang['p___0']; ?></label>
                  <?php if (!@file_exists($vData['staticon'])) { ?> 
                    <small class="orm_warn"><?php printf($lang['Page0'], $vData['staticon']); ?></small><br />
                  <?php } else { ?> 
                    <img src="<?php echo $vData['staticon']; ?>" alt="<?php echo $lang['p___0']; ?>" />
                  <?php } ?> 
                  <input type="text" name="staticon" size="30" id="orm_bstaticon" value="<?php echo strFunx($vData['staticon'], "0101"); ?>" />
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bannouncement"><?php echo $lang['pag_h']; ?></label>
                  <textarea rows="5" cols="50" name="announcement" id="orm_bannouncement" class="orm_tasans"><?php echo strFunx($vData['announcement'], "0101"); ?></textarea>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <input type="reset" value="<?php echo $lang['termj']; ?>" />
                  <input type="hidden" name="event" value="Setup" />
                  <input type="hidden" name="setup" value="Change" />
                  <input type="submit" value="<?php echo $lang['term7']; ?>" />
                </td>
              </tr>
            </tbody>
          </table>
        </form>

        <?php if ($rData['allcount'] > 2) {
          $bkg = 0; ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
              <thead>
                <tr>
                  <th><?php echo $lang['p___8']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <input type="hidden" name="event" value="Setup" />
                    <input type="hidden" name="setup" value="Reorder" />
                    <input type="submit" name="Randomize" value="<?php echo $lang['p___9']; ?>" /><br />
                    <input type="submit" name="SiteID" value="<?php echo $lang['p___a']; ?>" /><br />
                    <input type="submit" name="SiteTitle" value="<?php echo $lang['p___r']; ?>" />
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        <?php } ?>

        <?php ob_start();
        if ($rData['allcount']) {
          $select = mysql_query("SELECT `id`, `owner`, `title`, `admin` FROM `{$dData['tablename']}` ORDER BY `admin` DESC, `owner`;");
          $bkg = 0; ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="clear:right;">
            <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
              <thead>
                <tr>
                  <th><?php echo $lang['pag_i']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label for="orm_bmember"><?php echo $lang['pag_j']; ?></label><br />
                    <select name="member" id="orm_bmember" size="1">
                      <option value="NULL"><?php echo $lang['pag_k']; ?></option>
                      <?php for ($x = 0; $x < mysql_num_rows($select); $x++) {
                        $admin = (mysql_result($select, $x, "admin")) ? $lang['term2'] : $lang['term6'];
                        ?><option value="<?php echo mysql_result($select, $x, "id"); ?>"><?php echo strFunx(mysql_result($select, $x, "owner"), "0101")." | ".strFunx(mysql_result($select, $x, "title"), "0101")." | $admin"; ?></option>
                      <?php } ?> 
                    </select>
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <input type="hidden" name="event" value="Setup" />
                    <input type="hidden" name="setup" value="Toggle Status" />
                    <input type="submit" value="<?php echo $lang['termb']; ?>" />
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        <?php } ?> 

        <?php $bkg = 0; ?> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
            <thead>
              <tr>
                <th><?php echo $lang['pag_l']; ?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <h4 class="orm_warn"><?php echo $lang['pag_m']; ?></h4>
                  <p>
                    <?php echo preg_replace("/[\n\r]{2,}/", "</p>\n<p>", $lang['html3']); ?> 
                  </p>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label><?php echo $lang['pag_n']; ?></label><br />
                  <?php $navbar = (strpos($vData['navhtml'], "*") !== 0) ? sprintf($vData['wrap'], $vData['navscript']) : $vData['navscript'];
                  $rep = array($rData['thisURI'], "0", $vData['ringname'], $vData['ringemail']);
                  $navbar = str_replace($rData['coderep'], $rep, $navbar);
                  echo $navbar; ?> 
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bnavscript"><?php echo $lang['pag_o']; ?></label><br />
                  <textarea rows="10" cols="55" name="navhtml" id="orm_bnavhtml" class="orm_tacode"><?php echo strFunx($vData['navhtml'], "0101", false); ?></textarea>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <label for="orm_bnavhtml"><?php echo $lang['pag_p']; ?></label><br />
                  <textarea rows="10" cols="55" name="navscript" id="orm_bnavscript" class="orm_tacode"><?php echo strFunx($vData['navscript'], "0101", false); ?></textarea>
                </td>
              </tr>
              <tr>
                <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                  <input type="reset" value="<?php echo $lang['termj']; ?>" />
                  <input type="hidden" name="event" value="Setup" />
                  <input type="hidden" name="setup" value="Submit" />
                  <input type="submit" value="<?php echo $lang['termc']; ?>" />
                </td>
              </tr>
            </tbody>
          </table>
        </form>

        <?php $rData["ob"] = ob_get_contents();
        ob_end_clean();
        break;


      /* ***** Administration ********************************* */
      case "Admin":
        if (!$rData['allcount']) { ?> 
          <h3><?php echo $lang['p___4']; ?></h3>

        <?php } else {
          switch ($rData['admin']) {
            case "Edit":
              $bkg = 0; ?> 
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                  <thead>
                    <tr>
                      <th><?php printf($lang['pag_q'], $_SERVER['PHP_SELF'], mysql_result($aData['row'], 0, "id"), strFunx(mysql_result($aData['row'], 0, "owner"), "0101")); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><?php echo $lang['p___7']; ?></label>
                        <strong><big><?php echo mysql_result($aData['row'], 0, "id"); ?></big></strong>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><?php echo $lang['Page4']; ?></label>
                        <strong><small><?php if (mysql_result($aData['row'], 0, "joindate") > $vData['joindate']) {
                          echo dateStamp(mysql_result($aData['row'], 0, "joindate"));
                        } else printf($lang['Page5'], dateStamp($vData['joindate'])); ?></small></strong>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_bpass1"><?php echo $lang['pag_1']; ?></label>
                        <ul>
                          <li>
                            <label for="orm_bstatus1"><?php echo $lang['db7']; ?></label>
                            <input type="radio" name="status" value="inactive" id="orm_bstatus1" <?php if (mysql_result($aData['row'], 0, "status") == "inactive") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus2"><?php echo $lang['db9']; ?></label>
                            <input type="radio" name="status" value="active" id="orm_bstatus2" <?php if (mysql_result($aData['row'], 0, "status") == "active") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus3"><?php echo $lang['dba']; ?></label>
                            <input type="radio" name="status" value="hibernating" id="orm_bstatus3" <?php if (mysql_result($aData['row'], 0, "status") == "hibernating") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus4"><?php echo $lang['dbc']; ?></label>
                            <input type="radio" name="status" value="suspended" id="orm_bstatus4" <?php if (mysql_result($aData['row'], 0, "status") == "suspended") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_breason"><small><?php echo $lang['pag_r']; ?>:</small></label><br />
                            <input type="text" size="30" name="reason" id="orm_breason" />
                          </li>
                        </ul>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_bowner" title="<?php echo strFunx($lang['pagep'], "0101"); ?>"><?php echo $lang['pageo']; ?></label>
                        <input type="text" name="owner" size="20" id="orm_bowner" value="<?php echo strFunx(mysql_result($aData['row'], 0, "owner"), "0101"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_btitle"><?php echo $lang['pageq']; ?></label>
                        <input type="text" name="title" size="30" id="orm_btitle" value="<?php echo strFunx(mysql_result($aData['row'], 0, "title"), "0101"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_bURI"><a href="<?php echo strFunx(mysql_result($aData['row'], 0, "URI"), "0101"); ?>" target="_blank" title="<?php echo strFunx($lang['p___6'], "0101"); ?>"><?php echo $lang['pager']; ?></a></label>
                        <input type="text" name="URI" size="45" id="orm_bURI" value="<?php echo strFunx(mysql_result($aData['row'], 0, "URI"), "0101"); ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_bdescription" title="<?php echo strFunx($lang['paget'], "0101"); ?>"><?php echo $lang['pages']; ?></label>
                        <textarea rows="5" cols="50" name="description" id="orm_bdescription" class="orm_tasans"><?php echo strFunx(mysql_result($aData['row'], 0, "description"), "0101"); ?></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <input type="reset" value="<?php echo $lang['termj']; ?>" />
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
                        <input type="hidden" name="event" value="Admin" />
                        <input type="hidden" name="confirm" value="Confirm" />
                        <input type="hidden" name="admin" value="Edit" />
                        <input type="submit" value="<?php echo $lang['terma']; ?>" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>

              <?php $bkg = 0; ?> 
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['p___5']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <textarea rows="5" cols="45" class="orm_tacode"><?php
                        $code = (strpos($vData['navhtml'], "*") !== 0) ? sprintf($vData['wrap'], $vData['navscript']) : $vData['navscript'];
                        $rep = array($rData['thisURI'], mysql_result($aData['row'], 0, "id"), $vData['ringname'], $vData['ringemail']);
                        $code = str_replace($rData['coderep'], $rep, $code);
                        echo strFunx($code, "0101", false);
                      ?></textarea>
                    </td>
                  </tr>
                </tbody>
              </table>

              <?php $bkg = 0; ?> 
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                  <thead>
                    <tr>
                      <th><?php echo $lang['pag_4']; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><?php echo $lang['pag_5']; ?></label>
                        <strong><?php echo $lData[mysql_result($aData['row'], 0, "navstatus")]; ?></strong><br />
                        <?php if (mysql_result($aData['row'], 0, "navstatus") != "unchecked") printf($lang['pag_6'], dateStamp(mysql_result($aData['row'], 0, "navtime"))); ?> 
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
                        <input type="hidden" name="event" value="Admin" />
                        <input type="hidden" name="admin" value="Check" />
                        <input type="hidden" name="check" value="page" />
                        <input type="submit" value="<?php echo $lang['term8']; ?>" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>

              <?php $bkg = 0; ?> 
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                  <thead>
                    <tr>
                      <th><?php printf($lang['pag_s'], strFunx(mysql_result($aData['row'], 0, "title"), "0101")); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><?php printf($lang['pag_t'], strFunx(mysql_result($aData['row'], 0, "title"), "0101")); ?></label>
                        <span class="orm_warn" style="white-space:nowrap;"><?php echo $lang['pag_9']; ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
                        <input type="hidden" name="event" value="Admin" />
                        <input type="hidden" name="admin" value="Remove" />
                        <input type="submit" value="<?php echo $lang['terme']; ?>" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
              <?php break;

            case "Remove":
              $bkg = 0; ?> 
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                  <thead>
                    <tr>
                      <th><?php echo $lang['pag_u']; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><?php printf($lang['pag_v'], strFunx(mysql_result($aData['row'], 0, "title"), "0101")); ?></label>
                        <span class="orm_warn" style="white-space:nowrap;"><?php echo $lang['pag_w']; ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label for="orm_breason"><?php echo $lang['pag_x']; ?></label>
                        <input type="text" size="30" name="reason" id="orm_breason" />
                      </td>
                    </tr>
                    <tr>
                      <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <label><a href="<?php echo $_SERVER['PHP_SELF']; ?>?Admin"><?php echo $lang['termk']; ?></a></label>
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />
                        <input type="hidden" name="event" value="Admin" />
                        <input type="hidden" name="admin" value="Remove" />
                        <input type="hidden" name="confirm" value="Confirm" />
                        <input type="submit" value="<?php echo $lang['terml']; ?>" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
              <?php break;
          }

          if ($rData['adminls'][0]->anyType()) { ?> 
            <script type="text/javascript"><!-- 
              function orm_accountde(input) {
                var ids = input.split(" ");
                for (var x = 0; x < ids.length; x++) {
                  try {
                    document.getElementById("orm_bid" + ids[x]).selectedIndex = 0;
                  } catch(e) {}
                }
              }
              <?php $depart = 0; ?> 
              var orca_slist = [<?php foreach ($rData['adminls'] as $adminls) {
                for ($x = 0; $x < mysql_num_rows($adminls->rows); $x++) {
                  $thisURI = preg_replace("/^http:\/\/(www\.)?/", "", strFunx(mysql_result($adminls->rows, $x, "URI"), "0010"));
                  $thisURI = (strlen($thisURI) > 40) ? substr($thisURI, 0, 37)."..." : $thisURI;
                  echo (($depart++) ? ",": "")."\n['".mysql_result($adminls->rows, $x, "id")."', '{$adminls->nick}', '".strFunx(mysql_result($adminls->rows, $x, "title"), "0010")."', '$thisURI']";
                }
              } ?>];
              var orca_lsw = 2;
              var orca_ltp = ["ina", "act", "sus", "hib"];

              function orm_lswitch() {
                orca_lsw = (orca_lsw == 2) ? 3 : 2;
                document.getElementById('orca_lsbut').value = (orca_lsw == 2) ? "<?php echo $lang['Page2']; ?>" : "<?php echo $lang['Page3']; ?>";
                for (var x = 0; x < orca_ltp.length; x++) {
                  var orca_tsrt = new Array();
                  for (var y = 0; y < orca_slist.length; y++) if (orca_slist[y][1] == orca_ltp[x]) orca_tsrt[orca_tsrt.length] = orca_slist[y];
                  if (orca_tsrt.length) {
                    orca_tsrt.sort(new Function("a, b", "return (a[" + orca_lsw + "].toLowerCase() > b[" + orca_lsw + "].toLowerCase()) ? 1 : -1;"));
                    var orca_select = document.getElementById('orm_bid' + orca_ltp[x]).options;
                    var orca_selind = document.getElementById('orm_bid' + orca_ltp[x]).value;
                    for (var z = 1; z < orca_select.length; z++) {
                      orca_select[z].value = orca_tsrt[z - 1][0];
                      orca_select[z].text = orca_tsrt[z - 1][orca_lsw];
                      if (orca_select[z].value == orca_selind) document.getElementById('orm_bid' + orca_ltp[x]).selectedIndex = z;
                    }
                  }
                }
              }
            // --></script>

            <?php $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th>
                      <script type="text/javascript"><!--
                        document.write("<input type=\"button\" id=\"orca_lsbut\" value=\"<?php echo $lang['Page2']; ?>\" onclick=\"orm_lswitch();\" />");
                      //--></script>
                      <?php echo $lang['pag_z']; ?> 
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php reset($rData['adminls']);
                  foreach ($rData['adminls'] as $adminls) { 
                    if (mysql_num_rows($adminls->rows)) { ?> 
                      <tr>
                        <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                          <label for="orm_bid<?php echo $adminls->nick; ?>"><?php echo $adminls->title; ?></label>
                          <select name="id<?php echo $adminls->nick; ?>" id="orm_bid<?php echo $adminls->nick; ?>" size="1" onchange="orm_accountde('<?php echo $adminls->nonTypes(); ?>');">
                            <option value="NULL"><?php echo $lang['pag_k']; ?></option>
                            <?php for ($x = 0; $x < mysql_num_rows($adminls->rows); $x++) {
                              ?><option value="<?php echo mysql_result($adminls->rows, $x, "id"); ?>"><?php echo strFunx(mysql_result($adminls->rows, $x, "title"), "0101"); ?></option>
                            <?php } ?> 
                          </select>
                        </td>
                      </tr>
                    <?php }
                  }?> 
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="hidden" name="event" value="Admin" />
                      <input type="hidden" name="admin" value="Edit" />
                      <input type="submit" value="<?php echo $lang['terma']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>
          <?php } ?> 

          <?php if (isset($xData['site']) && is_array($xData['site'])) {
            uasort($xData['site'], create_function('$a, $b', 'if ($a[\'days\'][\'errr\'][3] == $b[\'days\'][\'errr\'][3]) return 0; return ($a[\'days\'][\'errr\'][3] > $b[\'days\'][\'errr\'][3]) ? -1 : 1;'));
            $check = each($xData['site']);
            if ($check[1]['days']['errr'][56]) { 
              reset($xData['site']); ?>
              <script type="text/javascript"><!--
                var orca_ccolumn = 3;

                <?php $depart = 0; reset($xData['site']); ?> 
                var orca_lista = [<?php while (list($key, $value) = each($xData['site'])) if ($value['days']['errr'][56]) echo (($depart++) ? "," : "")."\n['$key', '".strFunx($cData->site[$key]['title'], "0010")."', '{$value['days']['errr'][3]}', '{$value['days']['errr'][14]}', '{$value['days']['errr'][56]}']"; ?>];

                function orca_list(column) {
                  if (orca_ccolumn != column) {
                    document.getElementById("orca_sort" + orca_ccolumn).className = "orm_scol";
                    document.getElementById("orca_sort" + column).className = "orm_scol_on";
                    if (column == 56) {
                      var sorton = 4;
                    } else if (column == 14) {
                      var sorton = 3;
                    } else var sorton = 2;
                    orca_lista.sort(new Function("a, b", "return b[" + sorton + "] - a[" + sorton + "];"));
                    for (var j = 0; j < orca_lista.length; j++) {
                      document.getElementById("orca_elist").rows[j + 2].cells[0].innerHTML = "<a href=\"<?php echo $_SERVER['PHP_SELF']; ?>?Admin&amp;admin=Edit&amp;idact=" + orca_lista[j][0] + "\">" + orca_lista[j][1] + "</a>";
                      for (var k = 1; k < 4; k++) document.getElementById("orca_elist").rows[j + 2].cells[k].innerHTML = orca_lista[j][k + 1];
                    } orca_ccolumn = column;
                  }
                }
              // --></script>

              <table id="orca_elist" border="0" cellpadding="2" cellspacing="0" class="orm_table">
                <thead>
                  <tr>
                    <th colspan="4" class="orm_ttitle">
                      <?php echo $lang['p___s']; ?> 
                    </th>
                  </tr>
                  <tr>
                    <th><?php echo $lang['pageq']; ?></th>
                    <th id="orca_sort3" onclick="return orca_list(3);" class="orm_scol_on"><?php echo $lang['pa__8']; ?></th>
                    <th id="orca_sort14" onclick="return orca_list(14);" class="orm_scol"><?php echo $lang['pa__9']; ?></th>
                    <th id="orca_sort56" onclick="return orca_list(56);" class="orm_scol"><?php echo $lang['pa__a']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $total = 0; $bkg = 1; reset($xData['site']);
                  while (list($key, $value) = each($xData['site'])) {
                    if ($value['days']['errr'][56]) { ?> 
                      <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                        <th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?Admin&amp;admin=Edit&amp;idact=<?php echo $key; ?>"><?php echo $cData->site[$key]['title']; ?></a></th>
                        <td><?php echo $value['days']['errr'][3]; ?></td>
                        <td><?php echo $value['days']['errr'][14]; ?></td>
                        <td><?php echo $value['days']['errr'][56]; ?></td>
                      </tr>
                    <?php }
                  } ?> 
                </tbody>
              </table>
            <?php } 
          }
        }
        break;


      /* ***** Email Ring Members ***************************** */
      case "Email":
        if (!$rData['allcount']) { ?> 
          <h3><?php echo $lang['p___3']; ?></h3>
        <?php } else {

          $bkg = 0; ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
              <thead>
                <tr>
                  <th><?php echo $lang['pa__2']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label><?php echo $lang['pa__3']; ?></label>
                    <ul>
                      <li>
                        <label for="orm_brecipients1"><?php echo $lang['dbd']; ?></label>
                        <input type="radio" name="recipients" value="selected" id="orm_brecipients1"<?php if (isset($_GET['id'])) echo " checked=\"checked\""; ?> />
                      </li>
                      <li>
                        <label for="orm_brecipients2"><?php echo $lang['dbb']; ?></label>
                        <input type="radio" name="recipients" value="all" id="orm_brecipients2" onfocus="document.getElementById('orm_bid').selectedIndex=-1;"<?php if (!isset($_GET['id']) && !$emailRows['active']) echo " checked=\"checked\""; ?> />
                      </li>
                      <li>
                        <label for="orm_brecipients3"><?php echo $lang['db9']; ?></label>
                        <input type="radio" name="recipients" value="active" id="orm_brecipients3" <?php echo $rtyp[($emailRows['active']) ? 1 : 0]; ?><?php if (!isset($_GET['id']) && $emailRows['active']) echo " checked=\"checked\""; ?> />
                      </li>
                      <li>
                        <label for="orm_brecipients4"><?php echo $lang['db7']; ?></label>
                        <input type="radio" name="recipients" value="inactive" id="orm_brecipients4" <?php echo $rtyp[($emailRows['inactive']) ? 1 : 0]; ?> />
                      </li>
                      <li>
                        <label for="orm_brecipients5"><?php echo $lang['dba']; ?></label>
                        <input type="radio" name="recipients" value="hibernating" id="orm_brecipients5" <?php echo $rtyp[($emailRows['hibernating']) ? 1 : 0]; ?> />
                      </li>
                      <li>
                        <label for="orm_brecipients6"><?php echo $lang['dbc']; ?></label>
                        <input type="radio" name="recipients" value="suspended" id="orm_brecipients6" <?php echo $rtyp[($emailRows['suspended']) ? 1 : 0]; ?> />
                      </li>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label for="orm_bid"><?php echo $lang['pa__4']; ?></label>
                    <select name="id[]" id="orm_bid" size="10" multiple="multiple" onfocus="document.getElementById('orm_brecipients1').checked='checked';">
                      <?php $allrows = mysql_query("SELECT `id`, `title`, `status` FROM `{$dData['tablename']}` ORDER BY `id`;");
                      while ($row = mysql_fetch_assoc($allrows)) { ?> 
                        <option value="<?php echo $row['id']; ?>"<?php if (isset($_GET['id']) && $_GET['id'] == $row['id']) echo " selected=\"selected\""; ?>><?php echo $row['id'].") ".strFunx($row['title'], "0101")." | ".$lData[$row['status']]; ?></option>
                      <?php } ?> 
                    </select>
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label for="orm_bsubject"><?php echo $lang['pa__5']; ?></label>
                    <input type="text" name="subject" size="40" id="orm_bsubject" value="<?php echo (isset($_POST['subject'])) ? strFunx($_POST['subject'], "0101") : strFunx(sprintf($lang['pa__6'], $vData['ringname']), "1100"); ?>" />
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label for="orm_bmessage"><?php echo $lang['pa__7']; ?></label>
                    <textarea rows="5" cols="50" name="message" id="orm_bmessage" class="orm_tasans"><?php if (isset($_POST['message'])) echo strFunx($_POST['message'], "0101"); ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <input type="hidden" name="event" value="Email" />
                    <input type="submit" value="<?php echo $lang['termf']; ?>" />
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        <?php }
        break;


      /* ***** Edit Details *********************************** */
      case "Edit":
        switch ($rData['success']) {
          case "Edit-Verify": ?> 
            <p><?php echo preg_replace("/[\n\r]{2,}/", "</p>\n<p>", $lang['html2']); ?></p>
            <?php break;

          case "Edit-Leave":
            $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['pageu']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><?php echo $lang['pagev']; ?></label>
                      <span class="orm_warn" style="white-space:nowrap;"><?php echo $lang['pag_w']; ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><a href="<?php echo $_SERVER['PHP_SELF']; ?>?Edit"><?php echo $lang['termk']; ?></a></label>
                      <input type="hidden" name="event" value="Edit" />
                      <input type="hidden" name="edit" value="Leave" />
                      <input type="hidden" name="confirm" value="Confirm" />
                      <input type="submit" value="<?php echo $lang['terml']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>
            <?php break;

          default:
            $bkg = 0; ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['pagex']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><?php echo $lang['p___7']; ?></label>
                      <strong><big><?php echo $uData['id']; ?></big></strong>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><?php echo $lang['Page4']; ?></label>
                      <strong><small><?php if ($uData['joindate'] > $vData['joindate']) {
                        echo dateStamp($uData['joindate']);
                      } else printf($lang['Page5'], dateStamp($vData['joindate'])); ?></small></strong>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bpass1" title="<?php echo strFunx($lang['pagem'], "0101"); ?>"><?php echo $lang['pagey']; ?></label>
                      <div>
                        <label for="orm_bpassold"><?php echo $lang['pagez']; ?>:</label>
                        <input type="password" name="passold" size="10" id="orm_bpassold" /><br />
                        <input type="password" name="pass1" size="10" id="orm_bpass1" />
                        <input type="password" name="pass2" size="10" id="orm_bpass2" />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bpass1"><?php echo $lang['pag_1']; ?></label>
                      <?php if ($uData['logged'] == 2) { ?> 
                        <ul>
                          <li>
                            <label for="orm_bstatus1"><?php echo $lang['db7']; ?></label>
                            <input type="radio" name="status" value="inactive" id="orm_bstatus1" <?php if ($uData['status'] == "inactive") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus2"><?php echo $lang['db9']; ?></label>
                            <input type="radio" name="status" value="active" id="orm_bstatus2" <?php if ($uData['status'] == "active") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus3"><?php echo $lang['dba']; ?></label>
                            <input type="radio" name="status" value="hibernating" id="orm_bstatus3" <?php if ($uData['status'] == "hibernating") echo "checked=\"checked\" "; ?>/>
                          </li>
                          <li>
                            <label for="orm_bstatus4"><?php echo $lang['dbc']; ?></label>
                            <input type="radio" name="status" value="suspended" id="orm_bstatus4" <?php if ($uData['status'] == "suspended") echo "checked=\"checked\" "; ?>/>
                          </li>
                        </ul>
                      <?php } else {
                        switch ($uData['status']) {
                          case "inactive": ?> 
                            <strong><?php echo $lang['pag_2']; ?></strong>
                            <?php break;

                          case "active":
                          case "hibernating": ?> 
                            <ul>
                              <li>
                                <label for="orm_bstatus1"><?php echo $lang['db9']; ?></label>
                                <input type="radio" name="status" value="active" id="orm_bstatus1" <?php if ($uData['status'] == "active") echo "checked=\"checked\" "; ?>/>
                              </li>
                              <li>
                                <label for="orm_bstatus2"><?php echo $lang['dba']; ?></label>
                                <input type="radio" name="status" value="hibernating" id="orm_bstatus2" <?php if ($uData['status'] == "hibernating") echo "checked=\"checked\" "; ?>/>
                              </li>
                            </ul>
                            <?php break;

                          case "suspended": ?>
                            <strong class="orm_warn"><?php echo $lang['dbc']; ?></strong>
                            <?php break;
                        }
                      } ?> 
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bowner" title="<?php echo strFunx($lang['pagep'], "0101"); ?>"><?php echo $lang['pageo']; ?></label>
                      <input type="text" name="owner" size="20" id="orm_bowner" value="<?php echo strFunx($uData['owner'], "1100"); ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_btitle"><?php echo $lang['pageq']; ?></label>
                      <input type="text" name="title" size="30" id="orm_btitle" value="<?php echo strFunx($uData['title'], "0101"); ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bURI"><a href="<?php echo strFunx($uData['URI'], "0101"); ?>" target="_blank" title="<?php echo strFunx($lang['p___6'], "0101"); ?>"><?php echo $lang['pager']; ?></a></label>
                      <input type="text" name="URI" size="45" id="orm_bURI" value="<?php echo strFunx($uData['URI'], "0101"); ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bdescription" title="<?php echo strFunx($lang['paget'], "0101"); ?>"><?php echo $lang['pages']; ?></label>
                      <textarea rows="5" cols="50" name="description" id="orm_bdescription" class="orm_tasans"><?php echo strFunx($uData['description'], "0101"); ?></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="reset" value="<?php echo $lang['termj']; ?>" />
                      <input type="hidden" name="event" value="Edit" />
                      <input type="hidden" name="edit" value="Edit" />
                      <input type="submit" value="<?php echo $lang['terma']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>

            <?php $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['pag_3']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label for="orm_bemail"><?php echo $lang['pagen']; ?></label>
                      <input type="text" name="email" size="30" id="orm_bemail" value="<?php echo strFunx($uData['email'], "0101"); ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="reset" value="<?php echo $lang['termj']; ?>" />
                      <input type="hidden" name="event" value="Edit" />
                      <input type="hidden" name="edit" value="Change" />
                      <input type="submit" value="<?php echo $lang['term7']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>

            <?php $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['pag_4']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><?php echo $lang['pag_5']; ?></label>
                      <strong><?php echo $lData[$uData['navstatus']]; ?></strong><br />
                      <?php if ($uData['navstatus'] != "unchecked") printf($lang['pag_6'], dateStamp($uData['navtime'])); ?> 
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="hidden" name="event" value="Edit" />
                      <input type="hidden" name="edit" value="Check" />
                      <input type="submit" value="<?php echo $lang['term8']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>

            <?php $bkg = 0; ?> 
            <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
              <thead>
                <tr>
                  <th><?php echo $lang['pag_7']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <textarea rows="5" cols="45" class="orm_tacode"><?php
                      $code = (strpos($vData['navhtml'], "*") !== 0) ? sprintf($vData['wrap'], $vData['navscript']) : $vData['navscript'];
                      $rep = array($rData['thisURI'], $uData['id'], $vData['ringname'], $vData['ringemail']);
                      $code = str_replace($rData['coderep'], $rep, $code);
                      echo strFunx($code, "0101", false);
                    ?></textarea>
                  </td>
                </tr>
              </tbody>
            </table>

            <?php $bkg = 0; ?> 
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
                <thead>
                  <tr>
                    <th><?php echo $lang['pag_8']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <label><?php echo $lang['pag_8']; ?></label>
                      <span class="orm_warn" style="white-space:nowrap;"><?php echo $lang['pag_9']; ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <input type="hidden" name="event" value="Edit" />
                      <input type="hidden" name="edit" value="Leave" />
                      <input type="submit" value="<?php echo $lang['term9']; ?>" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>

        <?php }
        break;


      /* ***** Statistics Part Deux *************************** */
      case "Stats":
        if (!count($cData->ids)) { ?> 
          <h3><?php echo ($rData['actcount']) ? $lang['Page1'] : $lang['p___2']; ?></h3>
        <?php } else {

          if ($xData['idok']) { ?> 
            <table border="0" cellpadding="2" cellspacing="0" class="orm_table">
              <thead>
                <tr>
                  <th colspan="4" class="orm_ttitle">
                    <?php if (count($cData->ids) > 1) { ?> 
                      <script type="text/javascript"><!--
                        document.write("<select size=\"1\" onchange=\"window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?Stats&id=' + this.value;\">");
                        <?php foreach ($cData->ids as $actid) { ?> 
                          document.write("  <option value=\"<?php echo $actid; ?>\"<?php if ($actid == $xData['this']) echo " selected=\\\"selected\\\""; ?>><?php echo $actid; ?></option>");
                        <?php } ?> 
                        document.write("</select>");
                      // --></script>
                    <?php } ?> 
                    <a href="<?php echo strFunx(mysql_result($xData['thisURI'], 0, "URI"), "0101"); ?>" title="<?php echo strFunx($lang['p___6'], "0101"); ?>"><?php echo strFunx($cData->site[$xData['this']]['title'], "0101"); ?></a>
                  </th>
                </tr>
                <tr>
                  <td colspan="4">
                    <?php echo $lang['Page4']; ?>: 
                    <?php if (mysql_result($xData['thisURI'], 0, "joindate") > $vData['joindate']) {
                      echo dateStamp(mysql_result($xData['thisURI'], 0, "joindate"));
                    } else printf($lang['Page5'], dateStamp($vData['joindate'])); ?> 
                  </td>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <th><?php echo $lang['pa__8']; ?></th>
                  <th><?php echo $lang['pa__9']; ?></th>
                  <th><?php echo $lang['pa__a']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php statList(false, false, $lang['pa__b'], true, $xData['site'][$xData['this']]['days']['hits']['total'], $xData['ring']['days']['hits']['total'], true); ?> 
                <?php statList(false, true, $lang['dbh'], false, $xData['site'][$xData['this']]['days']['hits']['prev'], $xData['ring']['days']['hits']['prev'], false); ?> 
                <?php statList(false, true, $lang['dbi'], false, $xData['site'][$xData['this']]['days']['hits']['next'], $xData['ring']['days']['hits']['next'], false); ?> 
                <?php statList(false, true, $lang['dbg'], false, $xData['site'][$xData['this']]['days']['hits']['rand'], $xData['ring']['days']['hits']['rand'], false); ?> 
                <?php statList(false, true, $lang['dbk'], false, $xData['site'][$xData['this']]['days']['hits']['site'], $xData['ring']['days']['hits']['site'], false); ?> 
                <?php statList(true, false, "", false, "", "", false); ?> 
                <?php statList(false, false, $lang['pa__e'], true, $xData['site'][$xData['this']]['days']['clks']['total'], $xData['ring']['days']['clks']['total'], true); ?> 
                <?php statList(false, true, $lang['dbh'], false, $xData['site'][$xData['this']]['days']['clks']['prev'], $xData['ring']['days']['clks']['prev'], false); ?> 
                <?php statList(false, true, $lang['dbi'], false, $xData['site'][$xData['this']]['days']['clks']['next'], $xData['ring']['days']['clks']['next'], false); ?> 
                <?php statList(false, true, $lang['dbg'], false, $xData['site'][$xData['this']]['days']['clks']['rand'], $xData['ring']['days']['clks']['rand'], false); ?> 
                <?php statList(true, false, "", false, "", "", false); ?> 
                <?php statList(false, false, $lang['p___b'], true, $xData['site'][$xData['this']]['days']['errr'], $xData['ring']['days']['errr'], true); ?> 
              </tbody>
            </table>
          <?php } ?> 

          <script type="text/javascript"><!--
            function sprintf(num, decimalNum, bolLeadingZero, bolParens) {
              var tmpNum = num;
              tmpNum *= Math.pow(10, decimalNum);
              tmpNum = Math.round(tmpNum);
              tmpNum /= Math.pow(10, decimalNum);
              var tmpStr = new String(tmpNum);
              if (!bolLeadingZero && num < 1 && num > -1 && num !=0) 
                tmpStr = (num > 0) ? tmpStr.substring(1, tmpStr.length) : "-" + tmpStr.substring(2, tmpStr.length);                        
              if (bolParens && num < 0) tmpStr = "(" + tmpStr.substring(1, tmpStr.length) + ")";
              return tmpStr;
            }
            function orcaGraph(id, rec, gen, k, title, digits) {
              this.id = id;
              this.limit = rec.length;
              this.arr = [new Array(this.limit), new Array(this.limit)];
              this.max = [0, 0];
              for (var x = 0; x < rec.length; x++) {
                if (this.max[0] < rec[x]) this.max[0] = rec[x];
                this.arr[0][x] = rec[x];
                if (this.max[1] < gen[x]) this.max[1] = gen[x];
                this.arr[1][x] = gen[x];
              }
              this.k = k;
              this.title = title;
              this.digits = digits;
            }
            var orca_ = new Array();
            var orca_daynames = [<?php foreach($sData['day'] as $day) echo "\"$day\", "; ?>""];
            var orca_t = 0;

            <?php $graphs = array();
            if ($xData['idok']) {
              $graphs[] = new orcaGraph("site", $cData->site[$xData['this']]['days']->hits['total'], $cData->site[$xData['this']]['days']->clks['total'], gmdate("w", $cData->now), '$k % 7', "j", strFunx($cData->site[$xData['this']]['title'], "0101"), 0, array(array(28, "l", $lang['pa__h']), array(28, "r", "{$lang['pa__i']} ({$sData['day'][gmdate("w", time() + $vData['tzoffset'] * 3600)]})")), 5, '$graph->arr[0][$j]'); ?> 
              var orca_rec = [<?php for ($j = 55; $j >= 0; $j--) echo $cData->site[$xData['this']]['days']->hits['total'][$j].(($j) ? ", " : ""); ?>];
              var orca_gen = [<?php for ($j = 55; $j >= 0; $j--) echo $cData->site[$xData['this']]['days']->clks['total'][$j].(($j) ? ", " : ""); ?>];
              orca_[orca_.length] = new orcaGraph("site", orca_rec, orca_gen, 0, "orca_[orca_x].arr[orca_t][orca_k]", 0);
            <?php } ?> 

            <?php $graphs[] = new orcaGraph("ring", $cData->ring['days']->hits['total'], $cData->ring['days']->clks['total'], gmdate("w", $cData->now), '$k % 7', "j", $lang['pa__j'], 0, array(array(28, "l", $lang['pa__h']), array(28, "r", "{$lang['pa__i']} ({$sData['day'][gmdate("w", time() + $vData['tzoffset'] * 3600)]})")), 5, '$graph->arr[0][$j]'); ?> 
            var orca_rec = [<?php for ($j = 55; $j >= 0; $j--) echo $cData->ring['days']->hits['total'][$j].(($j) ? ", " : ""); ?>];
            var orca_gen = [<?php for ($j = 55; $j >= 0; $j--) echo $cData->ring['days']->clks['total'][$j].(($j) ? ", " : ""); ?>];
            orca_[orca_.length] = new orcaGraph("ring", orca_rec, orca_gen, 0, "orca_[orca_x].arr[orca_t][orca_k]", 0);

            <?php $graphs[] = new orcaGraph("day", $xData['ring']['days']['avg']['hits'], $xData['ring']['days']['avg']['clks'], -gmdate("w", $cData->now) - 1, '$j != 6', "k", sprintf($lang['pa__k'], $vData['timezone']), 1, array(array(3, "l", $lang['pa__u']), array(4, "r", $lang['p___1'])), 25, 'sprintf("%s: %01.1f", $sData[\'day\'][$day], $graph->arr[0][$k])'); ?> 
            var orca_rec = [<?php for ($j = 6; $j >= 0; $j--) echo $xData['ring']['days']['avg']['hits'][$j].(($j) ? ", " : ""); ?>];
            var orca_gen = [<?php for ($j = 6; $j >= 0; $j--) echo $xData['ring']['days']['avg']['clks'][$j].(($j) ? ", " : ""); ?>];
            orca_[orca_.length] = new orcaGraph("day", orca_rec, orca_gen, <?php echo gmdate("w", $cData->now); ?> + 1, "orca_daynames[(orca_j > 6) ? 0 : orca_j] + \": \" + sprintf(orca_[orca_x].arr[orca_t][orca_k], orca_[orca_x].digits, false, false)", 1);

            <?php $graphs[] = new orcaGraph("hour", $xData['ring']['hours']['avg']['hits'], $xData['ring']['hours']['avg']['clks'], -gmdate("G", $cData->now) - 1, '($j + 1) % 3', "k", "{$lang['p___d']} ({$vData['timezone']})", 1, array(array(6, "l", "0:00"), array(6, "r", "12:00"), array(6, "l", $lang['p___c']), array(6, "r", "23:59")), 10, 'sprintf((23 - $j).":00 - %01.1f", $graph->arr[0][$k])'); ?> 
            var orca_rec = [<?php for ($j = 23; $j >= 0; $j--) echo $xData['ring']['hours']['avg']['hits'][$j].(($j) ? ", " : ""); ?>];
            var orca_gen = [<?php for ($j = 23; $j >= 0; $j--) echo $xData['ring']['hours']['avg']['clks'][$j].(($j) ? ", " : ""); ?>];
            orca_[orca_.length] = new orcaGraph("hour", orca_rec, orca_gen, <?php echo gmdate("G", $cData->now); ?> + 1, "(orca_j) + \":00 - \" + sprintf(orca_[orca_x].arr[orca_t][orca_k], orca_[orca_x].digits, false, false)", 1);

            function hitclick() {
              document.getElementById("orca_gtitle").innerHTML = (orca_t) ? "<?php echo $lang['pa__g']; ?>" : "<?php echo $lang['pa__f']; ?>";
              document.getElementById("orca_hc").value = (orca_t) ? "<?php echo $lang['pa__e']; ?>" : "<?php echo $lang['pa__b']; ?>";
              orca_t = (orca_t) ? 0 : 1;

              for (var orca_x = 0; orca_x < orca_.length; orca_x++) {
                var orca_row = document.getElementById("orca_" + orca_[orca_x].id).rows[0];
                orca_row.cells[0].innerHTML = sprintf(Math.max(0, orca_[orca_x].max[orca_t]), orca_[orca_x].digits, false, false);
                for (var orca_j = 0; orca_j < orca_[orca_x].limit; orca_j++) {
                  var orca_k = orca_j - orca_[orca_x].k;
                  if (orca_k < 0) orca_k += orca_[orca_x].limit;
                  var orca_bar = orca_row.cells[orca_j + 1].getElementsByTagName("div");
                  orca_bar[0].style.height = (orca_[orca_x].max[orca_t]) ? Math.max(1, parseInt(orca_[orca_x].arr[orca_t][orca_k] * 100 / orca_[orca_x].max[orca_t])) + "px" : "1px";
                  orca_bar[0].className = (orca_[orca_x].max[orca_t] && (orca_[orca_x].arr[orca_t][orca_k] == orca_[orca_x].max[orca_t])) ? "orm_gmax" : "";
                  eval("orca_bar[0].title = \"\" + " + orca_[orca_x].title + ";");
                }
              }
            }
          //--></script>

          <table border="0" cellpadding="3" cellspacing="0" class="orm_table">
            <thead>
              <tr>
                <th class="orm_ttitle">
                  <script type="text/javascript"><!--
                    document.write("<input type=\"button\" id=\"orca_hc\" value=\"<?php echo $lang['pa__e']; ?>\" onclick=\"hitclick();\" />");
                  //--></script>
                  <span id="orca_gtitle"><?php echo $lang['pa__g']; ?></span>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($graphs as $graph) {
                $scale = ($graph->max[0]) ? 100 / $graph->max[0] : -1; ?> 
                <tr><td><?php echo $graph->title; ?></td></tr>
                <tr>
                  <td>
                    <table id="<?php echo $graph->id; ?>" cellpadding="0" cellspacing="0" border="0" class="orm_graph">
                      <tr class="orm_chartrow">
                        <th rowspan="2"><?php echo ($graph->digits) ? sprintf("%01.1f", (string)$graph->max[0]) : (string)$graph->max[0]; ?></th>
                        <?php for ($j = ($graph->limit - 1), $day = 0; $j >= 0; $j--, $day++) {
                          $k = $j - $graph->k;
                          if ($k >= $graph->limit) $k -= $graph->limit;
                          if ($k < 0) $k += $graph->limit;
                          $height = max(1, (int)($graph->arr[0][eval("return \${$graph->jk};")] * $scale));
                          $max = ($graph->max[0] && $graph->arr[0][eval("return \${$graph->jk};")] == $graph->max[0]) ? true : false; ?> 
                          <td<?php if (!eval("return ({$graph->kmod});")) echo " class=\"orm_sb\"";
                            ?>><div<?php if ($max) echo " class=\"orm_gmax\""; ?> style="height:<?php echo $height; ?>px;width:<?php echo $graph->width; ?>px;" title="<?php echo eval("return ({$graph->barts});");
                          ?>"></div></td><?php
                        } ?> 
                      </tr>
                      <tr class="orm_gbase">
                        <?php foreach ($graph->base as $base) { ?> 
                          <td colspan="<?php echo $base[0]; ?>" class="orm_gb<?php echo $base[1]; ?>"><?php echo $base[2]; ?></td>
                        <?php } ?> 
                      </tr>
                    </table>
                  </td>
                </tr>
              <?php } ?> 
            </tbody>
          </table>

          <?php ob_start(); 
          if (count($cData->ids) > 1) { ?> 
            <script type="text/javascript"><!--
              var orca_ctype = "hits";
              var orca_ccolumn = 56;

              <?php $depart = 0; reset($xData['site']);
              if ($xData['idok']) $cData->site[$xData['this']]['title'] = "<strong>{$cData->site[$xData['this']]['title']}</strong>"; ?> 
              var orca_lista = [<?php while (list($key, $value) = each($xData['site'])) echo (($depart++) ? "," : "")."\n['$key', '".strFunx($cData->site[$key]['title'], "0010")."', '{$value['days']['hits']['total'][3]}', '{$value['days']['hits']['total'][14]}', '{$value['days']['hits']['total'][56]}', '{$value['days']['clks']['total'][3]}', '{$value['days']['clks']['total'][14]}', '{$value['days']['clks']['total'][56]}']"; ?>];

              function hclist() {
                document.getElementById("orca_htitle").innerHTML = (orca_ctype == "clks") ? "<?php printf($lang['pa__l'], $sData['toplimit']); ?>" : "<?php printf($lang['pa__n'], $sData['toplimit']); ?>";
                document.getElementById("orca_hclist").value = (orca_ctype == "clks") ? "<?php echo $lang['pa__e']; ?>" : "<?php echo $lang['pa__b']; ?>";
                orca_ctype = (orca_ctype == "clks") ? "hits" : "clks";
                orca_list(orca_ccolumn, true);
              }

              function orca_list(column, force) {
                if (orca_ccolumn != column || force) {
                  document.getElementById("orca_sort" + orca_ccolumn).className = "orm_scol";
                  document.getElementById("orca_sort" + column).className = "orm_scol_on";
                  var orca_tinc = (orca_ctype == "hits") ? 1 : 4;
                  var orca_l = (orca_lista.length > <?php echo $sData['toplimit']; ?>) ? <?php echo $sData['toplimit']; ?> : orca_lista.length;
                  if (column == 56) {
                    var sorton = 3 + orca_tinc;
                  } else if (column == 14) {
                    var sorton = 2 + orca_tinc;
                  } else var sorton = 1 + orca_tinc;
                  orca_lista.sort(new Function("a, b", "return b[" + sorton + "] - a[" + sorton + "];"));
                  for (var j = 0; j < orca_l; j++) {
                    var inhtml  = "<a href=\"<?php echo $_SERVER['PHP_SELF']; ?>?Stats&amp;id=" + orca_lista[j][0] + "\" title=\"<?php echo strFunx($lang['page7'], "0101"); ?>\"><img src=\"<?php echo $vData['staticon']; ?>\" alt=\"<?php echo $lang['page7']; ?>\" /></a> ";
                        inhtml += "<a href=\"<?php echo $_SERVER['PHP_SELF']; ?>?Go&amp;Site&amp;" + orca_lista[j][0] + "\">" + orca_lista[j][1] + "</a>";
                      document.getElementById("orca_list").rows[j + 2].cells[0].innerHTML = inhtml;
                    for (var k = 1; k < 4; k++) document.getElementById("orca_list").rows[j + 2].cells[k].innerHTML = orca_lista[j][k + orca_tinc];
                  } orca_ccolumn = column;
                }
              }
            // --></script>

            <?php uasort($xData['site'], create_function('$a, $b', 'if ($a[\'days\'][\'hits\'][\'total\'][56] == $b[\'days\'][\'hits\'][\'total\'][56]) return 0; return ($a[\'days\'][\'hits\'][\'total\'][56] > $b[\'days\'][\'hits\'][\'total\'][56]) ? -1 : 1;')); ?> 
            <table id="orca_list" border="0" cellpadding="3" cellspacing="0" class="orm_table">
              <thead>
                <tr>
                  <th colspan="4" class="orm_ttitle">
                    <script type="text/javascript"><!--
                      document.write("<input type=\"button\" id=\"orca_hclist\" value=\"<?php echo $lang['pa__e']; ?>\" onclick=\"hclist();\" />");
                    //--></script>
                    <span id="orca_htitle"><?php printf($lang['pa__l'], $sData['toplimit']); ?></span>
                  </th>
                </tr>
                <tr>
                  <th><?php echo $lang['pageq']; ?></th>
                  <th id="orca_sort3" onclick="return orca_list(3, false);" class="orm_scol"><?php echo $lang['pa__8']; ?></th>
                  <th id="orca_sort14" onclick="return orca_list(14, false);" class="orm_scol"><?php echo $lang['pa__9']; ?></th>
                  <th id="orca_sort56" onclick="return orca_list(56, false);" class="orm_scol_on"><?php echo $lang['pa__a']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0; $bkg = 1; reset($xData['site']);
                while (list($key, $value) = each($xData['site'])) {
                  if ($total++ < $sData['toplimit']) { ?> 
                    <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <th>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?Stats&amp;id=<?php echo $key; ?>" title="<?php echo strFunx($lang['page7'], "0101"); ?>"><img src="<?php echo $vData['staticon']; ?>" alt="<?php echo $lang['page7']; ?>" /></a>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?Go&amp;Site&amp;<?php echo $key; ?>"><?php echo $cData->site[$key]['title']; ?></a>
                      </th>
                      <td><?php echo $value['days']['hits']['total'][3]; ?></td>
                      <td><?php echo $value['days']['hits']['total'][14]; ?></td>
                      <td><?php echo $value['days']['hits']['total'][56]; ?></td>
                    </tr>
                  <?php }
                } ?> 
              </tbody>
            </table>
          <?php }

          list($key, $value) = each($cData->ring['browsers']);
          if ($value[1]) { ?> 
            <table border="0" cellpadding="3" cellspacing="0" class="orm_table">
              <thead>
                <tr>
                  <th colspan="2" class="orm_ttitle">
                    <?php printf($lang['p___f'], 5); ?> 
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php $bkg = 0; reset($cData->ring['browsers']);
                while (list($key, $value) = each($cData->ring['browsers'])) { 
                  if ($value[1]) { ?> 
                    <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                      <th><?php echo $key; ?></th>
                      <td><?php echo $value[1]; ?></td>
                    </tr><?php
                  }
                } ?> 
              </tbody>
            </table>
          <?php }

          if ($vData['statcachetype'] != "none") {
            $now = time() - $vData['statdate'];
            $hours = floor($now / 3600);
              $now %= 3600;
            $minutes = floor($now / 60);
            $seconds = $now % 60; ?> 
            <table border="0" cellpadding="3" cellspacing="0" class="orm_table">
              <thead>
                <tr>
                  <th class="orm_ttitle"><?php echo $lang['p___m']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?php echo $lang['p___n']; ?>: &nbsp;
                    <span id="orm_hours" title="<?php echo strFunx($lang['p___o'], "0101"); ?>"><?php printf("%02s", $hours); ?></span>:<span
                          id="orm_minutes" title="<?php echo strFunx($lang['p___p'], "0101"); ?>"><?php printf("%02s", $minutes); ?></span>:<span
                          id="orm_seconds" title="<?php echo strFunx($lang['p___q'], "0101"); ?>"><?php printf("%02s", $seconds); ?></span>

                    <script type="text/javascript"><!--
                      var orca_atime = ["<?php echo $hours; ?>", "<?php echo $minutes; ?>", "<?php echo $seconds; ?>"];
                      var orca_atype = ["hours", "minutes", "seconds"];
                      function orca_incrTime() {
                        setTimeout("orca_incrTime();", 990);
                        if (++orca_atime[2] > 59) {
                          orca_atime[2] = 0;
                          orca_atime[1]++;
                        }
                        if (orca_atime[1] > 59) {
                          orca_atime[1] = 0;
                          orca_atime[0]++;
                        }
                        for (var x = 0; x < orca_atype.length; x++)
                          document.getElementById('orm_' + orca_atype[x]).firstChild.nodeValue = ((orca_atime[x] < 10) ? "0" : "") + orca_atime[x];
                      }
                      setTimeout("orca_incrTime();", 0);
                    // --></script>
                  </td>
                </tr>
              </tbody>
            </table>
          <?php }

          $rData["ob"] = ob_get_contents();
          ob_end_clean();
        }
        break;


      /* ***** Help ******************************************* */
      case "Help": ?> 
        <div id="orm_help">
          <?php readfile($vData['helpfile']); ?> 
        </div>
        <?php break;


      /* ***** Blank ****************************************** */
      case "Blank":
        break;


      /* ***** Ring Hub *************************************** */
      default:
        if ($rData['success'] == "Login-Forget") {
          $bkg = 0; ?> 
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="orm_box">
              <thead>
                <tr>
                  <th><?php echo $lang['pa__o']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <label for="orm_bforget"><?php echo $lang['pa__p']; ?></label><br />
                    <?php echo $lang['pa__q']; ?><br />
                    <input type="text" name="forget" size="30" id="orm_bforget" />
                  </td>
                </tr>
                <tr>
                  <td<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
                    <input type="hidden" name="event" value="Login" />
                    <input type="submit" value="<?php echo $lang['termc']; ?>" />
                  </td>
                </tr>
              </tbody>
            </table>
          </form>

        <?php } else if ($hubCount) { ?> 
          <ul id="orm_site">
            <?php while (1) {
              while ($row = mysql_fetch_assoc($list)) {
                $listed++; ?><li>
                  <h2>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?Stats&amp;id=<?php echo $row['id']; ?>" title="<?php echo strFunx($lang['page7'], "0101"); ?>"><img src="<?php echo $vData['staticon']; ?>" alt="<?php echo $lang['page7']; ?>" /></a>
                    <a href="<?php echo $_SERVER['PHP_SELF']."?Go&amp;Site&amp;{$row['id']}"; ?>"><?php echo strFunx($row['title'], "0101"); ?></a>
                  </h2>
                  <?php if ($y = strFunx($row['description'], "0101")) { ?> 
                    <p>
                      <?php echo $y; ?> 
                    </p>
                  <?php } ?> 
                </li>
              <?php }
              if ($listed >= $hubCount || $listed >= $pData['end']) break; ?> 
              <li class="orm_hr"><hr /></li>
              <?php $list = mysql_query("SELECT `id`, `title`, `description` FROM `{$dData['tablename']}` WHERE `status`='active' ORDER BY `order` LIMIT 0, ".($pData['end'] - $listed).";");
            } ?> 
          </ul>

          <?php if ($hubCount > $vData['sitelimit']) { ?> 
            <div id="orm_pagin">
              <div id="orm_pagin_prev">
                <?php $pstart = ($pData['start'] - $vData['sitelimit'] < 0) ? $pData['start'] - $vData['sitelimit'] + $hubCount : $pData['start'] - $vData['sitelimit']; ?> 
                <a href="<?php echo $_SERVER['PHP_SELF']."?start=$pstart"; ?>" title="<?php echo strFunx($lang['pa__r'], "0101"); ?>">&lt;&lt; <?php echo $lang['dbh']; ?></a>
              </div>
              <div id="orm_pagin_next">
                <?php $nstart = ($pData['start'] + $vData['sitelimit'] > $hubCount) ? $pData['start'] + $vData['sitelimit'] - $hubCount : $pData['start'] + $vData['sitelimit']; ?> 
                <a href="<?php echo $_SERVER['PHP_SELF']."?start=$nstart"; ?>" title="<?php echo strFunx($lang['pa__s'], "0101"); ?>"><?php echo $lang['dbi']; ?> &gt;&gt;</a>
              </div>
              <div id="orm_pagin_page">
                <?php for ($x = 0; $x < ceil($hubCount / $vData['sitelimit']); $x++) {
                  if ($x * $vData['sitelimit'] == $pData['start']) { ?> 
                    <strong><?php echo ($x + 1); ?></strong>
                  <?php } else { ?> 
                    <a href="<?php echo $_SERVER['PHP_SELF']."?start=".($x * $vData['sitelimit']); ?>" title="<?php strFunx(printf($lang['pa__t'], $x + 1), "0101"); ?>"><?php echo ($x + 1); ?></a>
                  <?php }
                } ?> 
              </div>
            </div>
          <?php }

        } else { ?> 
          <h2><?php printf($lang['html5'], $rData['UA']); ?></h2>
          <p>
            <?php echo preg_replace("/[\n\r]{2,}/", "</p>\n<p>", $lang['html6']); ?> 
          </p>
        <?php }
    } ?>

  </div>

  <?php if (isset($rData["ob"])) echo $rData["ob"]; ?>

  <div style="text-align:center;font:italic 80% Arial,sans-serif;clear:right;">
    <hr style="width:60%;margin:10px auto 2px auto;" />
    An <a href="http://www.greywyvern.com/" title="GreyWyvern.com">Orca</a> Script
  </div>

</div>
