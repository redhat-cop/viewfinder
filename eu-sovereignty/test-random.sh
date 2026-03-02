#!/bin/bash
# Quick test script to generate random EU sovereignty assessment results
# Usage: ./test-random.sh

echo "Generating random EU Cloud Sovereignty Assessment results..."
echo "================================================================"
echo ""

# Run the PHP test script
php test-random-results.php > /tmp/eu-sov-test-results.html

# Check if php command succeeded
if [ $? -eq 0 ]; then
    echo "✓ Results generated successfully!"
    echo ""
    echo "View in browser:"
    echo "  file:///tmp/eu-sov-test-results.html"
    echo ""
    echo "Or run in browser directly:"
    echo "  http://localhost/viewfinder-redhat/eu-sovereignty/test-random-results.php"
    echo ""

    # If running in a desktop environment, optionally open in browser
    if command -v xdg-open &> /dev/null; then
        read -p "Open in browser now? (y/n) " -n 1 -r
        echo ""
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            xdg-open /tmp/eu-sov-test-results.html
        fi
    fi
else
    echo "✗ Error generating results"
    exit 1
fi
