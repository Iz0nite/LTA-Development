#include <stdbool.h>
#include <stdint.h>
#include <stdio.h>
#include "qrcodegen.h"



const int BYTES_PER_PIXEL = 3; /// red, green, & blue
const int FILE_HEADER_SIZE = 14;
const int INFO_HEADER_SIZE = 40;

void generateBitmapImage(unsigned char* image, int height, int width, char* imageFileName);
unsigned char* createBitmapFileHeader(int height, int stride);
unsigned char* createBitmapInfoHeader(int height, int width);

void generateBitmapImage (unsigned char* image, int height, int width, char* imageFileName)
{
    int widthInBytes = width * BYTES_PER_PIXEL;

    unsigned char padding[3] = {0, 0, 0};
    int paddingSize = (4 - (widthInBytes) % 4) % 4;

    int stride = (widthInBytes) + paddingSize;

    FILE* imageFile = fopen(imageFileName, "wb");

    unsigned char* fileHeader = createBitmapFileHeader(height, stride);
    fwrite(fileHeader, 1, FILE_HEADER_SIZE, imageFile);

    unsigned char* infoHeader = createBitmapInfoHeader(height, width);
    fwrite(infoHeader, 1, INFO_HEADER_SIZE, imageFile);

    int i;
    for (i = 0; i < height; i++) {
        fwrite(image + (i*widthInBytes), BYTES_PER_PIXEL, width, imageFile);
        fwrite(padding, 1, paddingSize, imageFile);
    }

    fclose(imageFile);
}

unsigned char* createBitmapFileHeader (int height, int stride)
{
    int fileSize = FILE_HEADER_SIZE + INFO_HEADER_SIZE + (stride * height);

    static unsigned char fileHeader[] = {
        0,0,     /// signature
        0,0,0,0, /// image file size in bytes
        0,0,0,0, /// reserved
        0,0,0,0, /// start of pixel array
    };

    fileHeader[ 0] = (unsigned char)('B');
    fileHeader[ 1] = (unsigned char)('M');
    fileHeader[ 2] = (unsigned char)(fileSize      );
    fileHeader[ 3] = (unsigned char)(fileSize >>  8);
    fileHeader[ 4] = (unsigned char)(fileSize >> 16);
    fileHeader[ 5] = (unsigned char)(fileSize >> 24);
    fileHeader[10] = (unsigned char)(FILE_HEADER_SIZE + INFO_HEADER_SIZE);

    return fileHeader;
}

unsigned char* createBitmapInfoHeader (int height, int width)
{
    static unsigned char infoHeader[] = {
        0,0,0,0, /// header size
        0,0,0,0, /// image width
        0,0,0,0, /// image height
        0,0,     /// number of color planes
        0,0,     /// bits per pixel
        0,0,0,0, /// compression
        0,0,0,0, /// image size
        0,0,0,0, /// horizontal resolution
        0,0,0,0, /// vertical resolution
        0,0,0,0, /// colors in color table
        0,0,0,0, /// important color count
    };

    infoHeader[ 0] = (unsigned char)(INFO_HEADER_SIZE);
    infoHeader[ 4] = (unsigned char)(width      );
    infoHeader[ 5] = (unsigned char)(width >>  8);
    infoHeader[ 6] = (unsigned char)(width >> 16);
    infoHeader[ 7] = (unsigned char)(width >> 24);
    infoHeader[ 8] = (unsigned char)(height      );
    infoHeader[ 9] = (unsigned char)(height >>  8);
    infoHeader[10] = (unsigned char)(height >> 16);
    infoHeader[11] = (unsigned char)(height >> 24);
    infoHeader[12] = (unsigned char)(1);
    infoHeader[14] = (unsigned char)(BYTES_PER_PIXEL*8);

    return infoHeader;
}



static void createBitmap(const uint8_t qrcode[])
{
    int height = 21;
    int width = 21;
    unsigned char image[height][width][BYTES_PER_PIXEL];
    char* imageFileName = (char*) "qrcode.bmp";

    int i, j;
    for (i = 0; i < height; i++) {
        for (j = 0; j < width; j++) {
        	if (qrcodegen_getModule(qrcode, i, j))
        	{
	            image[height - 1 - i][j][2] = (unsigned char) (0);	///red
	            image[height - 1 - i][j][1] = (unsigned char) (0);	///green
	            image[height - 1 - i][j][0] = (unsigned char) (0);	///blue
        	}
        	else
        	{
	            image[height - 1 - i][j][2] = (unsigned char) (255);	///red
	            image[height - 1 - i][j][1] = (unsigned char) (255);	///green
	            image[height - 1 - i][j][0] = (unsigned char) (255);	///blue
        	}
        }
    }

    generateBitmapImage((unsigned char*) image, height, width, imageFileName);
    printf("\nStep 1: Generating QrCode bitmap file completed !\n\n");
}



// Creates a single QR Code, then prints it to the console.
static void doBasicDemo(char *txtToEncrypt)
{
	enum qrcodegen_Ecc errCorLvl = qrcodegen_Ecc_LOW;  // Error correction level
	
	// Make and print the QR Code symbol
	uint8_t qrcode[qrcodegen_BUFFER_LEN_MAX];
	uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
	bool ok = qrcodegen_encodeText(txtToEncrypt, tempBuffer, qrcode, errCorLvl,
		qrcodegen_VERSION_MIN, qrcodegen_VERSION_MAX, qrcodegen_Mask_AUTO, true);
	if (ok)
	{
		createBitmap(qrcode);
	}
}



void readQrcode(char *fileName, int width, int height)
{
	FILE *fp;
	unsigned char buffer[3];
	int qrcode[height][width];
	int i;

	printf("Step 2: Read QrCode file: %s\n", fileName);

	fp = fopen(fileName, "rb");

	fseek(fp, 53, SEEK_SET);

	i = 0;
	while(fread(buffer, sizeof(unsigned char), 3, fp), i < width * height)
	{
		if (i % 21 == 0)
		{
			fread(buffer, sizeof(unsigned char), 1, fp);
		}

		if (buffer[0] == 255 && buffer[1] == 255 && buffer[2] == 255)
			qrcode[i / 21][i % 21] = 0;
		else
			qrcode[i / 21][i % 21] = 1;

		i++;
	}

	printf("\n\n");

	for (int j = height - 1; j >= 0; j--)
	{
		for (int k = 0; k < width; k++)
		{
			if (qrcode[j][k] == 1)
				printf("##");
			else
				printf("  ");
		}
		printf("\n");
	}
    printf("\n");

	return;
}



int main(int argc, char const *argv[])
{
    // Create a QrCode bitmap file
	doBasicDemo("Voici un QrCode!");

    // Read the QrCode bitmap file
	readQrcode("qrcode.bmp", 21, 21);

	return 0;
}