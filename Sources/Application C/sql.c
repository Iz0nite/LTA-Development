#include <sql.h>

int setupMysqlConexion(MYSQL *mysql)
{
	mysql_init(mysql);
	mysql_options(mysql,MYSQL_READ_DEFAULT_GROUP,"option");

	if(mysql_real_connect(mysql, "51.77.144.219", "armanddfl", "Sa4L5V6ve", "LTA", 0, NULL, 0))
	{
		if(mysql_set_character_set(mysql, "utf8"))
			printf("Set encoding to utf-8 failed!\n");
		
		return 1;
	}

	return 0;
}



int getId(char *email, char **log)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char query[256];

	if(setupMysqlConexion(&mysql))
	{
		strcpy(query, "SELECT idUser FROM USERS WHERE email = '");
		strcat(query, email);
		strcat(query, "'");

		mysql_query(&mysql, query);

		resultSql = mysql_store_result(&mysql);

		if(resultSql)
		{
			rowSql = mysql_fetch_row(resultSql);
			if(rowSql)
			{
				mysql_close(&mysql);
				return atoi(rowSql[0]);
			}
		}

		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "The email is incorrect or does not exist!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return 0;
	}
	else
	{
		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Cannot connect to the database!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return 0;
	}
}



char *getUserData(int id, char *key, char **log)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char query[256];
	char stringId[5];

	if(setupMysqlConexion(&mysql))
	{
		itoa(id, stringId, 10);
		strcpy(query, "SELECT ");
		strcat(query, key);
		strcat(query, " FROM USERS WHERE idUser = '");
		strcat(query, stringId);
		strcat(query, "'");

		mysql_query(&mysql, query);

		resultSql = mysql_store_result(&mysql);

		if(resultSql)
		{
			rowSql = mysql_fetch_row(resultSql);
			if(rowSql)
			{
				strcpy(*log, "<span foreground='#10ac84'>");
				strcat(*log, "Please connect to the website and then resend\n the connection verification to the application.");
				strcat(*log, "</span>");

				mysql_close(&mysql);
				return rowSql[0];
			}
		}

		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Incorrect id user!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
	else
	{
		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Cannot connect to the database!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
}



void setUserData(int id, char *key, char *data)
{
	MYSQL mysql;
	char query[256];
	char stringId[5];

	if(setupMysqlConexion(&mysql))
	{
		itoa(id, stringId, 10);
		strcpy(query, "UPDATE USERS SET ");
		strcat(query, key);
		strcat(query, "='");
		strcat(query, data);
		strcat(query, "' WHERE idUser='");
		strcat(query, stringId);
		strcat(query, "'");

		mysql_query(&mysql, query);
	}
}



char ***getPrice(char *typeDelivery, int *nbRow, char ***log)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char ***tabPrice;
	char query[256];
	int commaPosition;

	if(setupMysqlConexion(&mysql))
	{
		strcpy(query, "SELECT weight, price FROM PRICE where deliveryType='");
		strcat(query, typeDelivery);
		strcat(query, "'");

		mysql_query(&mysql, query);

		resultSql = mysql_store_result(&mysql);

		if(resultSql)
		{
			*nbRow = (int)mysql_num_rows(resultSql);

			tabPrice = malloc(sizeof(char **) * *nbRow);
			for (int i = 0; i < *nbRow; i++)
			{
				tabPrice[i] = malloc(sizeof(char *) * 2);
				for (int j = 0; j < 2; j++)
					tabPrice[i][j] = malloc(sizeof(char) * 10 + 1);
			}

			for (int i = 0; i < *nbRow; i++)
			{
				rowSql = mysql_fetch_row(resultSql);

				strcpy(tabPrice[i][0], rowSql[0]);
				strcpy(tabPrice[i][1], rowSql[1]);

				commaPosition = 0;
				while(tabPrice[i][1][commaPosition] != '.')
					commaPosition++;

				tabPrice[i][1][commaPosition] = ',';
			}
			
			mysql_close(&mysql);
			return tabPrice;
		}

		strcpy(**log, "<span foreground='red'>");
		strcat(**log, "An error has been occurred!");
		strcat(**log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
	else
	{
		strcpy(**log, "<span foreground='red'>");
		strcat(**log, "Cannot connect to the database!");
		strcat(**log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
}



int addNewOrder(int idUser, char *deliveryType, double price, char ***log)
{
	MYSQL mysql;
	char query[256];
	char txtPrice[10];
	char txtId[5];
	int idOrder;
	int commaPosition;

	commaPosition = 0;

	itoa(idUser, txtId, 10);
	gcvt(price, 4, txtPrice);
	
	while(txtPrice[commaPosition] != ',')
		commaPosition++;
	txtPrice[commaPosition] = '.';
	
	if(setupMysqlConexion(&mysql))
	{
		printf("connection ok\n");
		strcpy(query, "INSERT INTO `ORDER`(deliveryStatus, paymentType, deliveryType, total, idUser) VALUES('0', '0', '");
		strcat(query, deliveryType);
		strcat(query, "', '");
		strcat(query, txtPrice);
		strcat(query, "', '");
		strcat(query, txtId);
		strcat(query, "')");

		mysql_query(&mysql, query);
		idOrder = mysql_insert_id(&mysql);
		
		mysql_close(&mysql);

		return idOrder;
	}
	else
	{
		strcpy(**log, "<span foreground='red'>");
		strcat(**log, "Cannot connect to the database!");
		strcat(**log, "</span>");
		mysql_close(&mysql);

		return 0;
	}
}



char ***getListDeposit(int *nbRow)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char query[256];
	char ***listDeposit;

	if(setupMysqlConexion(&mysql))
	{
		strcpy(query,"SELECT idDeposit, address, city FROM DEPOSITS");
		mysql_query(&mysql,query);

        resultSql = mysql_store_result(&mysql);

        if(resultSql)
		{
			*nbRow = (int)mysql_num_rows(resultSql);

			listDeposit = malloc(sizeof(char **) * *nbRow);
			for (int i = 0; i < *nbRow; i++)
			{
				listDeposit[i] = malloc(sizeof(char *) * 2);
				listDeposit[i][0] = malloc(sizeof(char) * 5);
				listDeposit[i][1] = malloc(sizeof(char) * 512);
			}

			for (int i = 0; i < *nbRow; i++)
			{
				rowSql = mysql_fetch_row(resultSql);

				strcpy(listDeposit[i][0], rowSql[0]);
				strcpy(listDeposit[i][1], rowSql[1]);
				strcat(listDeposit[i][1], " ");
				strcat(listDeposit[i][1], rowSql[2]);
			}

			mysql_close(&mysql);
			return listDeposit;
		}

		printf("An error has been occurred!\n");
		mysql_close(&mysql);
		return NULL;
	}
	else
	{
		printf("Cannot connect to the database!\n");
		mysql_close(&mysql);
		return NULL;
	}

}