#!/usr/bin/env bash
echo pwd
composer create-project asika/vaseman vaseman 2.*
php vaseman/bin/vaseman up
rm -rf vaseman
