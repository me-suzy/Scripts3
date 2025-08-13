<?php

	// Template two

?>
		</td>
		<td width="160" valign="top">
			<!-- Start Right Side Bar -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" class="SideHeader1">
						<span class="SideHeading">&nbsp;&nbsp;Bookmark Us!</span>
					</td>
				</tr>
				<tr>
					<td width="100%" class="SideBody1">
						<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10%" height="25" valign="bottom">
									<img src="images/hp.gif">&nbsp;&nbsp;
								</td>
								<td width="90%" height="25" valign="bottom">
									<a href="javascript:window.external.addFavorite('<?php echo $siteURL; ?>', '<?php echo $siteName; ?>')"><span class="Link3">Add to Favorites</span></a><br>
								</td>
							</tr>
							<tr>
								<td width="10%" height="25" valign="middle">
									<img src="images/hp.gif">&nbsp;
								</td>
								<td width="90%" height="25" valign="middle">
									<a style="behavior:url(#default#homepage)" onClick="setHomePage('<?php echo $siteURL; ?>')" href="#"><span class="Link3">Make Home Page</span></a><br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" class="SideHeader1">
						<span class="SideHeading">&nbsp;&nbsp;Our Newsletter</span>
					</td>
				</tr>
				<form name="frmEmail" action="newsletter.php" method="post">
				<tr>
				  <td width="100%" valign="middle" class="SideBody" height="40">
					<p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
						<span class="Text1">
				  			<img src="blank.gif" width="1" height="10"><br>
				  			<input size="12" type="text" name="strEmail" value="YOUR EMAIL" onClick="if(this.value == 'YOUR EMAIL') { this.value = ''; }"> <input type="submit" value="Go!"><br>
				  			<input checked type="radio" name="submit" value="1"> Subscribe<br>
				  			<input type="radio" name="submit" value="0"> Unsubscribe
				  			<br><img src="blank.gif" width="1" height="5">
				  		</span>
					</p>
				  </td>
				</tr>
				</form>
				<?php CheckShowUsersOnline(); ?>
				<?php ShowFeaturedBook(); ?>
				<?php if(!$isVBulletinForum && !$isPHPBBForum) { ?>
				<tr>
					<td width="100%" class="SideHeader">
						<span class="SideHeading">&nbsp;&nbsp;Recent News</span>
					</td>
				</tr>
				<tr>
				  <td width="100%" valign="top" class="SideBody1">
					<p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
				  		<?php ShowRecentNews(5); ?>
					</p>
				  </td>
				</tr>
				<?php
				}
				else
				{
					ShowRecentForumPosts(10);
				}
				?>
			</table>
			<!-- End Right Side Bar -->
		</td>
	</tr>
</table>
	<span class="Text1">
		<br>All content &copy; <?php echo date("Y"); ?> <?php echo "<a href='mailto:$adminEmail'>$adminName</a>"; ?>, <?php echo $siteName; ?>.<br>
		Site Powered By <!--CyKuH [WTN]-->SiteWorksPro <?php echo $appVersion; ?>.
		<br><br>
	</span>
  </center>
</div>
</body>
</html>

<?php

	ob_end_flush();

?>