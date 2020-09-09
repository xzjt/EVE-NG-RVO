#!/bin/bash

# Diagnostic Script

date 
echo ---------------Packages Installed----------------
dpkg -l |  grep eve-ng | awk '{print $1" "$2" "$3}' | grep -v ^rc
echo
echo ---------------Hostname--------------------------
hostnamectl | grep 'hostname\|Virtualization\|Operating\|Kernel\|Architecture'
echo ---------------Disk Usage------------------------
df -h | grep -v docker
echo
echo ---------------CPU Info--------------------------
lscpu
echo
echo ---------------Memory Info-----------------------
free -m -h
echo
echo ---------------Nic Info--------------------------
ip link show | grep eth[0-9]
echo
echo ---------------IP Info---------------------------
networkctl status 2>/dev/null | grep -v pnet | grep -v fe80  
echo
echo ---------------Bridge Info-----------------------
brctl show | grep pnet
echo
echo ---------------H/W Accel-------------------------
kvm-ok
echo
echo ---------------Service Info----------------------
echo -------------------------------------------------
echo --------------Guacamole--------------------------
systemctl status guacd | sed -n '1,/Active/p'
echo
echo --------------Tomcat-----------------------------
systemctl status tomcat8 | sed -n '1,/Active/p'
echo
echo --------------Mysql------------------------------
systemctl status mysql | sed -n '1,/Active/p'
echo
echo --------------Apache-----------------------------
systemctl status apache2 | sed -n '1,/Active/p'
echo
