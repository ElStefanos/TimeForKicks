#!/bin/bash
CURRENTDATE=$(date +%d-%m-%y)
DIR=~/web/$1/public_html/data/logs/$CURRENTDATE
if [ -d "$DIR" ]; then
  echo 1;
  else
  mkdir $DIR
  echo ${DIR}
fi
echo Starting cache control job.
date
cd ~/web/$1/public_html/
php ./src/jobs/cacheControl.php >> $DIR/outputCacheControl.txt