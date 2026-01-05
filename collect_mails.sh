#!/bin/bash

MAILBOX="/var/mail/stud"
OUTPUT="/var/www/html/mails.txt"

echo "=== MAILS DE STUD ===" > "$OUTPUT"
echo "" >> "$OUTPUT"

if [ -f "$MAILBOX" ]; then
    awk '
    /^From / {
        if (NR != 1) print "\n--------------------------\n"
        print
        next
    }
    /^Subject:|^Date:/ {
        print
        next
    }
    /^$/ {
        body=1
        next
    }
    body {
        print
    }
    ' "$MAILBOX" >> "$OUTPUT"
else
    echo "Aucun mail trouvé." >> "$OUTPUT"
fi
