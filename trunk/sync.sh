rsync -vizr --exclude=".*/" --exclude-from=rsync-exclude.txt  -e ssh /home/hitanshu/public_html/smslite/trunk/ guser@greenapplesolutions.com:~/domains/app.msngr.in
