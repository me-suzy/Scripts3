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


#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <time.h>
#include <errno.h>
#include "winfunc.c"



class cookie_rec{
public:
	char *name;
	char *value;

	int SetVal(char *s);
	};


class C_Cookie{
	int cno;
	cookie_rec *all;
public:
	C_Cookie (void);
   char *GetVal(char *s);
	};


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
        gmtp = localtime (&t);
	gmt = *gmtp;
	wday = weekday(gmt.tm_wday);
	mon = months(gmt.tm_mon);
	rez = (char *) malloc (50);
	sprintf (rez, "%s, %02d-%s-%02d %02d:%02d:%02d GMT", wday, gmt.tm_mday, mon, gmt.tm_year - 100, gmt.tm_hour, gmt.tm_min, gmt.tm_sec);
return rez;
	}








char *strsepp(char **s, char c){
	char *spt, *old;

   spt = strchr(*s, c);
   	if (spt == NULL) return NULL;
   old = *s;
   spt[0] = 0;
   *s = spt + 1;
return old;
	}







int cookie_rec:: SetVal(char *s){
	if (s[0] == ' ') s++;
	if (strchr(s, '=')){
   	name = strsepp(&s, '=');
      value = s;
      return 1;
   	}
   else  {
   	name = "";
      value = "";
   	}
return 0;
	}







C_Cookie:: C_Cookie(void){
	char *ce, *cline;
   int i;

   cno = 0; // Set the initial cookie entry no to zero


   // Get and copy the cookie line, exit if no cookies found
   ce = getenv("HTTP_COOKIE");
   if (ce == NULL) return;
	cline = strdup(ce);

   // Determine the amount of cookie entries in the line
   cno = 1 + chrcnt(cline, ';');
   all = new cookie_rec[cno];


   // split the line by ";" and set the cookie vals
   i = 0;
   while (strchr(cline, ';')){
   	all[i++].SetVal (   strsepp  (&cline, ';')  );
      }


   // if there is no ";" after the last entry, use what is left
   all[i++].SetVal(cline);
	}


char * C_Cookie:: GetVal(char *s){
	int i;
   char *res = "(null)";
   for (i = 0; i < cno; i++){
   	if (strcmp (all[i].name, s) == 0) res = all[i].value;
   	}
return res;
	}



