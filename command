# Calculator
bc

# Calendar
cal
ncal

# Date and Time
date

# CPU info
cat /proc/cpuinfo

# Display free disk space
df -H

# Download with remote filename
curl URL -O

# Download with new filename
curl URL -o "FILENAME"

# Upload
curl --upload-file LOCAL_FILE --user USERNAME:PASSWORD ftp://####

# Edit cron job
crontab -e
*(0-59) *(0-23) *(1-31) *(1-12) *(0-6/Sun-Sat) COMMAND TO EXECUTE

# List cron job
crontab -l

# Display disk usage statistics
du -h * | sort -hk 1

du -h * | sort -rhk 1

# Overwrite
echo 'STRING' > 'FILENAME'

# Append
echo 'STRING' >> 'FILENAME'

# Rename
find . -type f -name 'FILENAME' -exec mv {} {}.bak \;

find . -type f -name 'FILENAME' -exec rename 's/OLD/NEW/' {} \;

# Display amount of free and used memory in the system
free -m

# Search
grep -irn --color=auto 'PATTERN' .

# Search and Replace
grep -irl 'PATTERN' DIRECTORY/ | xargs sed -i '' 's/TARGET/REPLACEMENT/g'

# Terminate a process
kill PID

# Flush DNS cache on a Mac
killall -HUP mDNSResponder

# Create a symbolic link
ln -s TARGET NEW_LINK_NAME

# Process status
ps aux | grep 'APPLICATION NAME'

ps -eo pcpu,pid,user,args | sort -r -k1 | less

# Delete a symbolic link
rm NEW_LINK_NAME (if folder, do NOT suffix with /)

# Without SOURCE_FOLDER
rsync -av SOURCE_FOLDER/ DESTINATION_FOLDER

# With SOURCE_FOLDER
rsync -av SOURCE_FOLDER DESTINATION_FOLDER

# Upload
scp LOCAL_FILE USER@HOST:/home/USER/public_html

# Remote login
ssh USER@HOST

# Manipulate tape archives
tar -zxvf FILE.tgz -C NEW_FOLDER

telnet URL PORT

# Display and update sorted information about processes
top -o cpu

top -o rsize

# Print operating system name
uname -a

# Extract a compressed file
unzip FILE.zip -d FOLDER

# Compress a folder
zip -ry FILE.zip FOLDER

# Compress a folder exclude one folder
zip -ry FILE.zip FOLDER -x FOLDER/\*

# Preview zip file
zipinfo FILE.zip
