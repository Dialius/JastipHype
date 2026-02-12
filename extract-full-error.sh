#!/bin/bash

echo "=========================================="
echo "📋 EXTRACT FULL ERROR FROM LOG"
echo "=========================================="
echo ""

cd /home/u909490256/domains/jastiphype.shop

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo "Searching for mb_split error in Laravel log..."
echo ""

if [ ! -f "storage/logs/laravel.log" ]; then
    echo -e "${RED}❌ Laravel log file not found!${NC}"
    exit 1
fi

# Extract last mb_split error with context
echo -e "${BLUE}=== LAST mb_split ERROR ===${NC}"
echo ""

# Get the last occurrence with full context
grep -B 2 -A 10 "mb_split" storage/logs/laravel.log | tail -20

echo ""
echo -e "${BLUE}=== FULL ERROR LINE WITH PATH ===${NC}"
echo ""

# Extract just the error line with file path
grep "mb_split" storage/logs/laravel.log | grep "at /" | tail -1

echo ""
echo -e "${BLUE}=== ERROR TIMESTAMP ===${NC}"
echo ""

# Get timestamp of last error
grep "mb_split" storage/logs/laravel.log | tail -1 | grep -oP '\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]'

echo ""
echo -e "${BLUE}=== STACK TRACE (First 5 lines) ===${NC}"
echo ""

# Get stack trace
grep -A 5 "stacktrace" storage/logs/laravel.log | tail -6

echo ""
echo "=========================================="
echo "📊 ERROR ANALYSIS"
echo "=========================================="
echo ""

# Analyze the error
ERROR_FILE=$(grep "mb_split" storage/logs/laravel.log | grep -oP 'at \K[^:]+' | tail -1)
ERROR_LINE=$(grep "mb_split" storage/logs/laravel.log | grep -oP 'at [^:]+:\K\d+' | tail -1)

if [ -n "$ERROR_FILE" ]; then
    echo "Error occurs in file:"
    echo "  $ERROR_FILE"
    echo ""
    echo "At line: $ERROR_LINE"
    echo ""
    
    if [ -f "$ERROR_FILE" ]; then
        echo "File exists: ✅"
        echo ""
        echo "Context around error line:"
        echo "-------------------------------------------"
        
        # Show 5 lines before and after error line
        START_LINE=$((ERROR_LINE - 5))
        END_LINE=$((ERROR_LINE + 5))
        
        if [ $START_LINE -lt 1 ]; then
            START_LINE=1
        fi
        
        sed -n "${START_LINE},${END_LINE}p" "$ERROR_FILE" | nl -v $START_LINE
        echo "-------------------------------------------"
    else
        echo "File exists: ❌"
    fi
else
    echo "Could not extract error file path"
fi

echo ""
echo "=========================================="
echo "🔍 DIAGNOSTIC INFO"
echo "=========================================="
echo ""

echo "PHP CLI Version:"
php -v | head -1
echo ""

echo "PHP CLI mbstring:"
if php -m | grep -q "mbstring"; then
    echo "✅ Loaded"
else
    echo "❌ Not loaded"
fi
echo ""

echo "PHP CLI mb_split():"
php -r "if (function_exists('mb_split')) { echo '✅ Available\n'; } else { echo '❌ Not available\n'; }"
echo ""

echo "PHP Configuration File:"
php -i | grep "Loaded Configuration File"
echo ""

echo "=========================================="
echo "📝 COPY THIS INFO TO SUPPORT"
echo "=========================================="
echo ""
echo "If you need to contact Hostinger support, copy everything above."
echo ""
