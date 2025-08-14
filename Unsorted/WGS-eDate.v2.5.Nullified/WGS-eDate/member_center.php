<?php
include("member.php");
?> 
<blockquote> 
  <p align="center"><strong>Welcome <?php echo $tm0[fname]." ".$tm0[l_name]; ?> 
    !</strong></p>
  <p align="center">Your current rank : <?php echo $tm0[type]; ?><br>
    You can store maximum <?php echo $tm0pics; ?> pictures in your profile.</p>
  <blockquote> 
    <blockquote> 
      <blockquote> 
        <blockquote> 
          <p align="justify"><br>
            Your rights, available features in the system and details of other 
            members displayed when performing searches are determined by your 
            rank. You can purchase paid membership to increase your rank.<br>
            <br>
            See this table below for details :</p>
        </blockquote>
      </blockquote>
    </blockquote>
  </blockquote>
  <table width="600" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#999999">
    <tr bgcolor="#666699"> 
      <td><strong><font color="#FFFFFF" size="-2">MEMBERSHIP TYPE</font></strong></td>
      <td><div align="center"><strong><font color="#FFFFFF" size="-2">MINIMUM 
          RANK</font></strong></div></td>
      <td><strong><font color="#FFFFFF" size="-2">MESSAGE</font></strong></td>
      <td><strong><font color="#FFFFFF" size="-2">CHAT</font></strong></td>
      <td><strong><font color="#FFFFFF" size="-2">PICTURES</font></strong></td>
      <td><strong><font color="#FFFFFF" size="-2">CONTACT ON WEB</font></strong></td>
      <td><strong><font color="#FFFFFF" size="-2">CONTACT BY EMAIL</font></strong></td>
      <td><strong><font color="#FFFFFF" size="-2">CONTACT BY PHONE</font></strong></td>
      <td><div align="center"><font color="#FFFFFF" size="-2"><strong>PRICE (USD)</strong></font></div></td>
      <td><div align="center"><font color="#FFFFFF" size="-2"><strong>PURCHASE</strong></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="-2">NORMAL</font></td>
      <td><div align="center"><font color="#336699"><strong><font size="-2">0 
          </font></strong></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
          <?php if ($mem_free_message) echo "YES"; else echo "No";?>
          </font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
          <?php if ($mem_free_chat) echo "YES"; else echo "No";?>
          </font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_free_pics); ?></font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2">FREE</font></div></td>
      <td><div align="center"><font size="-2"><font color="#FF0000"><font color="#FF3300"></font></font></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="-2">SILVER</font></td>
      <td><div align="center"><font color="#336699"><strong><font size="-2">1000</font></strong></font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_silver_pics); ?></font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
          <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
          </font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><?php echo $mem_silver_cost;?></font></div></td>
      <td><div align="center"><font color="#FF3300" size="-2"><a href="buy.php?type=silver">PAYPAL</a></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="-2">GOLD</font></td>
      <td><div align="center"><font color="#336699"><strong><font size="-2">2000</font></strong></font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_gold_pics); ?></font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font color="#336633" size="-2"> 
          <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
          </font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#336633"> 
          <?php if ($mem_gold_emails) echo "YES"; else echo "No";?>
          </font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><?php echo $mem_gold_cost;?></font></div></td>
      <td><div align="center"><font color="#FF3300" size="-2"><a href="buy.php?type=gold">PAYPAL</a></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="-2">PLATINUM</font></td>
      <td><div align="center"><font color="#336699"><strong><font size="-2">3000</font></strong></font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_platinum_pics); ?></font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font color="#336633" size="-2"> 
          <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
          </font></div></td>
      <td><div align="center"><font color="#336633" size="-2"> 
          <?php if ($mem_gold_emails) echo "YES"; else echo "No";?>
          </font></div></td>
      <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
          <?php if ($mem_platinum_phone) echo "YES"; else echo "No";?>
          </font></font></font></font></font></font></font></div></td>
      <td><div align="center"><font size="-2"><?php echo $mem_platinum_cost;?></font></div></td>
      <td><div align="center"><font color="#FF3300" size="-2"><a href="buy.php?type=platinum">PAYPAL</a></font></div></td>
    </tr>
  </table>
  <blockquote> 
    <blockquote> 
      <blockquote> 
        <blockquote> 
          <p align="justify"><font color="#999999" size="-2">WARNING<br>
            When you click on a purchase link the webmaster will be automatically 
            notified about your purchase intention so he can check your payment 
            and upgrade your account. This will be done by recording all the payment 
            details in the webmaster area for later processing.</font></p>
        </blockquote>
      </blockquote>
      <p align="justify">&nbsp;</p>
    </blockquote>
  </blockquote>
  <p>&nbsp;</p>
</blockquote>
<?
include("_footer.php");
?>


