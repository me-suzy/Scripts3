// FCJ TRAFFIC MANAGEMENT
//
// WARRANTY DISCLAIMER AND COPYRIGHT NOTICE
// THE DBA STYLUS MAKES NO REPRESENTATION ABOUT THE SUITABILITY OR
// ACCURACY OF THIS SOFTWARE OR DATA FOR ANY PURPOSE, AND MAKES NO WARRANTIES,
// EITHER EXPRESS OR IMPLIED, INCLUDING MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE OR THAT THE USE OF THIS SOFTWARE OR
// DATA WILL NOT INFRINGE ANY THIRD PARTY PATENTS, COPYRIGHTS,
// TRADEMARKS, OR OTHER RIGHTS. THE SOFTWARE AND DATA ARE PROVIDED "AS IS".
//
// Licensee is not allowed to distribute the binary and source code (if released)
// to third parties. Licensee is not allowed to reverse engineer, disassemble or
// decompile code, or make any modifications of the binary or source code,
// remove or alter any trademark, logo, copyright or other proprietary notices,
// legends, symbols, or labels in the Software.
// Licensee is not allowed to sub-license the Software or any derivative
// work based on or derived from the Software.
//
// Copyright c 2001, 2002 by DBA STYLUS (USA)
// All Rights Reserved


#include <stdio.h>
#include <time.h>
#include <stdlib.h>


char logfile[] = "log/c_in.cgi";


char *TimeLine(void){
	struct tm *gmtp, gmt;
	time_t t;
   char *line;

   t = time(NULL);
   gmtp = gmtime(&t);
   gmt = *gmtp;

   line = (char *) malloc (20);
   sprintf (line,
   	"%04d/%02d/%02d %02d:%02d:%02d",
   	gmt.tm_year+1900, gmt.tm_mon, gmt.tm_mday,
      gmt.tm_hour, gmt.tm_min, gmt.tm_sec );
return line;
	}



char *weekday(int i){     // Translate numeric week into string
	char *weekdays[8] = {"Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"};
return weekdays[i];
	}





char *months(int i){       // Translate numeric month into string
	char *months[13] = {"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","Jan"};
return months[i];
	}







char *cookiefeed(){
	time_t t;
	struct tm *gmtp;
	struct tm  gmt ;
	char *rez, *wday, *mon;

	t =  time(NULL);
	t = t + 86400;
   gmtp = gmtime (&t);
	gmt = *gmtp;
	wday = weekday(gmt.tm_wday);
	mon = months(gmt.tm_mon);
	rez = (char *) malloc (50);
	sprintf (rez, "%s, %02d-%s-%02d %02d:%02d:%02d GMT", wday, gmt.tm_mday, mon, gmt.tm_year - 100, gmt.tm_hour, gmt.tm_min, gmt.tm_sec);
return rez;
	}



char *getenv_(char *var){
	char *p;
	p = getenv (var);
   p || (p = "");
return p;
	}



   
int chrcnt(char *str, char cc){ 
	int count = 0;
	long i = 0;
	while (str[i] != 0){
		if(str[i++] == cc){ count ++;}
		}
return count;
	}







long substr(char *big, char *small){
	long i, j, lens,lenb;

	lens = strlen(small);
	lenb = strlen( big );
	
	for (i = 0; i < lenb; i++){
		if(big [i] == small [0]){
			j = 0;
			while (small[j] == big [i+j]){
				j++;
				}
			if( j >= lens ){
				return i;
				}
			}
		}
return (-1);
	}





char *strlww(char *s){	
	long i, len;

	len = strlen (s);
	for(i = 0; i < len; i++){
		if ((s[i] >= 'A') && (s[i] <= 'Z')) s[i] = s[i] + 32;
		}
return s;
	}






char * charless (char *str, char cc){
	long len, i, j;
        char *nn;

	len = strlen(str);
	nn = (char *) malloc (len - chrcnt(str, cc) + 1);
	j = 0;
	for(i = 0; i < len; i++){
		if (str [i] != cc){
			nn[j++] = str[i];
			}
		}
	nn [j] = 0;
return nn;
	}









char *strcpp(char *ss, long frm, long len){
	char *nn;
	long i;
	nn = (char *) malloc (len + 1);
	for (i = 0; i < len; i ++) {
		nn[i] = ss[frm];
		frm ++;
		}
	nn [len] = 0;
return nn;
	}



void strctt(char *inn, char *into, long frm, long offset, long spot){
	long i = 0;
	for(i = 0; i < offset; i ++){
		into [i + spot] = inn [i + frm];
		}
	}


char *strdel (char *str, long frm, long offset){
	char *tmp;
	long leno, lenn;
	leno = strlen(str);
	lenn = leno - offset;
	tmp = (char *) malloc (lenn + 1);
	strctt(str, tmp, 0,  frm, 0);
	strctt(str, tmp, frm + offset, lenn - frm, frm);
	tmp [lenn] = 0; 
	strcpy (str ,tmp);
	free (tmp);
return str;
	} 




char *clrdot(char *s){
	int dotno, dotcount;
	long i, len;
	char *nn, *tmp;
	
	dotno = chrcnt (s, '.');
	dotcount = 0;
	i = 0;
	while (dotcount < (dotno - 1)){
		while (s[i++] != '.');
		dotcount ++;
		}
	len = strlen(s);
	nn = strcpp(s, i, len-i);
	strcpy (s, nn);
	free (nn);
return s;
	}


char *movslash(char *s){
	long len, frm;
	len = strlen(s);
	frm = substr(s, "/");
	if (frm != (-1) )
		s = strdel(s, frm, len-frm);
return s;
	}



char *coolsmb(char *s){
	long len, i,j;
	char *tmp;

	len = strlen(s);
	tmp = (char *) malloc(len + 1);
	j = 0;
	for ( i = 0; i < len; i++)
   		if(   
			((s[i] <= 'z') && (s [i] >= 'a' ))
			||
			(s[i] == '-')
			||
			(s[i] == '.')
			||
			((s[i] <= '9') && (s [i] >= '0' ))
		)
	tmp [j++] = s[i];
	strcpy (s, tmp);
	s[j] = 0;
return s;
	}


char *dstrip(char *s){
	long tmp;
	char *url;
        url = (char *) malloc(strlen(s) + 1);
	strcpy(url, s);

	strlww(url);
	tmp = substr (url, "http://");
	if( tmp != (-1) ) strdel (url, tmp, 7);
	movslash(url);
	clrdot (url);
	coolsmb(url);
return url;
	}





int main (void){
   FILE *log;     
   char *domain, *query;

	if (getenv("HTTP_REFERER") == NULL){ domain = "(noref)"; }
	else { domain = dstrip(getenv("HTTP_REFERER")); }

	printf ("Content-type: text/html\n\n");

	query = getenv("QUERY_STRING");
	if ((query) && (!strcmp(query, "bypass"))){
		printf("<!--- fcj :: bypassing --->");
		}
	else{

		
		printf ("<SCRIPT language=JavaScript>\n<!--\nvar r = '%s';\n", domain);
		printf ("document.cookie = 'ref=' + r + '**; path=/; expires=%s;';\n", cookiefeed());
		printf ("// -->\n</SCRIPT>");

	   log = fopen (logfile, "a+");
	   if (log){
		   fprintf (log, "%s|%s|%s|%s|%s|%s|%s|%s\n",
	   		TimeLine(),
	         getenv_("REMOTE_ADDR"),
		 domain,
	         getenv_("HTTP_REFERER"),
	         getenv_("HTTP_COOKIE"),
	         getenv_("HTTP_USER_AGENT"),
	         getenv_("QUERY_STRING"),
	         getenv_("REQUEST_METHOD")
	         );
	         }
	  }
	}


