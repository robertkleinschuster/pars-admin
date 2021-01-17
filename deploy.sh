#!/bin/sh
export PATH=/opt/plesk/php/7.4/bin:$PATH:$HOME/bin
composer update &> deploy.log
