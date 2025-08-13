<?
// russian doc file for Banner ACL administration
?>
<BR>
<table border="0" width="100%">
	<TR>
		<TD bgcolor="#CCCCCC">
			<table width="100%" cellspacing="1" cellpadding="5">
				<tr> 
					<td bgcolor="#FFFFFF"><b>îÁÚ×ÁÎÉÅ</b></td>
					<td bgcolor="#FFFFFF"><b>áÒÇÕÍÅÎÔ</b></td>
					<td bgcolor="#FFFFFF"><b>ïÐÉÓÁÎÉÅ</b></td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">Client IP</td>
					<td bgcolor="#FFFFFF">IP net/mask: ip.ip.ip.ip/mask.mask.mask.mask, ÎÁÐÒÉÍÅÒ 127.0.0.1/255.255.255.0</td>
					<td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ ÄÌÑ ËÏÎËÒÅÔÎÏÇÏ ÄÉÁÐÁÚÏÎÁ IP-ÁÄÒÅÓÏ×.</td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">User agent regexp</td>
					<td bgcolor="#FFFFFF">Regular expression matching a user agent, for example ^Mozilla/4.? </td>
		 			<td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ ÄÌÑ ËÏÎËÒÅÔÎÙÈ ÂÒÁÕÚÅÒÏ×.</td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">äÅÎØ ÎÅÄÅÌÉ (0-6)</td>
					<td bgcolor="#FFFFFF">äÅÎØ ÎÅÄÅÌÉ, ÏÔ 0 = ÷ÏÓËÒÅÓÅÎØÅ ÄÏ 6 = óÕÂÂÏÔÁ</td>
					<td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ ÐÏ ÕËÁÚÁÎÎÙÍ ÄÎÑÍ ÎÅÄÅÌÉ.</td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF">äÏÍÅÎ</td>
					<td bgcolor="#FFFFFF">äÏÍÅÎÎÙÊ ÓÕÆÆÉËÓ (Ô.Å. .jp, .edu, ÉÌÉ google.com)</td>
					<td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ ËÏÎËÒÅÔÎÏÍÕ ÄÏÍÅÎÕ.</td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF">éÓÔÏÞÎÉË</td>
					<td bgcolor="#FFFFFF">éÍÑ ÉÓÈÏÄÎÏÊ ÓÔÒÁÎÉÃÙ</td>
					<td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ ÎÁ ËÏÎËÒÅÔÎÙÈ ÓÔÒÁÎÉÃÁÈ.</td>
				</tr>
                <tr> 
                    <td bgcolor="#FFFFFF">÷ÒÅÍÑ (0-23)</td>
                    <td bgcolor="#FFFFFF">÷ÒÅÍÑ ÓÕÔÏË, ÏÔ 0 = ÐÏÌÎÏÞØ ÄÏ 23 = 23:00</td>
                    <td bgcolor="#FFFFFF">ðÏËÁÚÙ×ÁÔØ ÂÁÎÎÅÒ ÔÏÌØËÏ × ÕËÁÚÁÎÎÏÅ ×ÒÅÍÑ ÓÕÔÏË.</td>
                </tr>
			</table>
		</TD>
	</TR>
</table>
<p>îÁÐÒÉÍÅÒ, ÅÓÌÉ ×Ù ÈÏÔÉÔÅ ÐÏËÁÚÙ×ÁÔØ ÜÔÏÔ ÂÁÎÎÅÒ ÔÏÌØËÏ ÐÏ ×ÙÈÏÄÎÙÍ, ÎÕÖÎÏ ÄÏÂÁ×ÉÔØ Ä×Å ÓÔÒÏËÉ ACL:</p>
<ul>
	<li>äÅÎØ ÎÅÄÅÌÉ (0-6), <? echo $strAllow; ?>, ÁÒÇÕÍÅÎÔ 6 (óÕÂÂÏÔÁ)</li>
	<li>äÅÎØ ÎÅÄÅÌÉ (0-6), <? echo $strAllow; ?>, ÁÒÇÕÍÅÎÔ 0 (÷ÏÓËÒÅÓÅÎØÅ)</li>
    <li>äÅÎØ ÎÅÄÅÌÉ (0-6), <? echo $strDeny; ?>, ÁÒÇÕÍÅÎÔ * (ÌÀÂÏÊ ÄÅÎØ)</li>
</ul>
úÁÍÅÔØÔÅ, ÞÔÏ ÐÏÓÌÅÄÎÑÑ ÓÔÒÏËÁ ÎÅ ÏÂÑÚÁÔÅÌØÎÏ ÄÏÌÖÎÁ ÂÙÌÁ ÂÙÔØ &quot;äÅÎØ ÎÅÄÅÌÉ&quot;. ìÀÂÏÊ <? echo $strDeny; ?> * ACL ÐÏÄÈÏÄÉÔ ÄÌÑ ÚÁÐÒÅÝÅÎÉÑ ÐÏËÁÚÁ ÂÁÎÎÅÒÁ, ÅÓÌÉ ÅÝÅ ÅÎ ÐÒÏÉÚÏÛÌÏ ÓÏ×ÐÁÄÅÎÉÑ ÐÏ ÓÏÏÔ×ÅÔÓÔ×ÕÀÝÅÍÕ <? echo $strAllow; ?>.
<p>äÌÑ ÐÏËÁÚÁ ÂÁÎÎÅÒÁ ÍÅÖÄÕ 17:00 É 20:00:</p>
<ul>
    <li>÷ÒÅÍÑ, <? echo $strAllow; ?>, ÁÒÇÕÍÅÎÔ 17</li>  (17:00 - 17:59)
    <li>÷ÒÅÍÑ, <? echo $strAllow; ?>, ÁÒÇÕÍÅÎÔ 18</li>  (18:00 - 18:59)
	<li>÷ÒÅÍÑ, <? echo $strAllow; ?>, ÁÒÇÕÍÅÎÔ 19</li>  (19:00 - 19:59)
    <li>÷ÒÅÍÑ, <? echo $strDeny; ?>, ÁÒÇÕÍÅÎÔ * (ÌÀÂÏÅ ×ÒÅÍÑ)</li>
</ul>
<?
// EOF russian doc file for Banner ACL administration
?>
