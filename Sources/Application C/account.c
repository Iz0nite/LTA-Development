#include "account.h"

G_MODULE_EXPORT void signIn(GtkButton *btn_submitConnection, t_widgets *widgets)
{
    char httpRequest[255];
	char *email;
	char *error;
	int connectionStatus;

	connectionStatus = 0;
	error = malloc(sizeof(char) * 2000);

	strcpy(error, "");
	gtk_label_set_text(GTK_LABEL(widgets->lbl_messageError), error);

    email = (char *)gtk_entry_get_text(GTK_ENTRY(widgets->entry_email));
	widgets->id = getId(email, &error);
	printf("Id user: %d\n", widgets->id);

	if (strcmp(error, ""))
		gtk_label_set_text(GTK_LABEL(widgets->lbl_messageError), error);

	if (widgets->id)
	{
		connectionStatus = atoi(getUserData(widgets->id, "appSignIn", &error));

		if (strcmp(error, ""))
			gtk_label_set_text(GTK_LABEL(widgets->lbl_messageError), error);

		if (!connectionStatus)
		{
			strcpy(httpRequest, "start https://lta-development.fr/en/appConnection");
			system(httpRequest);
		}
		else
		{
			printf("ok\n");
		}

		printf("Connection status: %d\n", connectionStatus);
	}
}
