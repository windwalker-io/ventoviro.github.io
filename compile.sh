#!/usr/bin/env bash
pwd
composer create-project asika/vaseman vaseman 3.* -s RC
php vaseman/bin/vaseman up
rm -rf vaseman
