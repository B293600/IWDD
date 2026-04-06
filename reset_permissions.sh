#!/bin/bash

# Set base dirs
chmod 755 /home/s2328610
chmod 755 /home/s2328610/public_html

# Set directories only
find /home/s2328610/public_html/web_project -type d -exec chmod 755 {} \; 2>/dev/null

# Set files separately (optional)
find /home/s2328610/public_html/web_project -type f -exec chmod 644 {} \; 2>/dev/null

# Special tmp directory
chmod 733 /home/s2328610/public_html/web_project/tmp 2>/dev/null
