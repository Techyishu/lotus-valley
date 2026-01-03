#!/bin/bash

# ============================================
# Hostinger Deployment Script
# ============================================
# USAGE: ./deploy.sh
# ============================================

# Configuration
HOST="username@your-domain.com"  # CHANGE THIS
REMOTE_DIR="public_html"          # CHANGE THIS if different
PORT="22"                          # CHANGE THIS if different

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=================================${NC}"
echo -e "${YELLOW}Deploying to Hostinger${NC}"
echo -e "${YELLOW}=================================${NC}"

# Test SSH connection
echo -e "\n${GREEN}1. Testing SSH connection...${NC}"
ssh -p "$PORT" "$HOST" "echo 'Connected successfully'" || {
    echo -e "${RED}❌ SSH connection failed!${NC}"
    echo "Please check:"
    echo "  - Host: $HOST"
    echo "  - Port: $PORT"
    echo "  - SSH is enabled on Hostinger"
    exit 1
}

# Create directories
echo -e "\n${GREEN}2. Creating upload directories...${NC}"
ssh -p "$PORT" "$HOST" "
    cd $REMOTE_DIR
    mkdir -p uploads/slc
    chmod 755 uploads
    chmod 755 uploads/slc
    echo 'Directories created'
"

# Sync files
echo -e "\n${GREEN}3. Syncing files...${NC}"
rsync -avz -e "ssh -p $PORT" \
    --exclude='.git/' \
    --exclude='node_modules/' \
    --exclude='.DS_Store' \
    --exclude='*.log' \
    --exclude='deploy.sh' \
    --exclude='deploy-guide.md' \
    ./ "$HOST:$REMOTE_DIR/"

echo -e "\n${GREEN}✅ Deployment completed successfully!${NC}"
echo -e "\n${YELLOW}Next steps:${NC}"
echo "1. Import database_new_sections.sql via phpMyAdmin"
echo "2. Test your admin panel"
echo "3. Test the new pages"
echo ""
echo -e "${YELLOW}New pages added:${NC}"
echo "  - sports.php"
echo "  - slc.php"
echo "  - bus-routes.php"
echo "  - fee-structure.php"
