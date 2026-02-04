#!/bin/sh
file="controls-Security.json"
domainNumber="1"

while [ $domainNumber -le 9 ]
do
    grep -A44 "Domain-$domainNumber" controls-Security.json > domain-${domainNumber}
    for file in $(grep -- "-recommendation" domain-${domainNumber} | cut -f1 -d ":" | awk '{print $1}' | tr -d '"')
    do
        filename="$domainNumber-$file.html"
        grep -- "${file}" domain-${domainNumber} | cut -f2- -d ":" | tr -d '"' | rev | cut -c2- | rev | sed 's/    /\n/g'> recommendations/$filename
    done
    ((domainNumber++))
done
