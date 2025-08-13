          </td>
          <td width="2%" valign="top">&nbsp;</td>
          <td width="29%" valign="top" bgcolor="#FFFFFF">
			<table width="100%" border="0" bgcolor="#EFD7B5"><tr><td>
          	<img src="blank.gif" width="1" height="10"><br>
			<div align="center">
              <center>
              <table border="0" cellspacing="0" width="95%" cellpadding="0" height="48">
                <tr>
                  <td width="16%" height="28" valign="top">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="28" valign="top"><b>
                  <span class="SideHeader">CONTENT BY TOPIC</span></td>
                </tr>
                <tr>
                  <td width="16%" height="19">&nbsp;</td>
                  <td width="84%" height="19">
					<?php ShowTopics(); ?>
                  </td>
                </tr>
                <tr>
                  <td width="16%" height="28" valign="top">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="28" valign="top"><b>
                  <span class="SideHeader">FEATURED BOOK</span></td>
                </tr>
                <tr>
                  <td width="16%" height="19">&nbsp;</td>
                  <td width="84%" height="19">
					<table width="100%" border="0"><?php ShowFeaturedBook(false); ?></table>
                  </td>
                </tr>
                <tr>
                  <td width="16%" valign="top">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" valign="middle">
					<span class="SideHeader">OUR NEWSLETTER</span>
                  </td>
                </tr>
               <form name="frmEmail" action="newsletter.php" method="post">
                <tr>
                  <td width="16%" height="19">&nbsp;</td>
                  <td width="84%" height="19">
				  	<img src="blank.gif" width="1" height="10"><br>
				  	<input size="12" type="text" name="strEmail" value="YOUR EMAIL" onClick="if(this.value == 'YOUR EMAIL') { this.value = ''; }"> <input type="submit" value="Go!"></font><font size="2" face="Verdana"><br>
				  	<span class="Text1">
				  		<input checked type="radio" name="submit" value="1"> Subscribe<br>
				  		<input type="radio" name="submit" value="0"> Unsubscribe<br>
				  		<img src="blank.gif" width="1" height="5">
				  	</span>
				  </td>
                </tr>
               </form>
                <tr>
                  <td width="16%" height="31" valign="bottom">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" height="31" valign="bottom"><b>
					<span class="SideHeader">VOTING POLL</span>
				  </td>
                </tr>
				<?php ShowVotingPoll(false); ?>
                <tr>
                  <td width="16%" valign="middle">
                  <img border="0" src="images/oar1.gif" width="29" height="22"></td>
                  <td width="84%" valign="middle">
					<span class="SideHeader">BOOKMARK US!</span>
				  </td>
                </tr>
                <form>
                <tr>
                  <td width="16%" height="19">&nbsp;</td>
                  <td width="84%" height="19">
					<img src="blank.gif" width="1" height="10"><table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" cellpadding="0" height="38">
					  <tr>
					    <td width="15%" height="22">
					    <img border="0" src="images/folder.gif" width="15" height="15"></td>
					    <td width="85%" height="22">
							<a href="javascript:window.external.addFavorite('<?php echo $siteURL; ?>', '<?php echo $siteName; ?>')"><span class="Link2">Add To Favorites</span></a>
					    </td>
					  </tr>
					  <tr>
					    <td width="15%" height="16">
					    <img border="0" src="images/folder.gif" width="15" height="15"></td>
					    <td width="85%" height="16">
							<a style="behavior:url(#default#homepage)" onClick="setHomePage('<?php echo $siteURL; ?>')" href="#"><span class="Link2">Make Home Page</span></a>
					    </td>
					  </tr>
					</table>
                  </td>
                </tr>
                </form>
              </table>
              </center>
            </div>
          	&nbsp;
			</td></tr></table>
          </td>
        </tr>
      </table>
      </td>
    </tr>
	<tr>
      <td width="766" height="25" colspan="3" bgcolor="#636563" background="images/gbgb4.gif">
		<p align="center">
			<span class="Text2">
				All content &copy; <?php echo date("Y"); ?> <?php echo "<a href='mailto:$adminEmail'><span class='Link3'>$adminName</span></a>"; ?>, <?php echo $siteName; ?>.
				Site Powered By <!--CyKuH [WTN]--><span class="Link3">SiteWorksPro <?php echo $appVersion; ?></span>.
			</span>
	  </td>
    </tr>
  </table>
  </center>
</div>

</body>

</html>