#!/bin/bash

# $1 = Versionsnummer Aktuell
# $2 = Versionnummer Neu

git fetch > update.log;
git checkout $2 >> update.log
mv update.log _updaterescue/$1/