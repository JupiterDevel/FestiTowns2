#!/bin/bash
# Script to fix Laravel storage directory permissions

echo "Fixing storage directory permissions..."
sudo chown -R jupiter:jupiter storage
sudo chmod -R 775 storage

echo "Verifying permissions..."
ls -la storage/framework/views/ | head -5

echo "Done! Storage directory permissions have been fixed."


