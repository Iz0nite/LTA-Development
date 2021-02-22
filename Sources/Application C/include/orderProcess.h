#ifndef _ORDERPROCESS_H_
# define _ORDERPROCESS_H_

# include <stdio.h>
# include <stdlib.h>
# include <string.h>
# include <math.h>
# include "sql.h"

typedef struct s_package
{
	int weight;
	int volume;
	char *address;
	char *city;
	char *emailDest;
	struct s_package *next;
} t_package;

t_package *getDataExcelFile(char *filename, t_package *package);

t_package *addChain(t_package *package, char **data);

int sendOrder(int idUser, char *deliveryType, t_package *package, char *idDeposit, char **log);

#endif