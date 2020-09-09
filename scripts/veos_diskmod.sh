#!/bin/sh
cd $1
rmmod nbd
modprobe nbd max_part=16
for i in $(seq 0 15) 
	do /opt/qemu/bin/qemu-nbd -c /dev/nbd${i} *.qcow2  && break 
done 
mkdir disk
mount /dev/nbd${i}p1 disk
cp startup-config disk/startup-config 
if [ $? -ne 0 ] ; then
	umount disk
	mount /dev/nbd${i}p2 disk
	cp startup-config disk/startup-config
	echo "DISABLE=True" > disk/zerotouch-config
fi
umount disk
/opt/qemu/bin/qemu-nbd -d /dev/nbd${i}
rm -fr disk


