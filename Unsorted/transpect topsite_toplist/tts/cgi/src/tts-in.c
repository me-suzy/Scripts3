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

static char rcsid[] = "$Id: tts-in.c,v 2.1 1998/06/08 22:11:32 root Exp root $";

int main(int argc, char **argv) {
  char *tptr, *ip, *qs;
  char tmp[256], fname[256];
  struct stat buf;
  FILE *fh;

  arg=argv;

  puts(DESTINATION);
  if(!(qs=getenv("QUERY_STRING"))) dodie("QUERY_STRING not set");

  if(!strncasecmp(qs, "id=", 3)) qs+=3;

  strncpy(tmp, qs, 255); tmp[255]=0;

  if(!strlen(tmp)) dodie("QUERY_STRING empty");

  for(tptr=tmp;*tptr;tptr++) if(*tptr=='.' || *tptr=='/') *tptr='_';
  tptr = tmp;

  if(snprintf(fname, 255, "%s/%s%s", ACCOUNTPATH, tmp, ACCT_SUFFIX)==-1) dodie("QUERY_STRING too long: %s", fname);
  if(stat(fname, &buf)==-1) dodie("Account not found: %s", qs);
  if(!(ip=getenv("HTTP_COOKIE")) && !(ip=getenv("REMOTE_ADDR"))) dodie("No REMOTE_ADDR or HTTP_COOKIE");
  
  snprintf(fname, 255, "%s/%s%s", VISITORPATH, tmp, IN_SUFFIX); fname[255]=0;
  if(!(fh = fopen(fname, "a"))) dodie("Can't append to file: %s (%s)", fname, qs);

  if((tptr=getenv("HTTP_REFERER"))) snprintf(tmp, 255, "%s %s", ip, tptr);
  else  snprintf(tmp, 255, "%s", ip);
  fprintf(fh, "%s\n", tmp);
  fclose(fh);
  return 0;
}
