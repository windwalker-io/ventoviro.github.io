#!/usr/bin/env bash
pwd
composer create-project asika/vaseman vaseman 3.* -s beta
php vaseman/bin/vaseman up
rm -rf vaseman
