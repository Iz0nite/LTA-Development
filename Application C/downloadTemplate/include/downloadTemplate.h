#ifndef _DOWNLOADTEMPLATE_H_

#define _DOWNLOADTEMPLATE_H_

#include <stdio.h>
#include <stdlib.h>
#include <curl.h>

struct FtpFile {
  const char *filename;
  FILE *stream;
};

void downloadTemplate();
static size_t my_fwrite(void *buffer, size_t size, size_t nmemb, void *stream);

#endif // _DOWNLOADTEMPLATE_H_

