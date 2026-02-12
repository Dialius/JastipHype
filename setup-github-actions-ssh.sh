#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║      🔐 SETUP SSH KEY FOR GITHUB ACTIONS - HOSTINGER      ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}This script will help you setup SSH authentication for GitHub Actions${NC}"
echo ""

# Step 1: Check if .ssh directory exists
echo -e "${YELLOW}📂 Step 1: Checking .ssh directory...${NC}"
if [ ! -d ~/.ssh ]; then
    echo -e "${YELLOW}Creating .ssh directory...${NC}"
    mkdir -p ~/.ssh
    chmod 700 ~/.ssh
    echo -e "${GREEN}✅ .ssh directory created${NC}"
else
    echo -e "${GREEN}✅ .ssh directory exists${NC}"
fi
echo ""

# Step 2: Backup existing authorized_keys
echo -e "${YELLOW}📦 Step 2: Backing up authorized_keys...${NC}"
if [ -f ~/.ssh/authorized_keys ]; then
    BACKUP_FILE=~/.ssh/authorized_keys.backup.$(date +%Y%m%d_%H%M%S)
    cp ~/.ssh/authorized_keys $BACKUP_FILE
    echo -e "${GREEN}✅ Backup created: $BACKUP_FILE${NC}"
else
    echo -e "${YELLOW}⚠️  No existing authorized_keys found${NC}"
    touch ~/.ssh/authorized_keys
    echo -e "${GREEN}✅ Created new authorized_keys file${NC}"
fi
echo ""

# Step 3: Display current authorized_keys
echo -e "${YELLOW}📋 Step 3: Current authorized_keys content:${NC}"
echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
if [ -s ~/.ssh/authorized_keys ]; then
    cat ~/.ssh/authorized_keys | head -20
    echo ""
    LINES=$(wc -l < ~/.ssh/authorized_keys)
    echo -e "${BLUE}Total lines: $LINES${NC}"
else
    echo -e "${YELLOW}(empty)${NC}"
fi
echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo ""

# Step 4: Instructions for adding public key
echo -e "${YELLOW}📝 Step 4: Add your GitHub Actions public key${NC}"
echo ""
echo -e "${BLUE}INSTRUCTIONS:${NC}"
echo "1. On your LOCAL computer (Windows), generate SSH key:"
echo "   ${GREEN}ssh-keygen -t ed25519 -C \"github-actions\" -f github-actions-key${NC}"
echo ""
echo "2. View the PUBLIC key:"
echo "   ${GREEN}cat github-actions-key.pub${NC}"
echo ""
echo "3. Copy the ENTIRE output (starts with 'ssh-ed25519')"
echo ""
echo "4. Paste it below when prompted"
echo ""
echo -e "${RED}⚠️  IMPORTANT: Paste the PUBLIC key (.pub), NOT the private key!${NC}"
echo ""

# Prompt for public key
echo -e "${YELLOW}Enter your GitHub Actions PUBLIC KEY:${NC}"
echo -e "${BLUE}(Paste and press Enter)${NC}"
read -r PUBLIC_KEY

# Validate input
if [ -z "$PUBLIC_KEY" ]; then
    echo -e "${RED}❌ Error: No key provided!${NC}"
    exit 1
fi

# Check if key looks valid
if [[ ! "$PUBLIC_KEY" =~ ^ssh- ]]; then
    echo -e "${RED}❌ Error: Invalid key format! Key should start with 'ssh-'${NC}"
    echo -e "${YELLOW}Example: ssh-ed25519 AAAAC3NzaC1lZDI1NTE5...${NC}"
    exit 1
fi

# Step 5: Add public key to authorized_keys
echo ""
echo -e "${YELLOW}📝 Step 5: Adding public key to authorized_keys...${NC}"

# Add key with comment
echo "" >> ~/.ssh/authorized_keys
echo "# GitHub Actions - Added $(date +%Y-%m-%d\ %H:%M:%S)" >> ~/.ssh/authorized_keys
echo "$PUBLIC_KEY" >> ~/.ssh/authorized_keys

echo -e "${GREEN}✅ Public key added${NC}"
echo ""

# Step 6: Set correct permissions
echo -e "${YELLOW}🔧 Step 6: Setting correct permissions...${NC}"
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
echo -e "${GREEN}✅ Permissions set:${NC}"
echo "   ~/.ssh = 700"
echo "   ~/.ssh/authorized_keys = 600"
echo ""

# Step 7: Verify
echo -e "${YELLOW}🔍 Step 7: Verifying setup...${NC}"
echo ""
echo -e "${BLUE}Permissions:${NC}"
ls -la ~/.ssh/ | grep -E "^d|authorized_keys"
echo ""
echo -e "${BLUE}Last 5 lines of authorized_keys:${NC}"
tail -5 ~/.ssh/authorized_keys
echo ""

# Step 8: Display next steps
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                  ✅ SETUP COMPLETE! ✅                     ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}🎉 SSH key has been added to authorized_keys!${NC}"
echo ""
echo -e "${YELLOW}📝 NEXT STEPS:${NC}"
echo ""
echo "1. ${BLUE}Add PRIVATE key to GitHub Secrets:${NC}"
echo "   • Go to: https://github.com/Dialius/JastipHype/settings/secrets/actions"
echo "   • Click 'New repository secret'"
echo "   • Name: ${GREEN}SSH_PRIVATE_KEY${NC}"
echo "   • Value: Copy from ${GREEN}github-actions-key${NC} (NOT .pub)"
echo "   • On Windows: ${GREEN}cat github-actions-key${NC}"
echo ""
echo "2. ${BLUE}Verify other GitHub Secrets exist:${NC}"
echo "   • ${GREEN}SSH_HOST${NC} = jastiphype.shop"
echo "   • ${GREEN}SSH_USERNAME${NC} = u909490256"
echo "   • ${GREEN}SSH_PORT${NC} = 65002"
echo ""
echo "3. ${BLUE}Test SSH connection from local:${NC}"
echo "   ${GREEN}ssh -i github-actions-key -p 65002 u909490256@jastiphype.shop${NC}"
echo ""
echo "4. ${BLUE}Test GitHub Actions:${NC}"
echo "   • Go to: https://github.com/Dialius/JastipHype/actions"
echo "   • Click 'Deploy to Hostinger'"
echo "   • Click 'Run workflow'"
echo ""
echo -e "${YELLOW}📖 For detailed guide, see: FIX_GITHUB_ACTIONS_SSH.md${NC}"
echo ""
