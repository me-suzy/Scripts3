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
#include "cookie.cpp"





char errurl[] 	= "http://www.yahoo.com";
char logfile[] = "log/c_out.cgi";
char pipefile[]= "data/pipe.cgi";
char errfile[] = "data/err.url";

C_Cookie cookie;





class cQuery{
public:
	char *link;
   char *url;
   char *gll;
   int  frac;

	cQuery(void);
   void ExtractParams (char *line);
	};




void cQuery:: ExtractParams (char *line){
   char *tmp, *name, *value;

   tmp = strchr(line, '=');
   if (tmp == NULL) return;				// go home if nothing to do

   tmp[0] = 0;									// set
   name = line;								// name and
   value = tmp + 1;							// value


   if (strcmp (name, "link") == 0){ link = value; return; }
   if (strcmp (name, "url" ) == 0){ url = value; return; }
   if (strcmp (name, "gll" ) == 0){
      tmp = strchr(value, '|');
   	if ( tmp==NULL){ return; }

      tmp[0] = 0;
      frac = strtol(value, NULL, 10);
   	gll = tmp + 1;
      if (gll[0] == 0){
      	gll  = NULL;
         frac = 0;
         }
      return;
      }
	}




cQuery::cQuery(void){
	char *tmp, *query, *sepp, *nn;
   int len;
								   // predefined default values in case of no params
   link 	= "no-label";
   url	= "no-link";
   gll	= NULL;
   frac	= 0;

   sepp = "**";								// should be compatible with any shit

   tmp = getenv("QUERY_STRING");			// get the params line
	if (tmp == NULL) return;
	len = strlen (tmp) + 4;

	query = (char *) malloc (len);
   strcpy (query, tmp);
   strcat (query, "***");					// add the "***"
   query[len] = 0;							// to make sure all go smooth

   tmp = strstr(query, sepp);
   while (tmp != NULL){						// while params exist do eat them
	   tmp[0] = 0;
	   nn = query;
      ExtractParams(nn);
	   query = tmp + 2;
      tmp = strstr(query, sepp);
      }
	}



cQuery q;




class prec{
public:
	long prc;
   char *domain;
   char *url;
   int mode;
   long lim;

	void SetVal (int t_prc,char *t_domain,char *t_url,int t_mode,int t_lim);
	};



class blist{
public:
	char **items;
   int sn;
	blist(void);
   void PrintCLine(void);
   int blist:: AddNew(char *s);
	};




class pp{
 private:
 	prec *all;

 	long recnr;
   long psum;
 public:
 	int succ;
   blist bll;

 	void load(char *filename);
   int pp:: PickRand(void);
   void pp:: OutGo(int actd);
   void pp:: FinalCheck(int actd);
   void RemoveRaws(void);
   void pp:: RemoveFromList(char *s);
 	};






// GLOB //
			   pp pipe_;
// END GLOB












int blist:: AddNew(char *s){
	int i, exs;

   exs = 0;
   if (s != NULL)
   	for (i = 0; i < sn; i++)
	      if ( (items[i] != NULL) && ((strcmp(items[i], s) == 0))) exs = 1;
   if (!exs){
   	items[sn++] = s;
      return 1;
      }
return 0;
	}




blist:: blist(void){
	char *banline, *ref, *tmp, *tmp2;

   tmp = cookie.GetVal("bin");
   banline = strdup(tmp);
   tmp = cookie.GetVal("ref");
   tmp2= strdup(tmp);
   ref = strsepp (&tmp2, '*');
   	if (ref == NULL) ref = "(null)";
   items = new char*[chrcnt(banline, '|')+3];

   sn = 0;
   tmp = strsepp(&banline, '|');
   while (tmp != NULL) {
   	items[sn++] = tmp;
      tmp = strsepp(&banline, '|');
      }
   if (strcmp(ref, "(null)") != 0)  AddNew (ref);
	}




void blist :: PrintCLine(void){
	int i;
   char *cd;

   if (sn){
	   printf ("Set-Cookie: bin=");
	   for (i = 0; i < sn; i++)
			if (items[i] != NULL) printf("%s|", items[i]);
	   printf ("; ");
		cd = cookiefeed();
      if (cd != NULL) printf("expires=%s;", cd);
      printf ("\n");
      }
	}




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





void fcjlog(char *domain, int unique, char *url){
   FILE *LOG;
   char *cval, *cc;
   char *ip, *browser;  // I officially hate Solaris


   LOG = fopen (logfile, "a");
   if (LOG == NULL){
   	fprintf (stderr,
      	"FCJ::can not open log file %s for writing: %s\n",
         logfile, strerror(errno));
      return;
   	}
   // get the cookie val from environment
   cval = cookie.GetVal("ref");

   // separate the value from ** delim
   cc = strsepp (&cval, '*');
   	if (cc == NULL) cc = "(null)";

   // Solaris dont like null print 
	ip = getenv("REMOTE_ADDR");
	browser = getenv("HTTP_USER_AGENT");

	ip || (ip = "(null)");
	browser || (browser = "(null)");

   // write down the log
   fprintf (LOG, "%s|%s|%s|%s|%s|%s|%s|%d\n",
   		TimeLine(),
         ip,
         domain,
         url,
         cc,
         q.link,
         browser,
         unique);
   fclose (LOG);
	}






void ErrTime(void){
	printf("Location: %s\n\n", errurl);
   exit (1);
	}






void PlanB(void){
   FILE *ERR;
   char s[1024];
   int succ, unique;

   ERR = fopen (errfile, "r");
	   if (ERR == NULL)	ErrTime();
	succ = fscanf (ERR, "%s", &s);
   fclose (ERR);

   	if ( (succ == 0) || (s == NULL) )		ErrTime();
   	if ( strlen(s) < 8 ) ErrTime();

   unique = pipe_.bll.AddNew("(null)");
   pipe_.bll.PrintCLine();

   printf("Location: %s\n\n", s);

   fcjlog("_err", unique, s);
   exit (1);
	}







void prec:: SetVal (int t_prc,char *t_domain,char *t_url,int t_mode,int t_lim){
	int tmp;
   prc 	= t_prc;
   mode 	= t_mode;
   lim 	= t_lim;

   tmp = 2 + strlen(t_domain);
   domain = new char[tmp];
   strcpy (domain, t_domain);

   tmp = 2 + strlen (t_url);
   url = new char[tmp];
   strcpy (url, t_url);
	}








void pp:: load(char *filename){
	FILE *PF;
   int nr, prc, mode, i;
   long lim;
	char domain[64], url[1024];

	succ = 1;
   PF = fopen (filename, "r");
   // make sure file opened successfully
   if (PF == NULL){
   	succ = 0;
      return;
   	}

   // scan the pipe header to see amount of recs
	nr = fscanf(PF, "%d|%d", &recnr, &psum);
   if (nr != 2 ){
   	succ = 0;
      return;
   	}
   // if the header good then get the body of the list
   all = new prec[recnr]; // set mem for the value list

   for (i = 0; i < recnr; i++){
   	nr = fscanf(PF, "%d|%[^|]|%[^|]|%d|%d", &prc, domain, url, &mode, &lim);
														// scan the line for apropriate format
   	// make sure segmentation is all rite
      if ((domain == NULL) || (url == NULL)){
         succ = 0;
         return;
      	}
      // 5 recs should be present
   	if ((nr != 5) || (64 < strlen(domain)) || (1024 < strlen(url))){
         succ = 0;
         return;
      	}
      // if OK then copy the values to the list
      all[i].SetVal(prc, domain, url, mode, lim);
   	}


   fclose (PF);
	}






long GetRand(double max){
	time_t t;
	long ll;
	srand ((unsigned) time (&t));
	ll = 1 + (int) (max*rand()/(RAND_MAX+1.0));
return ll;
	}





long GetRand2(long max){
	time_t t;
	long ll;
	srand ((unsigned) time (&t)+98764);
	ll = (int) rand() % max;
return ll;
	}






void pp:: RemoveFromList(char *s){
	int i;

   if (s!=NULL)
	   for (i = 0; i < recnr; i++){
	   	if ( (all[i].domain != NULL) && (strcmp (all[i].domain, s) == 0)){
         	if ((all[i].mode == 1) || (all[i].mode == 2)){
	            recnr --;
	            psum -= all[i].prc;
	            all[i] = all[recnr];
	            return;
               }
	      	}
	   	}
	}



void pp:: RemoveRaws(void){
	int i;

   for (i = 0; i < bll.sn; i ++)
      RemoveFromList(bll.items[i]);

	}



void pp:: FinalCheck(int actd){
	// Just checking if the pointers not lost and domains valid...
	if (all[actd].domain == NULL){ PlanB(); }
   else if (strlen (all[actd].domain) < 4){ PlanB(); }

   if (all[actd].url == NULL){ PlanB(); }
   else if (strlen (all[actd].url) < 7){ PlanB(); }
	}







void pp:: OutGo(int actd){
	int unique;
	FinalCheck(actd);
   unique = bll.AddNew(all[actd].domain);
   bll.PrintCLine();
	printf ("Location: %s\n\n", all[actd].url);
   fcjlog(all[actd].domain, unique, all[actd].url);
	}









int pp:: PickRand(void){
   long SUM, rnd;
   int i;

   SUM = 0;
	rnd = GetRand(psum);
   for (i = 0; i < recnr; i++){
   	SUM = SUM + all[i].prc;
      if (SUM >= rnd) return i;   // return the index no of the domain to be run
   	}
	PlanB();                		 // If some errors appear go to plan B
return 0;                         // Doesn't make much difference what to return
	}





void CheckPerm(void){
	blist bll;
   char *url, *domain;
   int unique;

   url = q.url;
   if ((url == NULL) || (strcmp(url, "no-link") == 0) ){
   	return;
      }
   else{
		domain = dstrip(url);
	   if ((domain == NULL) || (strlen(domain) < 3)){
	   	return;
	   	}
      else{
         unique = bll.AddNew(domain);
         bll.PrintCLine();
			printf ("Location: %s\n\n", url);
		   fcjlog(domain, unique, url);
      	}
      }
   exit(1);
	}





void FeedGalleries(void){      // same shit as CheckPerm, only %% added
	blist bll;
   char *url, *domain;
   int unique, rnd;

   if (q.frac == 0){ 	return; }

	rnd = GetRand2(100);
   if (q.frac < rnd){	return; }

   url = q.gll;
   if ((url == NULL) || (strcmp(url, "no-link") == 0) ){
   	return;
      }
   else{
		domain = dstrip(url);
	   if ((domain == NULL) || (strlen(domain) < 3)){
	   	return;
	   	}
      else{
         unique = bll.AddNew(domain);
         bll.PrintCLine();
			printf ("Location: %s\n\n", url);
		   fcjlog("gallery", unique, url);
      	}
      }
   exit(1);
	}




 int main(void){
   int ActiveDomain;

	// TGP Feature
   FeedGalleries();

   // check if no pernament link is defined
   CheckPerm();

   // load the active list
   pipe_.load (pipefile);

   // Do not send the same visitor to the same site twice!
    pipe_.RemoveRaws();

   // Do not continue normal execution if loading pipe is unsuccessful
   if (pipe_.succ != 1){
   	PlanB();
   	}
	// Pick the domain that the user would "like" to go to...
   ActiveDomain = pipe_.PickRand();

   // send out the visitor...
   pipe_.OutGo(ActiveDomain);
 	}








