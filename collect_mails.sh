#!/bin/bash

MAILBOX="/var/mail/stud"
OUTPUT="/var/www/html/mails.txt"

echo "=== MAILS DE STUD ===" > "$OUTPUT"
echo "" >> "$OUTPUT"

if [ -f "$MAILBOX" ]; then
    grep -E "^(From |Subject:|Date:)" "$MAILBOX" \
    | sed '/^From /a Subject: (aucun sujet)' \
    | sed '/^Subject:/d;0,/^Subject: (aucun sujet)/{/^Subject: (aucun sujet)/!d}' \
    >> "$OUTPUT"
else
    echo "Aucun mail trouvé." >> "$OUTPUT"
fi
