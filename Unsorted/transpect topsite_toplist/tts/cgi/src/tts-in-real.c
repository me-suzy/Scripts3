/*
 * tts-in.c - The TranSpecT Top Site incoming link CGI
 * 
 * Running in C, smokin' up the back alleys of pornmongers everywhere!
 *
 * Copyright 1997 - Rick Franchuk - TranSpecT Consulting
 *
 * All Rights Reserved. Violators will be shot and pissed upon.
 *
 */

#include "tts.h"

static char rcsid[] = "$Id: tts-in-real.c,v 2.3 1998/06/08 22:10:49 root Exp root $";

static char unassigned[] = "(not defined)";
	
int main(int argc, char **argv) {
  char *tptr, *ref, *ip, *ck;
  char qs[128];
  char fname[256];
  struct stat buf;
  FILE *fh;

  arg=argv;

  puts(DESTINATION);

  checksuid();

  ref=getenv("HTTP_REFERER");
  if(!ref || !strcasestr(ref, AUTHORIZED)) {
    tptr = getenv("QUERY_STRING"); if(!tptr) tptr=unassigned;
    ip = getenv("REMOTE_ADDR"); if(!ip) ip=unassigned;
    dodie("Illegal access from %s (ref: %s, qstr: %s)", ip, ref, tptr);
  }

  if(!(tptr=getenv("QUERY_STRING"))) dodie("%s", "QUERY_STRING not set");
  strncpy(qs, tptr, 128);

  if(!strlen(qs)) dodie("%s","QUERY_STRING empty");

  for(tptr=qs;*tptr;tptr++) if(*tptr=='/') { *tptr=0; break; }
  ref = tptr + 1;

  if(snprintf(fname, 255, "%s/%s%s", ACCOUNTPATH, qs, ACCT_SUFFIX)==-1) dodie("QUERY_STRING too long: %s", fname);

  if(stat(fname, &buf)==-1) dodie("Account not found: %s", qs);

  ck = getenv("HTTP_COOKIE");
  ip = getenv("REMOTE_ADDR");
  if(!ck && !ip) dodie("%s","No REMOTE_ADDR or HTTP_COOKIE");

  snprintf(fname, 255, "%s/%s%s", VISITORPATH, qs, IN_SUFFIX);
  fname[255]=0;

  if(!(fh = fopen(fname, "a"))) dodie("Can't append to file: %s", qs);
  fprintf(fh, "%s ", ip);
  if(ck) fprintf(fh, "%s ", ck);
  while(*ref) {
    if(*ref!='%') fputc(*ref++, fh);
    if(*(ref+1)=='3' && (*(ref+2)=='A' || *(ref+2)=='a')) { fputc(':', fh); ref+=3; }
    else if(*(ref+1)=='2' && *(ref+2)=='5') { fputc('%', fh); ref+=3; }
    else fputc(*ref++, fh);
  }
  fprintf(fh, "\n");
  fclose(fh);
  exit(0);
}
