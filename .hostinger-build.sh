#!/bin/bash

# Hostinger Build Script
# This script runs when Hostinger auto-deploy is triggered

echo "🚀 Starting Hostinger build..."

# Install dependencies with legacy peer deps
npm install --legacy-peer-deps

# Build assets
npm run build

echo "✅ Build completed!"
