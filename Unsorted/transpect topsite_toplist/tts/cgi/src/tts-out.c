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

static char rcsid[] = "$Id: tts-out.c,v 2.1 1998/06/08 22:30:26 root Exp root $";

void mydodie(char *, ...);

int main(int argc, char **argv) {
  char *ip, *qs, *ck;
  char qstr[256], tmp[256], fname[256];
  struct stat buf;
  FILE *fh;

  arg=argv;

  checksuid();

  if(!(qs = getenv("QUERY_STRING"))) mydodie("QUERY_STRING not set");
  strncpy(qstr, qs, 255); qstr[255]=0;
  if(!strlen(qstr)) mydodie("QUERY_STRING empty");

  for(qs=qstr;*qs;qs++) if(*qs=='.' || *qs=='/') *qs='_';

  if(snprintf(fname, 255, "%s/%s%s", ACCOUNTPATH, qstr, ACCT_SUFFIX)==-1) mydodie("QUERY_STRING too long: %s", fname);

  if(stat(fname, &buf)==-1) mydodie("Account not found: %s", fname);
  if(!(fh = fopen(fname, "r"))) mydodie("Can't read account file: %s", fname);

  while(!feof(fh)) {
    if(!fgets(tmp, 255, fh)) continue; 
    tmp[255] = 0;
    if(!strncasecmp("http://", tmp, 7)) {
      printf("Location: %s\n", tmp);
      break;
    }
  }

  if(feof(fh)) mydodie("Can't find URL to forward to: %s", qstr);
  fclose(fh);

  if(!(ip=getenv("REMOTE_ADDR"))) dodie("No REMOTE_ADDR");
  ck=getenv("HTTP_COOKIE");
  snprintf(fname, 255, "%s/%s%s", VISITORPATH, qstr, OUT_SUFFIX); fname[255]=0;

  if(!(fh = fopen(fname, "a"))) dodie("Can't append to file: %s", qs);

  fprintf(fh, "%s", ip);
  if(ck) fprintf(fh, " %s", ck);
  fprintf(fh, "\n");
  fclose(fh);
  return(0);
}

void mydodie(char *s, ...)
{
  va_list ap;

  va_start(ap, s);

  printf("%s\n",MISSING);
  dodie(s, ap);
}
