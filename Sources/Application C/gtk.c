#include "gtk.h"

GtkWidget *window;

G_MODULE_EXPORT void logOut(GtkButton *btn_submitConnection, t_data *data)
{
	setUserData(data->id, "appSignIn", "0");
	data->id = 0;

	gtk_container_remove(GTK_CONTAINER(window), data->windowMenu->box);
	gtk_container_add(GTK_CONTAINER(window), data->windowConnection->box);

	return;
}



G_MODULE_EXPORT void signIn(GtkButton *btn_submitConnection, t_data *data)
{
    char httpRequest[255];
	char *email;
	char *log;
	int connectionStatus;

	connectionStatus = 0;
	log = malloc(sizeof(char) * 2000);

	strcpy(log, "");
	gtk_label_set_text(GTK_LABEL(data->windowConnection->lbl_messageError), log);

    email = (char *)gtk_entry_get_text(GTK_ENTRY(data->windowConnection->entry_email));
	data->id = getId(email, &log);

	if (strcmp(log, ""))
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

	error = NULL;
	data = malloc(sizeof(t_data));
	data->windowConnection = g_slice_new(t_connection);
	data->windowMenu = g_slice_new(t_menu);

	gtk_init(&argc, &argv);

	builder = gtk_builder_new();

	if(!gtk_builder_add_from_file(builder, GLADE_FILENAME, &error))
	{
		g_printerr("%s\n", error->message);
		g_error_free(error);
		return 0;
	}

	window = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "main_window")));
	data->id = 0;

	data->windowConnection->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_connection")));
	data->windowConnection->entry_email  = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "entry_email")));
	data->windowConnection->lbl_messageError = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "lbl_messageError")));

	data->windowMenu->box = g_object_ref(GTK_WIDGET(gtk_builder_get_object(builder, "box_menu")));

	if(!window)
	{
		printf("Error: Impossible to find the object \"%s\"\n", "main_window");
		destroyWindow(NULL, NULL);
		return 0;
	}

	gtk_builder_connect_signals(builder, data);

	gtk_container_add(GTK_CONTAINER(window), data->windowConnection->box);

	g_object_unref(builder);

	gtk_widget_show(window);

	gtk_main();

	g_slice_free(t_connection, data->windowConnection);
	g_slice_free(t_menu, data->windowMenu);

	return 1;
}