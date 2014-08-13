#!/bin/bash

echo $* > /tmp/ffmpeg-convert.log

($4 -i $1 -ab 192k -ac 2 -ar 44100 -b 1500k -s 640x480 -level 21 -refs 2 -bt 1500k $2 &&
mv $2 $2.tmp &&
qt-faststart $2.tmp $2 &&
rm -f $2.tmp &&
$4 -i $2 -an -ss 10 -vframes 1 -s 480x270 -y -f mjpeg $3 &&
rm -f $1) > /dev/null 2> /tmp/ffmpeg-convert.error.log &

