#ifndef _QRCODEPROCESS_H_
# define _QRCODEPROCESS_H_

# include <stdbool.h>
# include <stdint.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>
# include "qrcodegen.h"

void readQrcode(char *fileName, int width, int height);

void generateBitmapImage (unsigned char* image, int height, int width, char* imageFileName);

unsigned char* createBitmapFileHeader (int height, int stride);

unsigned char* createBitmapInfoHeader (int height, int width);

static void createBitmap(const uint8_t qrcode[], char *filename);

void createQrcode(int idPackage);

#endif
