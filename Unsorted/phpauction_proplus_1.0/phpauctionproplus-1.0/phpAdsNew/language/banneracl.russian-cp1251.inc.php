<?
// russian doc file for Banner ACL administration
?>
<BR>
<table border="0" width="100%">
	<TR>
		<TD bgcolor="#CCCCCC">
			<table width="100%" cellspacing="1" cellpadding="5">
				<tr> 
					<td bgcolor="#FFFFFF"><b>Íàçâàíèå</b></td>
					<td bgcolor="#FFFFFF"><b>Àðãóìåíò</b></td>
					<td bgcolor="#FFFFFF"><b>Îïèñàíèå</b></td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">Client IP</td>
					<td bgcolor="#FFFFFF">IP net/mask: ip.ip.ip.ip/mask.mask.mask.mask, íàïðèìåð 127.0.0.1/255.255.255.0</td>
					<td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî äëÿ êîíêðåòíîãî äèàïàçîíà IP-àäðåñîâ.</td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">User agent regexp</td>
					<td bgcolor="#FFFFFF">Regular expression matching a user agent, for example ^Mozilla/4.? </td>
		 			<td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî äëÿ êîíêðåòíûõ áðàóçåðîâ.</td>
				</tr>
				<tr> 
					<td bgcolor="#FFFFFF">Äåíü íåäåëè (0-6)</td>
					<td bgcolor="#FFFFFF">Äåíü íåäåëè, îò 0 = Âîñêðåñåíüå äî 6 = Ñóááîòà</td>
					<td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî ïî óêàçàííûì äíÿì íåäåëè.</td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF">Äîìåí</td>
					<td bgcolor="#FFFFFF">Äîìåííûé ñóôôèêñ (ò.å. .jp, .edu, èëè google.com)</td>
					<td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî êîíêðåòíîìó äîìåíó.</td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF">Èñòî÷íèê</td>
					<td bgcolor="#FFFFFF">Èìÿ èñõîäíîé ñòðàíèöû</td>
					<td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî íà êîíêðåòíûõ ñòðàíèöàõ.</td>
				</tr>
                <tr> 
                    <td bgcolor="#FFFFFF">Âðåìÿ (0-23)</td>
                    <td bgcolor="#FFFFFF">Âðåìÿ ñóòîê, îò 0 = ïîëíî÷ü äî 23 = 23:00</td>
                    <td bgcolor="#FFFFFF">Ïîêàçûâàòü áàííåð òîëüêî â óêàçàííîå âðåìÿ ñóòîê.</td>
                </tr>
			</table>
		</TD>
	</TR>
</table>
<p>Íàïðèìåð, åñëè âû õîòèòå ïîêàçûâàòü ýòîò áàííåð òîëüêî ïî âûõîäíûì, íóæíî äîáàâèòü äâå ñòðîêè ACL:</p>
<ul>
	<li>Äåíü íåäåëè (0-6), <? echo $strAllow; ?>, àðãóìåíò 6 (Ñóááîòà)</li>
	<li>Äåíü íåäåëè (0-6), <? echo $strAllow; ?>, àðãóìåíò 0 (Âîñêðåñåíüå)</li>
    <li>Äåíü íåäåëè (0-6), <? echo $strDeny; ?>, àðãóìåíò * (ëþáîé äåíü)</li>
</ul>
Çàìåòüòå, ÷òî ïîñëåäíÿÿ ñòðîêà íå îáÿçàòåëüíî äîëæíà áûëà áûòü &quot;Äåíü íåäåëè&quot;. Ëþáîé <? echo $strDeny; ?> * ACL ïîäõîäèò äëÿ çàïðåùåíèÿ ïîêàçà áàííåðà, åñëè åùå åí ïðîèçîøëî ñîâïàäåíèÿ ïî ñîîòâåòñòâóþùåìó <? echo $strAllow; ?>.
<p>Äëÿ ïîêàçà áàííåðà ìåæäó 17:00 è 20:00:</p>
<ul>
    <li>Âðåìÿ, <? echo $strAllow; ?>, àðãóìåíò 17</li>  (17:00 - 17:59)
    <li>Âðåìÿ, <? echo $strAllow; ?>, àðãóìåíò 18</li>  (18:00 - 18:59)
	<li>Âðåìÿ, <? echo $strAllow; ?>, àðãóìåíò 19</li>  (19:00 - 19:59)
    <li>Âðåìÿ, <? echo $strDeny; ?>, àðãóìåíò * (ëþáîå âðåìÿ)</li>
</ul>
<?
// EOF russian doc file for Banner ACL administration
?>
