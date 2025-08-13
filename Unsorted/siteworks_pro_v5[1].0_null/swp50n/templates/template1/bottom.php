            <?php
            
				// Template one
            
            ?>
            </td>
            <td width="40%" background="images/gbgrhs.gif" valign="top">
            <div align="center">
              <center>
            </form>
            <table border="0" cellspacing="0" width="92%" cellpadding="0" height="114" background="images/wbg8.gif">
			<form name="frmSearch" action="search.php" method="get">
			<input type="hidden" name="srchArticles" value="1">
			<input type="hidden" name="srchNews" value="1">
              <tr>
                <td width="100%" height="57">
                <input type="text" name="query" value="SEARCH SITE" size="20" onClick="if(this.value == 'SEARCH SITE') { this.value = ''; }"> <input type="submit" value="Go!"><br>
                <a href="search.php"><span class="Link2">Advanced Search</span></a>
                </td>
              </tr>
			</form>
              <tr>
                <td width="100%" height="19">
					<span class="BodyHeading4"><img border="0" src="images/gar.gif" width="9" height="9"> Recent News</span>
                </td>
              </tr>
              <tr>
                <td width="100%" height="19">
					<p style="margin-left: 15">
						<?php ShowRecentNews(5, false); ?>
					</p>
				</td>
              </tr>
              <tr>
                <td width="100%" height="19">
					<span class="BodyHeading4"><img border="0" src="images/gar.gif" width="9" height="9"> Affiliate Links</span>
				</td>
              </tr>
              <tr>
                <td width="100%" height="19">
					<p style="margin-left: 15">
						<?php ShowAffiliates(false); ?>
					</p>
				</td>
              </tr>
              <tr>
                <td width="100%" height="19">&nbsp;</td>
              </tr>
            </table>
              </center>
            </div>
            </td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="100%" class="SideBody" align="center">
		<span class="Text1">
			<br>All content &copy; <?php echo date("Y"); ?> <?php echo "<a href='mailto:$adminEmail'>$adminName</a>"; ?>, <?php echo $siteName; ?>.
			Site Powered By <!--CyKuH [WTN]-->SiteWorksPro <?php echo $appVersion; ?>.
			<br><img src="blank.gif" width="1" height="5">
		</span>
	</td>
  </tr>
</table>
</body>
</html>
