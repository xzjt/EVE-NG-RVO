#!/bin/bash
# This scirpt is made by Pete.
# It is used for configure the environment variable of eve-ng plaform modified by Pete
# Notice! Don't use it in any offical version that not including any modified php source code. 
# Email pete19890813@gmail.com
# Feel free to contact me if you have any problem.


ciscokeygen='/opt/unetlab/scripts/CiscoKeyGen.py'

sudo dpkg -l | grep eve > /dev/null
if [ $? -ne 0 ]; then
	echo '[!] eve-ng is not installed on current system, exit.'
	exit 1
fi

if [ -d ./html ] && [ -d /opt/unetlab ]; then
	cp -r ./html /opt/unetlab
	if [ $? -ne 0 ]; then
		echo '[-] Copy folder "html" to "/opt/unetlab" failed'
		htmlcp='failed'
	else
		echo '[+] Copy folder html to "/opt/unetlab" successfully!'
	fi
fi

if [ -d ./scripts ] && [ -d /opt/unetlab ]; then
	cp -r ./scripts /opt/unetlab
	if [ $? -ne 0 ]; then
		echo '[-] Copy folder "scripts" to "/opt/unetlab" failed'
		scrptscp='failed'
	else
		echo '[+] Copy folder scripts to "/opt/unetlab" successfully!'
	fi
fi

grep -P 'www-data.*ALL=' /etc/sudoers > /dev/null
if [ $? -ne 0 ]; then
	sed -i 's#root\t\+.\+ALL$#&\nwww-data ALL=(ALL:ALL) NOPASSWD:ALL#' /etc/sudoers
	grep -P '^root\t+ALL=' /etc/sudoers > /dev/null
	if [ $? -ne 0 ]; then
		echo '[-] Add user www-data to sudoers failed'
		sudoers='failed'
	else
		echo '[+] Add user www-data to sudoers successfully!'
	fi
else
	echo '[-] User www-data is alreay in sudoers!'
fi

if [ ! -x $ciscokeygen ]; then
	chmod +x $ciscokeygen
	if [ $? -ne 0 ]; then
		echo '[-] Change CiscoKeygen.py to excutable failed.'
	else
		echo '[+] Change CiscokeyGen.py to excuteable successfully!'
		ln -s $ciscokeygen /usr/bin/CiscoKeyGen
	fi		
fi

/opt/unetlab/wrappers/unl_wrapper -a fixpermissions
if [ $? -ne 0 ]; then
	echo '[-] fix permissions failed.'
	exit 1
fi

exit 0 
