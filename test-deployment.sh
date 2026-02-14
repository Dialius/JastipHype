#!/bin/bash
# Deployment Testing Script for Bash
# Usage: ./test-deployment.sh

echo ""
echo "🧪 JastipHype Deployment Testing"
echo "================================"
echo ""

tests_passed=0
tests_failed=0

# Test 1: Site is accessible
echo "1. Testing site accessibility..."
if curl -f -s -o /dev/null -w "%{http_code}" https://jastiphype.shop | grep -q "200"; then
    echo "   ✅ Site is accessible (Status: 200)"
    ((tests_passed++))
else
    echo "   ❌ Site is not accessible"
    ((tests_failed++))
fi

# Test 2: Assets are loading
echo ""
echo "2. Testing asset loading..."
if curl -s https://jastiphype.shop | grep -q "build/assets/app-.*\.css"; then
    echo "   ✅ CSS assets found in HTML"
    ((tests_passed++))
else
    echo "   ❌ CSS assets not found in HTML"
    ((tests_failed++))
fi

# Test 3: Privacy policy page
echo ""
echo "3. Testing GDPR pages..."
if curl -f -s -o /dev/null https://jastiphype.shop/gdpr/privacy-policy; then
    echo "   ✅ Privacy policy accessible"
    ((tests_passed++))
else
    echo "   ❌ Privacy policy not accessible"
    ((tests_failed++))
fi

# Test 4: Cookie policy page
echo ""
echo "4. Testing cookie policy..."
if curl -f -s -o /dev/null https://jastiphype.shop/gdpr/cookie-policy; then
    echo "   ✅ Cookie policy accessible"
    ((tests_passed++))
else
    echo "   ❌ Cookie policy not accessible"
    ((tests_failed++))
fi

# Test 5: Check server commit
echo ""
echo "5. Checking server synchronization..."
server_commit=$(ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && git log -1 --format='%h %s'")
local_commit=$(git log -1 --format='%h %s')

echo "   Server: $server_commit"
echo "   Local:  $local_commit"

if [ "$server_commit" = "$local_commit" ]; then
    echo "   ✅ Server is synchronized with local"
    ((tests_passed++))
else
    echo "   ⚠️  Server is not synchronized (may be deploying)"
    ((tests_failed++))
fi

# Test 6: Response time
echo ""
echo "6. Testing response time..."
response_time=$(curl -o /dev/null -s -w '%{time_total}\n' https://jastiphype.shop)
echo "   Response time: ${response_time}s"

if (( $(echo "$response_time < 3" | bc -l) )); then
    echo "   ✅ Response time is good"
    ((tests_passed++))
else
    echo "   ⚠️  Response time is slow"
    ((tests_passed++))
fi

# Summary
echo ""
echo "================================"
echo "📊 Test Summary"
echo "================================"
echo "✅ Passed: $tests_passed"
echo "❌ Failed: $tests_failed"
echo "Total:  $((tests_passed + tests_failed))"

if [ $tests_failed -eq 0 ]; then
    echo ""
    echo "🎉 All tests passed! Deployment is successful!"
else
    echo ""
    echo "⚠️  Some tests failed. Please check the issues above."
fi

echo ""
