#ifndef _PACKAGEPROCESS_H_
# define _PACKAGEPROCESS_H_

# include <stdio.h>
# include <stdlib.h>
# include <string.h>

typedef struct s_package
{
	int weight;
	int volume;
	char *deliveryType;
	char *address;
	char *city;
	char *emailDest;
} t_package;

t_package *getDataExcelFile(char *filename);

#endif