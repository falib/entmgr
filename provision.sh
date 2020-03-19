#!/bin/bash
output=`useradd $1; echo -e "$2\n$2" | sudo passwd $1`
#echo $?
#echo "henlo"
if [ $? -eq "0" ]; then	
	printf "TRUE,$1 was created successfully\n"
else
	echo $?
	printf "Something went wrong\n"
fi

