#include "packageProcess.h"

int main(int argc, char const **argv)
{
	t_package *package;

	package = malloc(sizeof(t_package));

	package = getDataExcelFile("test.csv");

	if (package)
	{
		printf("--- DATA STRUCT ---\n");
		printf("Weight: %d\n", package->weight);
		printf("Volume: %d\n", package->volume);
		printf("Delivery type: %s\n", package->deliveryType);
		printf("Depot address: %s\n", package->address);
		printf("Depot city: %s\n", package->city);
		printf("Email of the receiver: %s\n", package->emailDest);
	}
	
	return 0;
}