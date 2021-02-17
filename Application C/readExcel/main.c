#include "packageProcess.h"

void display(t_package *package)
{
	t_package *packageTmp;

	packageTmp = package;

	printf("--- DISPLAY STRUCT ---\n");
	while(packageTmp->next != NULL)
	{
		printf("weight: %d\n", packageTmp->weight);
		printf("volume: %d\n", packageTmp->volume);
		printf("type of delivery: %s\n", packageTmp->deliveryType);
		printf(" address of the receiver%s\n", packageTmp->address);
		printf("city of the receiver%s\n", packageTmp->city);
		printf("email of the receiver%s\n\n", packageTmp->emailDest);
		packageTmp = packageTmp->next;
	}

	printf("weight: %d\n", packageTmp->weight);
	printf("volume: %d\n", packageTmp->volume);
	printf("type of delivery: %s\n", packageTmp->deliveryType);
	printf(" address of the receiver%s\n", packageTmp->address);
	printf("city of the receiver%s\n", packageTmp->city);
	printf("email of the receiver%s\n\n", packageTmp->emailDest);

	return;
}



int main(int argc, char const **argv)
{
	t_package *package;

	package = malloc(sizeof(t_package));
	package = NULL;

	package = getDataExcelFile("test.csv", package);

	display(package);
	
	return 0;
}