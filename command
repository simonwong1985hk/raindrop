bc

cal

date

curl -O URL

curl -o NEW_FILE_NAME URL

curl -T LOCAL_FILE -u 'FTP_USERNAME@FTP_PASSWORD' FTP_SERVER

crontab -e
*(0-59) *(0-23) *(1-31) *(1-12) *(0-6/Sun-Sat) COMMAND TO EXECUTE

crontab -l

du -h * | sort -hk 1

du -h * | sort -rhk 1

find . -type f -name 'FILENAME' -exec mv {} {}.bak \;

find . -type f -name 'FILENAME' -exec rename 's/OLD/NEW/' {} \;

grep -irn --color=auto 'PATTERN' .

kill PID

killall -HUP mDNSResponder

ln -s TARGET NEW_LINK_NAME

ls -lv

openssl s_client -quiet -connect URL:PORT

ps aux | grep 'APPLICATION NAME'

ps -eo pcpu,pid,user,args | sort -r -k1 | less

rm NEW_LINK_NAME (if folder, do NOT suffix with /)

#without SOURCE_FOLDER
rsync -av SOURCE_FOLDER/ DESTINATION_FOLDER

#with SOURCE_FOLDER
rsync -av SOURCE_FOLDER DESTINATION_FOLDER

ssh USERNAME@HOSTNAME

tar -zxvf FILE.tgz -C NEW_FOLDER

telnet URL PORT

top -o cpu

top -o rsize

# Kernel Version
uname -a

unzip FILE.zip -d FOLDER

vi FILE.zip

zip -ry FILE.zip FOLDER

zip -ry FILE.zip FOLDER -x FOLDER/\*
