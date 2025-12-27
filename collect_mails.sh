#!/bin/bash

MAILBOX="/var/mail/stud"
OUTPUT="/var/www/html/mails.txt"

echo "=== MAILS DE STUD ===" > "$OUTPUT"
echo "" >> "$OUTPUT"

if [ -f "$MAILBOX" ];  then
    sed -n '
    /^From /{
        print ""
        print "------------------------------"
    }
    /^From |^Subject:|^Date:/p
    /^$/{
        print ""
        getline
        while ($0 !~ /^From / && !eof) {
            print
            getline
        }
    }
    ' "$MAILBOX" >> "$OUTPUT"
else
    echo "Aucun mail trouvé." >> "$OUTPUT"
fi

