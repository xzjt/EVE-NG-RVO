#!/bin/bash


LIST=$(find /opt/unetlab/tmp -name '*nvram_[0-9]*')


for NVRAM in $LIST ; do \
echo ${NVRAM} | egrep '/opt/unetlab/tmp/[0-9]+/[0-9a-f\-]{36}/[0-9]+/nvram_[0-9]{5}' > /dev/null 2>&1
if [ ${?} -ne 0 ]; then
    echo 'ERROR: File is not valid.'
    exit 2
fi

if [ ! -f ${NVRAM} ]; then
    echo 'ERROR: File does not exist.'
    exit 3
fi

NVRAM_FILE=$(echo ${NVRAM} | cut -d/ -f 8)
NVRAM_ID=$(echo ${NVRAM_FILE} | sed 's/nvram_[0]\+//')
NODE_ID=$(echo ${NVRAM} | cut -d/ -f 7)
NODE_DIR=$(echo ${NVRAM} | cut -d/ -f 1-7)
POD_DIR=$(echo ${NVRAM} | cut -d/ -f 5)
NEW_NVRAM_ID=$(($((${NODE_ID}<<4)) + ${POD_DIR} ))

if [ ${NVRAM_ID} -ne ${NEW_NVRAM_ID} ]; then
    mv -f ${NVRAM} ${NODE_DIR}/nvram_$(printf "%05i" $(($((${NODE_ID}<<4)) + ${POD_DIR} )))
fi
done
