#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <MYSQL/mysql.h>
#include <math.h>

#define PATH "./excelFile/"

typedef struct s_package
{
	int weight;
	int volume;
	char *address;
	char *city;
	char *emailDest;
	struct s_package *next;
} t_package;

t_package *addChain(t_package *package, char **data)
{
	t_package *newPackage;

	newPackage = malloc(sizeof(t_package));
	newPackage->address = malloc(sizeof(char) * 256);
	newPackage->city = malloc(sizeof(char) * 60);
	newPackage->emailDest = malloc(sizeof(char) * 256);

	newPackage->weight = atoi(data[0]);
	newPackage->volume = atoi(data[1]);
	strcpy(newPackage->address, data[2]);
	strcpy(newPackage->city, data[3]);
	strcpy(newPackage->emailDest, data[4]);
	newPackage->emailDest[strlen(newPackage->emailDest)-1] = '\0';

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
    char buffer[2];
    char **data;
	char *pathFile;
	int startProcess;
	int dataPosition;

	startProcess = 0;
	dataPosition = 0;

	data = malloc(sizeof(char *) * 5);
	for(int i= 0; i < 5; i++)
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

		while(fread(buffer, sizeof(char), 1, fp), !feof(fp))
		{
			buffer[1] = '\0';

			if(buffer[0] == '\n' && !startProcess)
				startProcess = 1;

			if(buffer[0] == ';' || buffer[0] == '\n')
				dataPosition++;

			if(startProcess && buffer[0] != ';' && buffer[0] != '\n')
            {
                if(buffer[0] == '\'')
                    strcat(data[dataPosition % 5], "\\");
                strcat(data[dataPosition % 5], buffer);
            }


			if ((dataPosition % 5 == 0) && dataPosition > 5 && buffer[0] == '\n')
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

		return NULL;
	}

	free(pathFile);

	return package;
}

void display(t_package *package)
{
	t_package *packageTmp;

	packageTmp = package;

	printf("--- DISPLAY STRUCT ---\n");
	while(packageTmp->next != NULL)
	{
		printf("weight: %d\n", packageTmp->weight);
		printf("volume: %d\n", packageTmp->volume);
		printf("address of the receiver%s\n", packageTmp->address);
		printf("city of the receiver%s\n", packageTmp->city);
		printf("email of the receiver%s\n\n", packageTmp->emailDest);
		printf("len: %d",strlen(packageTmp->emailDest));
		packageTmp = packageTmp->next;
	}

	printf("weight: %d\n", packageTmp->weight);
	printf("volume: %d\n", packageTmp->volume);
	printf("address of the receiver%s\n", packageTmp->address);
	printf("city of the receiver%s\n", packageTmp->city);
	printf("email of the receiver%s\n\n", packageTmp->emailDest);

	return;
}

int sendOrder(int choice,t_package *package){

    char txtChoice[10];
    t_package *packageTmp;
    int check;
    char query[255];
    double tmpPrice;
    double price=0;
    char txtPrice[10];
    int tmpWeight;
    int idOrder;
    MYSQL_RES *result=NULL;
    MYSQL_ROW row;
    MYSQL_ROW rowTmp;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","armanddfl","Sa4L5V6ve","LTA",0,NULL,0)){



        packageTmp = package;

        while(packageTmp->next != NULL){
            strcpy(query,"SELECT weight,price FROM PRICE where deliveryType='");
            itoa(choice,txtChoice,10);
            strcat(query,txtChoice);
            strcat(query,"'");

            mysql_query(&mysql,query);
            result = mysql_use_result(&mysql);

            check=0;

            while((row = mysql_fetch_row(result))){
                rowTmp=row;

                tmpWeight=atoi(row[0]);
                //printf("%d %d\n",(packageTmp->weight)*1000,tmpWeight);
                if((packageTmp->weight)*1000<=tmpWeight && check!=1){
                    check=1;
                    tmpPrice=atof(row[1]);
                }

            }

            if(check == 0){
                tmpPrice=atof(rowTmp[1]);
                if(tmpPrice == -1){
                    printf("Your package is too heavy for an express delivery\n");
                    return -1;
                }
            }

            printf("%lf ",tmpPrice);
            price+=tmpPrice;
            packageTmp = packageTmp->next;
        }

        check=0;

        mysql_query(&mysql,query);
        result = mysql_use_result(&mysql);
        while((row = mysql_fetch_row(result))){

            tmpWeight=atoi(row[0]);

            //printf("%d %d\n",(packageTmp->weight)*1000,tmpWeight);
            if((packageTmp->weight)*1000<=tmpWeight && check!=1){
                check=1;
                tmpPrice=atof(row[1]);
            }

        }

        if(check == 0){
            tmpPrice=atof(rowTmp[1]);
            if(tmpPrice == -1){
                printf("Your package is too heavy for an express delivery\n");
                return -1;
            }
        }

        price+=tmpPrice;
        printf("%lf ",tmpPrice);
        printf("%lf ",price);

        strcpy(query,"INSERT INTO `ORDER`(deliveryStatus,deliveryType,total,idUser) VALUES('0','");
        strcat(query,txtChoice);
        strcat(query,"','");
        gcvt(price,4,txtPrice);
        strcat(query,txtPrice);
        strcat(query,"','69')");

        mysql_query(&mysql,query);
        //printf("\n%s",query);

        strcpy(query,"SELECT LAST_INSERT_ID() FROM `ORDER`");
        mysql_query(&mysql,query);

        result = mysql_store_result(&mysql);
        row = mysql_fetch_row(result);

        if(row){
           idOrder=atoi(row[0]);
        }

        mysql_close(&mysql);
        return idOrder;
    }else{
        printf("ERROR:");
        return -1;

    }

}

void sendPackages(int idOrder,t_package *package){

    char txtIdOrder[10];
    int choice;
    int count_row=0;
    int check;
    char query[255];
    char **tabDeposit;
    char txtWeight[10];
    char txtVolume[10];
    int i;
    t_package *packageTmp;
    MYSQL_RES *result=NULL;
    MYSQL_ROW row;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","armanddfl","Sa4L5V6ve","LTA",0,NULL,0)){

    //printf("%s",idOrder);
        itoa(idOrder,txtIdOrder,10);
        strcpy(query,"SELECT * FROM DEPOSITS");
        mysql_query(&mysql,query);

        result = mysql_use_result(&mysql);
        while((row = mysql_fetch_row(result))){
            count_row++;
        }

        tabDeposit=malloc(sizeof(char*)*count_row);
        if(tabDeposit!=NULL){
            for(i=0;i<count_row;i++){
                tabDeposit[i]=malloc(sizeof(char)*11);
            }
        }

        check=0;

        do{
            count_row=0;
            strcpy(query,"SELECT * FROM DEPOSITS");
            mysql_query(&mysql,query);
            result = mysql_use_result(&mysql);

            printf("Choose a deposit\n");

            while((row = mysql_fetch_row(result))){
                printf("[%d] %s %s\n",count_row+1,row[1],row[2]);
                if(check==0){
                    strcpy(tabDeposit[count_row],row[0]);
                }
                count_row++;
            }

            check=1;

            scanf("%d",&choice);

        }while(choice<0 || choice>count_row);


        packageTmp = package;

        display(package);

        while(packageTmp->next != NULL)
        {
            strcpy(query,"INSERT INTO PACKAGES (weight,volumeSize,emailDest,address,city,status,idOrder,idDeposit) VALUES ('");
            itoa((packageTmp->weight)*1000,txtWeight,10);
            strcat(query,txtWeight);
            strcat(query,"','");
            itoa(packageTmp->volume,txtVolume,10);
            strcat(query,txtVolume);
            strcat(query,"','");
            strcat(query,packageTmp->emailDest);
            strcat(query,"','");
            strcat(query,packageTmp->address);
            strcat(query,"','");
            strcat(query,packageTmp->city);
            strcat(query,"','0','");
            strcat(query,txtIdOrder);
            strcat(query,"','");
            strcat(query,tabDeposit[choice-1]);
            strcat(query,"')");



            mysql_query(&mysql,query);
            packageTmp = packageTmp->next;
        }

        strcpy(query,"INSERT INTO PACKAGES (weight,volumeSize,emailDest,address,city,status,idOrder,idDeposit) VALUES ('");
        itoa((packageTmp->weight)*1000,txtWeight,10);
        strcat(query,txtWeight);
        strcat(query,"','");
        itoa(packageTmp->volume,txtVolume,10);
        strcat(query,txtVolume);
        strcat(query,"','");
        strcat(query,packageTmp->emailDest);
        strcat(query,"','");
        strcat(query,packageTmp->address);
        strcat(query,"','");
        strcat(query,packageTmp->city);
        strcat(query,"','0','");
        strcat(query,txtIdOrder);
        strcat(query,"','");
        strcat(query,tabDeposit[choice-1]);
        strcat(query,"')");

        printf("%s",query);
        mysql_query(&mysql,query);

        mysql_close(&mysql);
        free(tabDeposit);
    }

}

int main()
{
    int choice;
    t_package *package;
    int idOrder;

    package = malloc(sizeof(t_package));
    package = NULL;

    package = getDataExcelFile("test.csv", package);

    do{
        do{
            printf("Enter your delivery type\n1) Express\n2) Standard\n");
            scanf("%d",&choice);
        }while(choice!=1 && choice!=2);

        if(choice==2){
            choice=0;
        }

        idOrder=sendOrder(choice,package);

    }while(idOrder == -1);

    sendPackages(idOrder,package);


    return 0;
}
