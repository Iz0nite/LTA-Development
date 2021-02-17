NAME = ./excel.exe
SRCS = ./main.c ./packageProcess.c
FLAG = -I ./include


all: $(NAME)

$(NAME):
	gcc -o $(NAME) $(FLAG) $(SRCS)
	cls
	$(NAME)
clean:
	del -rf *.exe 
re: clean all