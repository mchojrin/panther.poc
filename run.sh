#!/bin/sh

clear
docker run -it --rm -v $(pwd):/app panther.poc php test.php 'https://phpstack-1290690-4683712.cloudwaysapps.com/' chrome
