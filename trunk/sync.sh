rsync -vizr --exclude=".*/" --exclude-from=rsync-exclude.txt  -e ssh /home/hitanshu/public_html/smslite/trunk/ guser@demo-tracker.wisdommart.in:~/domains/app.msngr.in
