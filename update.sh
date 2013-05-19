#!/bin/bash

# $1 = Versionsnummer Aktuell
# $2 = Versionnummer Neu

git checkout -f $2 > update.log
mv update.log rollback/$1/