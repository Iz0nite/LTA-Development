#include "sql.h"

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



int getId(char *email, char **error)
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

		strcpy(*error, "The email is incorrect or does not exist!");
		mysql_close(&mysql);
		return 0;
	}
	else
	{
		strcpy(*error, "Cannot connect to the database!");
		mysql_close(&mysql);
		return 0;
	}
}



char *getUserData(int id, char *key, char **error)
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
				mysql_close(&mysql);
				return rowSql[0];
			}
		}

		strcpy(*error, "Incorrect id user!");
		mysql_close(&mysql);
		return NULL;
	}
	else
	{
		strcpy(*error, "Cannot connect to the database!");
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