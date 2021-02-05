#include "packageProcess.h"

#define PATH "./excelFile/"

t_package *getDataExcelFile(char *filename)
{
	FILE *fp;
	t_package *package;
	unsigned char buffer[2];
	unsigned char *key;
	unsigned char *data;
	char *pathFile;

	pathFile = malloc(sizeof(char) * 256);

	strcpy(pathFile, PATH);
	strcat(pathFile, filename);

	fp = fopen(pathFile, "rb");

	if(fp)
	{
		key = malloc(sizeof(char) * 60);
		data = malloc(sizeof(char) * 256);

		package = malloc(sizeof(t_package));
		package->deliveryType = malloc(sizeof(char) * 20);
		package->address = malloc(sizeof(char) * 256);
		package->city = malloc(sizeof(char) * 60);
		package->emailDest = malloc(sizeof(char) * 256);

		strcpy(key, "");
		while(fread(buffer, sizeof(unsigned char), 1, fp), !feof(fp))
		{
			buffer[1] = '\0';

			if(buffer[0] == ';')
			{
				strcpy(data, "");

				while(fread(buffer, sizeof(unsigned char), 1, fp), buffer[0] != '\n')
				{
					buffer[1] = '\0';

					if (buffer[0] != '\n')
						strcat(data, buffer);
				}

				if(!strcmp(key, "Package weight"))
					package->weight = atoi(data);
				else if(!strcmp(key, "Package volume size"))
					package->volume = atoi(data);
				else if(!strcmp(key, "Type of delivery (standard or express)"))
					strcpy(package->deliveryType, data);
				else if(!strcmp(key, "Depot address"))
					strcpy(package->address, data);
				else if(!strcmp(key, "Depot city"))
					strcpy(package->city, data);
				else if(!strcmp(key, "Email of the receiver"))
					strcpy(package->emailDest, data);

				strcpy(key, "");
			}
			else
				strcat(key, buffer);
		}

		free(key);
		free(data);
	}
	else
	{
		printf("error: open file failed\n");
		free(pathFile);

		return NULL;
	}

	free(pathFile);
	
	return package;
}