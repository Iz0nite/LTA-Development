#include <orderProcess.h>

t_package *addChain(t_package *package, char **data)
{
	t_package *newPackage;

	newPackage = malloc(sizeof(t_package));
	newPackage->address = malloc(sizeof(char) * 256);
	newPackage->city = malloc(sizeof(char) * 128);
	newPackage->emailDest = malloc(sizeof(char) * 256);

	newPackage->weight = atoi(data[0]);
	newPackage->volume = atoi(data[1]);
	strcpy(newPackage->address, data[2]);
	strcpy(newPackage->city, data[3]);
	strcpy(newPackage->emailDest, data[4]);

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
    char buffer;
    char **data;
	char *pathFile;
	int startProcess;
	int dataPosition;
	int buildDataPosition;

	startProcess = 0;
	dataPosition = 0;
	buildDataPosition = 0;

	data = malloc(sizeof(char *) * 5);
	for(int i= 0; i < 5; i++)
		data[i] = malloc(sizeof(char) * 256);

	pathFile = malloc(sizeof(char) * 512);

	strcpy(pathFile, filename);

	fp = fopen(pathFile, "rb");

	if(fp)
	{
		strcpy(data[0], "");
		strcpy(data[1], "");
		strcpy(data[2], "");
		strcpy(data[3], "");
		strcpy(data[4], "");

		while(fread(&buffer, sizeof(char), 1, fp), !feof(fp))
		{
			if(buffer == '\n' && !startProcess)
				startProcess = 1;

            if(buffer == ';' || buffer == '\n')
            {
                data[dataPosition % 5][buildDataPosition] = '\0';
                buildDataPosition = 0;
                dataPosition++;
            }

			if(startProcess && buffer != ';' && buffer != '\n' && buffer != '\r')
            {
                if(buffer == '\'')
                {
                    data[dataPosition % 5][buildDataPosition] = '\\';
                    buildDataPosition++;
                }
                data[dataPosition % 5][buildDataPosition] = buffer;
                buildDataPosition++;
            }

			if ((dataPosition % 5 == 0) && dataPosition > 5 && buffer == '\n')
			{
				package = addChain(package, data);

				strcpy(data[0], "");
				strcpy(data[1], "");
				strcpy(data[2], "");
				strcpy(data[3], "");
				strcpy(data[4], "");
			}
		}
	}
	else
	{
		printf("error: open file failed\n");
		free(pathFile);
		free(data);
		fclose(fp);

		return NULL;
	}

	free(pathFile);
	free(data);
	fclose(fp);

	return package;
}



int sendOrder(int idUser, char *deliveryType, t_package *package, char *idDeposit, char **log)
{
	t_package *packageTmp;
	char ***tabPrice;
	double tmpPrice;
	double price;
	char txtWeight[10];
	char txtVolumeSize[10];
	char txtIdOrder[10];
	int tabPriceNbRowElement;
	int check;
	int tmpWeight;
	int tmpRow;
	int idOrder;

	price = 0;
	packageTmp = package;
	tabPrice = getPrice(deliveryType, &tabPriceNbRowElement, &log);

	if(tabPrice)
	{
		while(packageTmp != NULL)
		{
			check = 0;

			for(int i = 0; i < tabPriceNbRowElement; i++)
			{
				tmpRow = i;
				tmpWeight = atoi(tabPrice[i][0]);

				if(packageTmp->weight * 1000 <= tmpWeight && check != 1){
					check = 1;
					tmpPrice = atof(tabPrice[i][1]);
				}
			}

			if(!check)
			{
				tmpPrice = atof(tabPrice[tmpRow][1]);
				
				if(tmpPrice == -1)
				{
					strcpy(*log, "<span foreground='red'>");
					strcat(*log, "Your package is too heavy for an express delivery!");
					strcat(*log, "</span>");
					return 0;
				}
			}

			price += tmpPrice;
			packageTmp = packageTmp->next;
		}

		idOrder = addNewOrder(idUser, deliveryType, price, &log);

		if(!idOrder)
			return 0;

        packageTmp = package;
        while(packageTmp != NULL)
        {
            itoa(packageTmp->weight * 1000, txtWeight, 10);
            itoa(packageTmp->volume, txtVolumeSize, 10);
            itoa(idOrder, txtIdOrder, 10);
            addPackage(txtWeight, txtVolumeSize, packageTmp->emailDest, packageTmp->address, packageTmp->city, txtIdOrder, idDeposit, &log);
            packageTmp = packageTmp->next;
        }

        return idOrder;
	}

	return 0;
}