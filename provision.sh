#!/bin/bash
if sudo useradd $1; echo -e "$2\n$2" | passwd $1; then
	printf "$1 was created successfully\n"
else
	printf "Something went wrong\n"
fi

