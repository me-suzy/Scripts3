<html>
<head>
<title>MEC Stats 1.13</title>
<link href="style.css" rel="StyleSheet" type="text/css">
</head>
<body>
<h2><a href="http://www.mecstats.com">MEC Stats</a></h2>
<table width="100%">
	<tr>
		<td width="150" valign="top" class="nav">
		<?
		include('nav.inc');
		?>
		</td>
		<td valign="top" class="main">
		<?
		//Written By Matt Toigo
		include('functions.inc');
		
		switch($_GET['page'])
		{
		case 'reffer_detail':
			include('reffer_detail.inc');
		break;
		case 'reffer':
			include('reffer.inc');
		break;
		case 'trace':
			include('trace.inc');
		break;
		case 'pages':
			include('pages.inc');
		break;
		case 'trends':
			include('trends.inc');
		break;
		case 'trends_month':
			include('trends_month.inc');
		break;
		case 'trends_year':
			include('trends_year.inc');
		break;
		case 'trends_all':
			include('trends_all.inc');
		break;
		case 'search':
			include('search.inc');
		break;
		case 'browsers':
			include('browsers.inc');
		break;
		case 'page_info':
			include('page_info.inc');
		break;
		case 'averages':
			include('averages.inc');
		break;
		case 'latest':
			include('latest.inc');
		break;
		default:
			include('summary.inc');
		break;
		}
		?>
	</tr>
</table>

</body>
</html>