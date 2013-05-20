#!/bin/bash

# $1 = Versionsnummer Aktuell
# $2 = Versionnummer Neu

git checkout -f $1 > updaterescue.log
mv updaterescue.log _updaterescue/$1/