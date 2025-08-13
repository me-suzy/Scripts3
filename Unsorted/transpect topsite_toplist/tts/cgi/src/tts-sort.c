/*
 *
 * tts-sort.c - Handy dandy hit compilation code for TTSv2.0
 *
 * Chews up all the files in 'visitors' and print out a stat file to stdout.
 * This should be vastly superior speedwise to the perl equivalent (Duh!)
 * that DIDN'T perform the incoming->outgoing cookie referal match.
 *
 */

#include "tts.h"

char rcsid[] = "$Id: tts-sort.c,v 2.5 1998/09/18 06:34:32 root Exp root $";

struct sorthash {
  char filename[128];
  char *buf;
  char **hash;
  char **uhash;
  size_t len;
  size_t lines;
  size_t uniq;
};

size_t getlines(char *, size_t);
int compar(char **, char **);
void freesorthash(struct sorthash *);
void grabnsort(struct sorthash *);
size_t crossref(struct sorthash *, struct sorthash *);
void unique(struct sorthash *);
void process(char *);
void usage();

int maxicount=3, opt_g=0, opt_r=0, opt_u=0;

extern char *optarg;
extern int optind;

int main(int argc, char **argv) {
  char *r;
  int a;

  arg = argv;

  if(argc<2) usage();
  while((a=getopt(argc, argv, "?hgm:ru"))!=EOF) {
    switch(a) {
      case 'g': opt_g = 1; break;
      case 'r': opt_r = 1; break;
      case 'u': opt_u = 1; break;
      case 'h':
      case '?': usage();
      case 'm': maxicount = strtol(optarg, (char **)&r, 10);
                if(r==optarg) dodie("Invalid value for switch -m");
    }
  }
  for(a=optind;a<argc;a++) { process(argv[a]); }
  return 0;
}

void usage() {
  fprintf(stderr, 
"Usage: tts-sort [options] account [account [...]]

Options:
  -g     Count gateway hits
  -r     Count refout/refin hits (crossreferenced)
  -u     Count unique hits
  -m     Specify maximum per-browser hits in refin/refout crossreference
  -h     Show help

'account' is the name or names of .tts accounts to be processed.
Output format:

record rawin rawout rawgate uniqin uniqout uniqgate crossref
"); 
  exit(1); 
}

void process(char *account) {
  struct sorthash refin, refout, in, out, gate;

  bzero(&refin,  sizeof(struct sorthash));
  bzero(&refout, sizeof(struct sorthash));
  bzero(&in,     sizeof(struct sorthash));
  bzero(&out,    sizeof(struct sorthash));
  bzero(&gate,   sizeof(struct sorthash));

  snprintf(in.filename,  128, "%s/%s.in",  VISITORPATH, account);
  snprintf(out.filename, 128, "%s/%s.out", VISITORPATH, account);
  grabnsort(&in);
  grabnsort(&out);
  if(opt_u) { unique(&in); unique(&out); }

  if(opt_r==1) {
    snprintf(refin.filename,  128, "%s/%s.ref-in",  VISITORPATH, account);
    snprintf(refout.filename, 128, "%s/%s.ref-out", VISITORPATH, account);
    grabnsort(&refin);
    grabnsort(&refout);
  }

  if(opt_g==1) {
    snprintf(gate.filename, 128, "%s/%s.gate", VISITORPATH, account);
    grabnsort(&gate);
    if(opt_u) unique(&gate);
  }

  printf("%s %d %d %d %d %d %d %d\n", account, in.lines, out.lines, gate.lines, in.uniq, out.uniq, gate.uniq, crossref(&refin, &refout));

  freesorthash(&in);
  freesorthash(&out);
  freesorthash(&gate);
  freesorthash(&refin);
  freesorthash(&refout);
}

void freesorthash(struct sorthash *sh) {
  if(!sh->len) return;
  if(sh->buf)   free(sh->buf);
  if(sh->hash)  free(sh->hash);
  if(sh->uhash) free(sh->uhash);
}

void grabnsort(struct sorthash *sh) {
  register char *p, *q;
  int fd;
  size_t a;
  struct stat st;

  fd = open(sh->filename, O_RDONLY);
  if(fd<0) return;
  if(fstat(fd, &st)) { close(fd); return; }
  sh->len = st.st_size;
  if(!(sh->buf = (char *)malloc(sh->len+1))) dodie("Error in malloc (%d bytes for %s)", sh->len+1, sh->filename);
  if(read(fd, sh->buf, sh->len)==-1) dodie("Error reading %s", sh->filename);
  sh->buf[sh->len]=0;
  sh->lines = getlines(sh->buf, sh->len);
  if(!(sh->hash = (char **)calloc(sh->lines+1, sizeof(char **)))) dodie("Can't calloc memory for hash");
  sh->hash[0]=sh->buf;
  for(q=sh->buf+sh->len,p=sh->buf,a=1;p<q;p++) if(*p==10) { sh->hash[a++]=p+1; *p=0; }
  qsort(sh->hash, sh->lines, sizeof(char **), compar);
  close(fd);
}

void unique(struct sorthash *sh) {
  size_t l;

  if(!sh->lines) return;
  if(!(sh->uhash = (char **)calloc(sh->lines+1, sizeof(char **)))) dodie("Can't calloc memory for uhash");
  sh->uhash[0] = sh->hash[0];
  for(l=1, sh->uniq=1;l<sh->lines;l++) if(strcmp(sh->hash[l], sh->hash[l-1]))
    sh->uhash[sh->uniq++] = sh->hash[l];
}

int compar(char **a, char **b) {
  return strcmp(*a, *b);
}

size_t getlines(char *s, size_t len) {
  register char *t;
  register size_t a=0;

  for(t=s;t<s+len;t++) if(*t==10) a++;

  return a;
}

size_t crossref(struct sorthash *shin, struct sorthash *shout) {
  size_t cie=0, coe=0, icount=0, total=0;
  int res;

  if(shout->len==0) return 0;

  for(;cie<shin->lines && coe<shout->lines;) {
    res=strncmp(shin->hash[cie], shout->hash[coe], strlen(shin->hash[cie])-1);
    if(res<0) { cie++; icount=0; continue; }
    if(res>0) { coe++; icount=0; continue; }
    total++; icount++; coe++;
    if(maxicount>0 && icount>=maxicount) { icount = 0; cie++; }
  }
  return total; 
}
