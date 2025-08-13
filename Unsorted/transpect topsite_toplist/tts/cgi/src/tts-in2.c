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

static char rcsid[] = "$Id: tts-in2.c,v 2.1 1998/06/08 22:14:44 root Exp root $";

void dolog(char *tmp, ...);

int main(int argc, char **argv) {
  unsigned char *tptr;
  char tmp[1024], fname[256];
  struct stat buf;
  FILE *fh;

  char qs[64]  = { "\0" }; 
  char ip[16]  = { "\0" };
  char ref[64] = { "\0" };
  char ck[64]  = { "\0" };

  arg=argv;

  checksuid();

  if((tptr=getenv("QUERY_STRING"))) { if(!strncasecmp(tptr, "id=", 3)) tptr+=3; strncpy(qs, tptr, 64); qs[63]=0; }
  if((tptr=getenv("REMOTE_ADDR")))  { strncpy(ip, tptr, 16);   ip[15]=0; }
  if((tptr=getenv("HTTP_REFERER"))) { strncpy(ref, tptr, 64); ref[63]=0; }
  if((tptr=getenv("HTTP_COOKIE")))  { strncpy(ck, tptr, 64);   ck[63]=0; }

  if(!strlen(ip)) dolog("No REMOTE_ADDR");
  if(!strlen(qs)) dolog("No QUERY_STRING");
  else {
    for(tptr=qs;*tptr;tptr++) if(*tptr=='.' || *tptr=='/') { *tptr=0; break; }
    snprintf(fname, 255, "%s/%s%s", ACCOUNTPATH, qs, ACCT_SUFFIX);
    if(stat(fname, &buf)==-1) dolog("Account not found: %s", qs);
    else {
      snprintf(fname, 255, "%s/%s%s", VISITORPATH, qs, GATE_SUFFIX);
      if(!(fh = fopen(fname, "a"))) dolog("Can't append to file: %s", fname);
      else { fprintf(fh, "%s %s\n", ip, ref); fclose(fh); }
    }
  }

  if(!strlen(ck) || strncmp(ck,"ID=TTS-",7)) snprintf(ck, 64, "ID=TTS-%d-%d",time(NULL),getpid());

  printf("Content-Type: text/html\nSet-Cookie: %s\n\n", ck);

  if(!(fh = fopen(GATEPATH, "r"))) dolog("Can't open file: %s", GATEPATH);
  while(!feof(fh)) {
    if(!fgets(tmp, 1023, fh)) break;
    if(strncmp("<!-- TTSGATE", tmp, 12)) {
      puts(tmp);
    } else {
      printf("<A HREF=\"%s?%s/", INURL, qs);
      for(tptr=ref;*tptr;tptr++) {
        switch(*tptr) {
          case ':':
            printf("%%3a");
            break;
          case '%':
            printf("%%25");
            break;
          default:
            if(*tptr>31 && *tptr<128) printf("%c", *tptr);
        }
      }
      printf("\"%s",tmp+12);
    }
  }
  fclose(fh);
  return(0);
}

void dolog(char *tmp, ...)
{
  char ts[64];
  char temp[256];
  FILE *out;
  time_t tt;
  va_list ap;

  va_start(ap, tmp);

  tt = time(NULL);
  strncpy(ts,ctime(&tt),63); ts[63]=0; ts[strlen(ts)-1] = 0;
  vsnprintf(temp, 255, tmp, ap); temp[255] = 0;
  if((out = fopen(LOGFILE, "a"))) {
    fprintf(out, "[%s] (%s:%d) %s\n", ts, arg[0], getpid(), temp);
    fclose(out);
  }
}

