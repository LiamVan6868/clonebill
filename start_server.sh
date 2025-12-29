#!/bin/bash
echo "Starting Bill Vn Local Server..."
echo "Access at http://localhost:8080"
cd public_html
php -S localhost:8080 -t . router.php
