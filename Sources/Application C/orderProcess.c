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
                printf("buildDataPosition: %d => \\0\n", buildDataPosition);
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



int sendOrder(int idUser, char *deliveryType, t_package *package, char **log)
{
	t_package *packageTmp;
	char ***tabPrice;
	double tmpPrice;
	double price;
	int tabPriceNbRowElement;
	int check;
	int tmpWeight;
	int tmpRow;
	int idOrder;

	price = 0;
	packageTmp = package;
	tabPrice = getPrice(deliveryType, &tabPriceNbRowElement, &log);

	for(int i = 0; i < tabPriceNbRowElement; i++)
		printf("%s g - %s euros\n", tabPrice[i][0], tabPrice[i][1]);

	if(tabPrice)
	{
		while(packageTmp->next != NULL)
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

			printf("%.2lf\n", tmpPrice);
			price += tmpPrice;
			packageTmp = packageTmp->next;
		}

		check = 0;

		for(int i = 0; i < tabPriceNbRowElement; i++)
		{
			tmpRow = i;
			tmpWeight = atoi(tabPrice[i][0]);

			if(packageTmp->weight * 1000 <= tmpWeight && check != 1)
			{
				check = 1;
				tmpPrice = atof(tabPrice[i][1]);
			}
		}

		if(!check){
			tmpPrice = atof(tabPrice[tmpRow][1]);
			
			if(tmpPrice == -1)
			{
				strcpy(*log, "<span foreground='red'>");
				strcat(*log, "Your package is too heavy for an express delivery!");
				strcat(*log, "</span>");
				return 0;
			}
		}

		printf("%.2lf\n", tmpPrice);
		price += tmpPrice;

		printf("total: %lf\n", price);

		idOrder = addNewOrder(idUser, deliveryType, price, &log);

		if(!idOrder)
			return 0;

		printf("idOrder: %d\n", idOrder);

		return 1;
	}

	return 0;
}