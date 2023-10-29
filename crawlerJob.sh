#!/bin/bash

cd ~/web/$2/public_html
CURRENTDATE=$(date +%d-%m-%y)
DIR=~/web/$2/public_html/data/logs/$CURRENTDATE
if [ -d "$DIR" ]; then
  echo 1;
  else
  mkdir $DIR
  echo ${DIR}
fi

if [ $1 = 'hard' ]
    then
    echo "Started crawler process for hard sites....."
    date
    php ./src/jobs/hardCrawl.php >> $DIR/outputHard.txt
    fi
if [ $1 = 'medium' ]
    then
    echo "Started crawler process for medium sites....."
    date
    php ./src/jobs/mediumCrawl.php >> $DIR/outputMedium.txt
    fi
if [ $1 = 'easy' ]
    then
    echo "Started crawler process for easy sites....."
    date
    php ./src/jobs/easyCrawl.php >> $DIR/outputEasy.txt
    fi
