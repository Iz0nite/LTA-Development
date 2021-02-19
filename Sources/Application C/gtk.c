#include <gtk.h>

GtkWidget *window;

G_MODULE_EXPORT void logOut(GtkButton *btn_submit, t_data *data)
{
	setUserData(data->id, "appSignIn", "0");
	data->id = 0;

	gtk_container_remove(GTK_CONTAINER(window), data->windowMenu->box);
	gtk_container_add(GTK_CONTAINER(window), data->windowConnection->box);

	return;
}



G_MODULE_EXPORT void signIn(GtkButton *btn_submit, t_data *data)
{
    char httpRequest[255];
	char *email;
	char *log;
	int connectionStatus;

	log = malloc(sizeof(char) * 2000);

	strcpy(log, "");
	gtk_label_set_text(GTK_LABEL(data->windowConnection->lbl_messageError), log);

    email = (char *)gtk_entry_get_text(GTK_ENTRY(data->windowConnection->entry_email));
	data->id = getId(email, &log);

	if(strcmp(log, ""))
	{
		gtk_label_set_markup (GTK_LABEL(data->windowConnection->lbl_messageError), log);
		strcpy(log, "");
	}

	if (data->id)
	{
		connectionStatus = atoi(getUserData(data->id, "appSignIn", &log));

		if (strcmp(log, ""))
		{
			gtk_label_set_markup (GTK_LABEL(data->windowConnection->lbl_messageError), log);
			strcpy(log, "");
		}

		if (!connectionStatus)
		{
			strcpy(httpRequest, "start https://lta-development.fr/en/appConnection");
			system(httpRequest);
		}	
		else
		{
			gtk_label_set_markup (GTK_LABEL(data->windowConnection->lbl_messageError), log);
			gtk_container_remove(GTK_CONTAINER(window), data->windowConnection->box);
			gtk_container_add(GTK_CONTAINER(window), data->windowMenu->box);
		}
	}

	free(log);

	return;
}



G_MODULE_EXPORT void uploadFile(GtkButton *btn_submit, t_data *data)
{
	gtk_container_remove(GTK_CONTAINER(window), data->windowMenu->box);
	gtk_container_add(GTK_CONTAINER(window), data->windowUploadFile->box);

	return;
}



/*------------------------------------------------------------------------*/
void display(t_package *package)
{
	t_package *packageTmp;
    
	packageTmp = package;

	if (package)
	{
		printf("--- DISPLAY STRUCT ---\n");
		while(packageTmp->next != NULL)
		{
            printf("weight: %d\n", packageTmp->weight);
            printf("volume: %d\n", packageTmp->volume);
            printf("address of the receiver: %s\n", packageTmp->address);
            printf("city of the receiver: %s\n", packageTmp->city);
            printf("email of the receiver: %s\n\n", packageTmp->emailDest);
            packageTmp = packageTmp->next;
        }

		printf("weight: %d\n", packageTmp->weight);
		printf("volume: %d\n", packageTmp->volume);
		printf("address of the receiver: %s\n", packageTmp->address);
		printf("city of the receiver: %s\n", packageTmp->city);
		printf("email of the receiver: %s\n\n", packageTmp->emailDest);
	}
	else
	{
		printf("No data!\n");
	}

	return;
}
/*------------------------------------------------------------------------*/



G_MODULE_EXPORT void createOrder(GtkButton *btn_submit, t_data *data)
{
	t_package *package;
	char *pathFile;
	char *log;

	log = malloc(sizeof(char) * 2000);
	package = malloc(sizeof(t_package));
	package = NULL;

	strcpy(log, "");

	pathFile = (char *)gtk_file_chooser_get_preview_filename(GTK_FILE_CHOOSER(data->windowUploadFile->inputFile));

	if(!pathFile)
	{
		strcpy(log, "<span foreground='red'>");
		strcat(log, "No file selected!");
		strcat(log, "</span>");

		gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
		strcpy(log, "");
	}
	else
	{
		package = getDataExcelFile(pathFile, package);

		if(!package)
		{
			strcpy(log, "<span foreground='red'>");
			strcat(log, "The selected file has no data!");
			strcat(log, "</span>");

			gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
			strcpy(log, "");
		}
		else
		{
			display(package);

			if(!sendOrder(data->id, "0", package, &log))
			{
				gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
				strcpy(log, "");
			}
			else
				gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
		}
	}

	free(pathFile);
	free(package);
	free(log);

	return;
}



G_MODULE_EXPORT void destroyWindow(GtkWidget *widget, t_data *data)
{
	if(data)
		setUserData(data->id, "appSignIn", "0");
    gtk_main_quit();

    return;
}



int gtkInit(int argc, char **argv)
{
	GtkBuilder *builder;
	GError *error;
	t_data *data;
	char ***listDeposit;
	int listDepositNbRowElement;

	error = NULL;
	data = malloc(sizeof(t_data));
	data->windowConnection = g_slice_new(t_connection);
	data->windowMenu = g_slice_new(t_menu);
	data->windowUploadFile = g_slice_new(t_uploaFile);

	gtk_init(&argc, &argv);

	builder = gtk_builder_new();

	if(!gtk_builder_add_from_file(builder, GLADE_FILENAME, &error))
	{
		g_printerr("%s\n", error->message);
		g_error_free(error);
		return 0;
	}

	window = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "main_window")));

	if(!window)
	{
		printf("Error: Impossible to find the object \"%s\"\n", "main_window");
		destroyWindow(NULL, NULL);
		return 0;
	}

	data->id = 0;

	data->windowConnection->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_connection")));
	data->windowConnection->entry_email  = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "entry_email")));
	data->windowConnection->lbl_messageError = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "lbl_messageError")));

	data->windowMenu->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_menu")));

	data->windowUploadFile->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_uploadFile")));
	data->windowUploadFile->inputFile = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "btnFile_uploadFile")));
	data->windowUploadFile->selector = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "selector_deposit")));
	data->windowUploadFile->lbl_messageError = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "lbl_UploaFileMessageError")));

	listDeposit = getListDeposit(&listDepositNbRowElement);

	printf("nb deposit: %d\n", listDepositNbRowElement);

    for(int i = 0; i < listDepositNbRowElement; i++)
        printf("id: %d => %s\n", atoi(listDeposit[i][0]), listDeposit[i][1]);

	if(!listDeposit)
	{
		printf("Impossible to retrieve the list of deposit!\n");
		return 0;
	}

//	buildDepositSelector();

	gtk_builder_connect_signals(builder, data);

	gtk_container_add(GTK_CONTAINER(window), data->windowConnection->box);

	g_object_unref(builder);

	gtk_widget_show(window);

	gtk_main();

	g_slice_free(t_connection, data->windowConnection);
	g_slice_free(t_menu, data->windowMenu);
	g_slice_free(t_uploaFile, data->windowUploadFile);

	free(builder);
	free(error);
	free(data);

	return 1;
}