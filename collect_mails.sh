#!/bin/bash

MAILBOX="/var/mail/stud"
OUTPUT="/var/www/html/mails.txt"

echo "=== MAILS DE STUD ===" > "$OUTPUT"
echo "" >> "$OUTPUT"

if [ -f "$MAILBOX" ]; then
    cat "$MAILBOX" >> "$OUTPUT"
else
    echo "Aucun mail trouvé." >> "$OUTPUT"
fi
