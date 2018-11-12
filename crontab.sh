#!/bin/bash

step=2 #间隔的秒数，不能大于60

for (( i = 0; i < 60; i=(i+step) )); do
    $(/usr/bin/curl http://54.190.29.18/index.php?g=Api&c=Index&a=updateAssign)
    sleep $step
done

exit 0