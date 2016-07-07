#!/usr/bin/env bash
pwd
composer create-project asika/vaseman vaseman 2.*
php vaseman/bin/vaseman up
ls -l
rm -rf vaseman
