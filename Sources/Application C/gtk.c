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
            gtk_label_set_markup (GTK_LABEL(data->windowMenu->lbl_messageError), log);
			gtk_container_remove(GTK_CONTAINER(window), data->windowConnection->box);
			gtk_container_add(GTK_CONTAINER(window), data->windowMenu->box);
		}
	}

	free(log);

	return;
}



G_MODULE_EXPORT void uploadFileToMenu(GtkButton *btn_submit, t_data *data)
{
    gtk_label_set_markup (GTK_LABEL(data->windowMenu->lbl_messageError), "");

    gtk_container_remove(GTK_CONTAINER(window), data->windowUploadFile->box);
    gtk_container_add(GTK_CONTAINER(window), data->windowMenu->box);

    return;
}



G_MODULE_EXPORT void uploadFile(GtkButton *btn_submit, t_data *data)
{
    gtk_file_chooser_unselect_all (GTK_FILE_CHOOSER(data->windowUploadFile->inputFile));
    gtk_combo_box_set_active_id (GTK_COMBO_BOX(data->windowUploadFile->selector), "0");
	gtk_container_remove(GTK_CONTAINER(window), data->windowMenu->box);
	gtk_container_add(GTK_CONTAINER(window), data->windowUploadFile->box);

	return;
}



G_MODULE_EXPORT void downloadFile(GtkButton *btn_submit, t_data *data)
{
    char *log;
    char *downloadPath;

    log = malloc(sizeof(char) * 2000);

    downloadPath = (char *)gtk_file_chooser_get_filename(GTK_FILE_CHOOSER(data->windowMenu->selectorDownloadFolder));

    if(!downloadPath)
    {
        strcpy(log, "<span foreground='red'>");
        strcat(log, "You have not selected a folder where to save the file!");
        strcat(log, "</span>");
        gtk_label_set_markup (GTK_LABEL(data->windowMenu->lbl_messageError), log);
        strcat(log, "");
    }
    else
    {
        printf("chk\n");
        downloadTemplate(downloadPath);

        strcpy(log, "<span foreground='#10ac84'>");
        strcat(log, "Download success!");
        strcat(log, "</span>");

        gtk_label_set_markup (GTK_LABEL(data->windowMenu->lbl_messageError), log);
        strcat(log, "");
    }

    free(log);

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
		while(packageTmp != NULL)
		{
            printf("weight: %d\n", packageTmp->weight);
            printf("volume: %d\n", packageTmp->volume);
            printf("address of the receiver: %s\n", packageTmp->address);
            printf("city of the receiver: %s\n", packageTmp->city);
            printf("email of the receiver: %s\n\n", packageTmp->emailDest);
            packageTmp = packageTmp->next;
        }
	}
	else
	{
		printf("No data!\n");
	}

	return;
}
/*------------------------------------------------------------------------*/



G_MODULE_EXPORT void createExpressOrder(GtkButton *btn_submit, t_data *data)
{
    orderProcess(data, "1");

    return;
}



G_MODULE_EXPORT void createStandardOrder(GtkButton *btn_submit, t_data *data)
{
    orderProcess(data, "0");

    return;
}



void orderProcess(t_data *data, char *deliveryType)
{
    t_package *package;
    char idDeposit[5];
    char *pathFile;
    char *log;
    char *idBill;
    int idOrder;

	log = malloc(sizeof(char) * 2000);
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
			//display(package); //===> DEBUG

			strcpy(idDeposit, (char *)gtk_combo_box_get_active_id(GTK_COMBO_BOX(data->windowUploadFile->selector)));

            idOrder = sendOrder(data->id, deliveryType, package, idDeposit, &log);
            idBill = getIdBill(idOrder);
            sendFileInServer(data->id, pathFile, idBill);

            if(!strcmp(idDeposit, "0"))
            {
                strcpy(log, "<span foreground='red'>");
                strcat(log, "You have to selected a deposit!\n");
                strcat(log, "</span>");

                gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
                strcpy(log, "");
            }
            else
            {
                if(!idOrder)
                {
                    gtk_label_set_markup (GTK_LABEL(data->windowUploadFile->lbl_messageError), log);
                    strcpy(log, "");
                }
                else
                {
                    //======================================
                    strcpy(log, "<span foreground='#10ac84'>");
                    if(atoi(deliveryType))
                        strcat(log, "Your express order has been sent successfully!\n");
                    else
                        strcat(log, "Your standard order has been sent successfully!\n");
                    strcat(log, "The reference number of your order will be: ");
                    strcat(log, idBill);
                    strcat(log, "</span>");

                    gtk_label_set_markup (GTK_LABEL(data->windowMenu->lbl_messageError), log);
                    strcpy(log, "");

                    gtk_container_remove(GTK_CONTAINER(window), data->windowUploadFile->box);
                    gtk_container_add(GTK_CONTAINER(window), data->windowMenu->box);
                }
            }
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



void buildDepositSelector(t_data **data, char ***listDeposit, int nbRow)
{
    for (int i = 0; i < nbRow; i++)
        gtk_combo_box_text_append (GTK_COMBO_BOX_TEXT((*data)->windowUploadFile->selector), listDeposit[i][0], listDeposit[i][1]);

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
	data->windowUploadFile = g_slice_new(t_uploadFile);

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
	data->windowMenu->selectorDownloadFolder = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "btn_selectFolder")));
	data->windowMenu->lbl_messageError = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "lbl_menuLog")));

	data->windowUploadFile->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_uploadFile")));
	data->windowUploadFile->inputFile = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "btnFile_uploadFile")));
    data->windowUploadFile->selector = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "selector_deposit")));
    data->windowUploadFile->lbl_messageError = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "lbl_UploaFileMessageError")));

	listDeposit = getListDeposit(&listDepositNbRowElement);

	if(!listDeposit)
	{
		printf("Impossible to retrieve the list of deposit!\n");
		return 0;
	}

	buildDepositSelector(&data, listDeposit, listDepositNbRowElement);

	gtk_builder_connect_signals(builder, data);

	g_object_unref(builder);

	gtk_widget_show(window);

	gtk_main();

	g_slice_free(t_connection, data->windowConnection);
	g_slice_free(t_menu, data->windowMenu);
	g_slice_free(t_uploadFile, data->windowUploadFile);

	free(builder);
	free(error);
	free(data);

	return 1;
}