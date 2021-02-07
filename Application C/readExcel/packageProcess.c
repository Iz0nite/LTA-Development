#include "packageProcess.h"

#define PATH "./excelFile/"

t_package *addChain(t_package *package, unsigned char **data)
{
	t_package *newPackage;

	newPackage = malloc(sizeof(t_package));
	newPackage->deliveryType = malloc(sizeof(char) * 20);
	newPackage->address = malloc(sizeof(char) * 256);
	newPackage->city = malloc(sizeof(char) * 60);
	newPackage->emailDest = malloc(sizeof(char) * 256);

	newPackage->weight = atoi(data[0]);
	newPackage->volume = atoi(data[1]);
	strcpy(newPackage->deliveryType, data[2]);
	strcpy(newPackage->address, data[3]);
	strcpy(newPackage->city, data[4]);
	strcpy(newPackage->emailDest, data[5]);

	if (!package)
	{
		newPackage->next = NULL;
		return newPackage;
	}
	else
	{
		newPackage->next = package;
		return newPackage;
	}
}



t_package *getDataExcelFile(char *filename, t_package *package)
{
	FILE *fp;
	unsigned char buffer[2];
	unsigned char **data;
	char *pathFile;
	int startProcess;
	int dataPosition;

	startProcess = 0;
	dataPosition = 0;

	data = malloc(sizeof(char *) * 6);
	for(int i= 0; i < 6; i++)
		data[i] = malloc(sizeof(char) * 256);

	pathFile = malloc(sizeof(char) * 256);

	strcpy(pathFile, PATH);
	strcat(pathFile, filename);

	fp = fopen(pathFile, "rb");

	if(fp)
	{
		strcpy(data[0], "");
		strcpy(data[1], "");
		strcpy(data[2], "");
		strcpy(data[3], "");
		strcpy(data[4], "");
		strcpy(data[5], "");

		while(fread(buffer, sizeof(char), 1, fp), !feof(fp))
		{
			buffer[1] = '\0';

			if(buffer[0] == '\n' && !startProcess)
				startProcess = 1;

			if(buffer[0] == ';' || buffer[0] == '\n')
				dataPosition++;

			if(startProcess && buffer[0] != ';' && buffer[0] != '\n')
				strcat(data[dataPosition % 6], buffer);

			if ((dataPosition % 6 == 0) && dataPosition > 6 && buffer[0] == '\n')
			{
				package = addChain(package, data);

				strcpy(data[0], "");
				strcpy(data[1], "");
				strcpy(data[2], "");
				strcpy(data[3], "");
				strcpy(data[4], "");
				strcpy(data[5], "");
			}
		}
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