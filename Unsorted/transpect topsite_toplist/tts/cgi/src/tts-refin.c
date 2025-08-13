/*
 * tts-refin.c - The TranSpecT Top Site incoming link CGI
 *               Generates cookies needed to correctly track traffic via
 *               tts-out.cgi
 * 
 * Running in C, smokin' up the back alleys of pornmongers everywhere!
 *
 * Copyright 1997 - Rick Franchuk - TranSpecT Consulting
 *
 * All Rights Reserved. Violators will be shot and pissed upon.
 *
 */

#include "tts.h"

/* 
 * uncomment the following if you want to enable cookie timeouts (limited cookie lifespan)
 *
#define COOKIE_TIMEOUT

char *weekdays[] = { "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" };
char *months[] = { "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec" };
 *
 */

static char rcsid[] = "$Id: tts-refin.c,v 2.7 1998/09/08 20:20:51 root Exp root $";

int main(int argc, char **argv) {
  struct stat buf;
  FILE *fh;

  char *tptr, *ref = NULL;
  char qs[64];
  char ip[16];
  char rf[64];
  char ck[128];
  char rstr[64];
  char qstr[256];
  char cstr[256];
  char fname[256];

  time_t tt;
#ifdef COOKIE_TIMEOUT
  struct tm *tm;
  char dstr[48];
#endif

  arg=argv;

  checksuid();

  *qs = *ip = *rf = *ck = 0;

#ifdef FORCE_TO_DOMAIN
  if((tptr=getenv("HTTP_HOST")) && (ref=getenv("REQUEST_URI"))) {
     if(!strcasestr(tptr, AUTHORIZED)) { printf("%s%s\n\n", FORCEDDOMAIN, ref); return 0; }
  }
#endif

  if((tptr=getenv("QUERY_STRING"))) { if(!strncasecmp(tptr, "id=", 3)) tptr+=3; strncpy(qs, tptr, 64); qs[63]=0; tptr=strtok(qs, "/"); }
  if((tptr=getenv("REMOTE_ADDR")))  { strncpy(ip,  tptr,  16);  ip[15]=0; }
  if((tptr=getenv("HTTP_REFERER"))) { 
    strncpy(rf, tptr,  64); rf[63]=0;
    if((tptr=getenv("QUERY_STRING"))) {
      strncpy(rstr, tptr, 64); rstr[63]=0;
      for(;*tptr;tptr++) if(*tptr=='/') break;
      if(*tptr) ref = tptr + 1;
    }
  }
  if((tptr=getenv("HTTP_COOKIE")))  { strncpy(ck,  tptr, 128); ck[127]=0; }
  snprintf(qstr, 256, "ID=TTS~%s~%s", UNIQUE, ip); qstr[255]=0;
  
  if(strncasecmp(ck, qstr, strlen(qstr))) {
    tt = time(NULL) + 1200;
    tptr = rf; if(ref) tptr = ref;
    snprintf(cstr, 256, "ID=TTS~%s~%s~%s~%d~%s", UNIQUE, ip, qs, tt-1200, tptr); cstr[255]=0;
#ifdef COOKIE_TIMEOUT
    tm = gmtime(&tt);
    tm->tm_year += 1900;
    snprintf(dstr, 48, "%s, %02d-%s-%d %02d:%02d:%02d GMT", weekdays[tm->tm_wday], tm->tm_mday, months[tm->tm_mon], tm->tm_year, tm->tm_hour, tm->tm_min, tm->tm_sec);
    printf("Set-Cookie: %s; domain=%s; expires=%s; path=/\n", cstr, COOKIEALLOW, dstr);
#else
    printf("Set-Cookie: %s; domain=%s; path=/\n", cstr, COOKIEALLOW);
#endif
  }
  printf("%s\n",DESTINATION);

  if(! *qs)       dodie("%s","QUERY_STRING not set");
  if(!strlen(qs)) dodie("%s","QUERY_STRING empty");
  if(! *ip)       dodie("%s","No REMOTE_ADDR");

  if(!ref) ref=rf;

  if(snprintf(fname, 255, "%s/%s%s", ACCOUNTPATH, qs, ACCT_SUFFIX)==-1) dodie("QUERY_STRING too long: %s", fname);
  if(stat(fname, &buf)==-1) dodie("Account not found: %s (%s)", qs, ref);

  strncpy(qstr, qs, 256);

  snprintf(fname, 255, "%s/%s%s", VISITORPATH, qs, IN_SUFFIX); fname[255]=0;
  if(!(fh = fopen(fname, "a"))) dodie("Can't append to file: %s", qs);
  fprintf(fh, "%s ", ip);
  while(*ref) {
    if(*ref!='%') fputc(*ref++, fh);
    if(*(ref+1)=='3' && (*(ref+2)=='A' || *(ref+2)=='a')) { fputc(':', fh); ref+=3; }
    else if(*(ref+1)=='2' && *(ref+2)=='5') { fputc('%', fh); ref+=3; }
    else fputc(*ref++, fh);
  }
  fprintf(fh, "\n");
  fclose(fh);

  snprintf(fname, 255, "%s/%s%s", VISITORPATH, qs, REF_SUFFIX); fname[255]=0;

  if(!(fh = fopen(fname, "a"))) dodie("Can't append to file: %s", fname);
  fprintf(fh, "%s", cstr);
  if(*ck) fprintf(fh, " (%s)", ck);
  fprintf(fh, "\n");
  fclose(fh);
  exit(0);
}
