#include "gtk.h"

G_MODULE_EXPORT void connexion()
{
	printf("ok\n");
}



G_MODULE_EXPORT void destroyWindow(GtkWidget *widget, t_widgets *widgets)
{
	if(widgets)
		setUserData(widgets->id, "appSignIn", "0");
    gtk_main_quit();
}



G_MODULE_EXPORT void btnTest(GtkButton *btn_submitConnection, GtkEntry *entry_email)
{
	printf("%s\n", gtk_entry_get_text(entry_email));
}



int gtkInit(int argc, char **argv, GtkBuilder **builder)
{
	GError *error;

	error = NULL;

	gtk_init(&argc, &argv);

	*builder = gtk_builder_new();

	if(!gtk_builder_add_from_file(*builder, GLADE_FILENAME, &error))
	{
		g_printerr("%s\n", error->message);
		g_error_free(error);
		return 0;
	}

	return 1;
}



void updateWindow(GtkBuilder *builder, char *windowObjectName)
{
	GtkWidget *window;
	t_widgets *widgets;

	window = GTK_WIDGET(gtk_builder_get_object(builder, windowObjectName));
	widgets = NULL;

	if(!window)
	{
		printf("Error: Impossible to find the object \"%s\"\n", windowObjectName);
		destroyWindow(NULL, NULL);
		return;
	}

	if (!strcmp(windowObjectName, "window_connection"))
	{
		widgets = g_slice_new(t_widgets);
		widgets->entry_email  = GTK_WIDGET(gtk_builder_get_object(builder, "entry_email"));
		widgets->lbl_messageError = GTK_WIDGET(gtk_builder_get_object(builder, "lbl_messageError"));
		widgets->id = 0;
	}

	gtk_builder_connect_signals(builder, widgets);

	g_object_unref(builder);

	gtk_widget_show(window);

	gtk_main();

	if (!strcmp(windowObjectName, "window_connection"))
		g_slice_free(t_widgets, widgets);
}