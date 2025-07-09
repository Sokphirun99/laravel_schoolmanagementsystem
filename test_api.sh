#!/bin/bash

# School Management System API Test Script
# Make sure your Laravel server is running on http://localhost:8000

BASE_URL="http://localhost:8000/api"
TOKEN=""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}School Management System API Test Script${NC}"
echo "=========================================="

# Function to make API calls
make_request() {
    local method=$1
    local endpoint=$2
    local data=$3
    local use_auth=$4

    if [ "$use_auth" = "true" ] && [ -n "$TOKEN" ]; then
        if [ -n "$data" ]; then
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Content-Type: application/json" \
                -H "Authorization: Bearer $TOKEN" \
                -d "$data"
        else
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Authorization: Bearer $TOKEN"
        fi
    else
        if [ -n "$data" ]; then
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Content-Type: application/json" \
                -d "$data"
        else
            curl -s -X $method "$BASE_URL$endpoint"
        fi
    fi
}

# Test Authentication
echo -e "\n${YELLOW}1. Testing Authentication${NC}"
echo "========================="

# Test Login (you'll need to update with valid credentials)
echo -e "\n${GREEN}Testing Login...${NC}"
login_data='{
    "email": "admin@example.com",
    "password": "password"
}'

login_response=$(make_request "POST" "/auth/login" "$login_data" "false")
echo "$login_response" | python3 -m json.tool 2>/dev/null || echo "$login_response"

# Extract token from response (you might need to adjust this based on your actual response)
TOKEN=$(echo "$login_response" | python3 -c "import sys, json; print(json.load(sys.stdin)['data']['token'])" 2>/dev/null || echo "")

if [ -n "$TOKEN" ]; then
    echo -e "\n${GREEN}Login successful! Token obtained.${NC}"
else
    echo -e "\n${RED}Login failed or token not found. Some tests may fail.${NC}"
fi

# Test Get User
echo -e "\n${GREEN}Testing Get Authenticated User...${NC}"
user_response=$(make_request "GET" "/auth/user" "" "true")
echo "$user_response" | python3 -m json.tool 2>/dev/null || echo "$user_response"

# Test Dashboard Stats
echo -e "\n${YELLOW}2. Testing Dashboard${NC}"
echo "==================="
echo -e "\n${GREEN}Testing Dashboard Stats...${NC}"
stats_response=$(make_request "GET" "/dashboard/stats" "" "true")
echo "$stats_response" | python3 -m json.tool 2>/dev/null || echo "$stats_response"

# Test Students API
echo -e "\n${YELLOW}3. Testing Students API${NC}"
echo "======================="
echo -e "\n${GREEN}Testing Get Students...${NC}"
students_response=$(make_request "GET" "/students" "" "true")
echo "$students_response" | python3 -m json.tool 2>/dev/null || echo "$students_response"

# Test Teachers API
echo -e "\n${YELLOW}4. Testing Teachers API${NC}"
echo "======================="
echo -e "\n${GREEN}Testing Get Teachers...${NC}"
teachers_response=$(make_request "GET" "/teachers" "" "true")
echo "$teachers_response" | python3 -m json.tool 2>/dev/null || echo "$teachers_response"

# Test Courses API
echo -e "\n${YELLOW}5. Testing Courses API${NC}"
echo "======================"
echo -e "\n${GREEN}Testing Get Courses...${NC}"
courses_response=$(make_request "GET" "/courses" "" "true")
echo "$courses_response" | python3 -m json.tool 2>/dev/null || echo "$courses_response"

# Test Assignments API
echo -e "\n${YELLOW}6. Testing Assignments API${NC}"
echo "=========================="
echo -e "\n${GREEN}Testing Get Assignments...${NC}"
assignments_response=$(make_request "GET" "/assignments" "" "true")
echo "$assignments_response" | python3 -m json.tool 2>/dev/null || echo "$assignments_response"

# Test Attendance API
echo -e "\n${YELLOW}7. Testing Attendance API${NC}"
echo "========================="
echo -e "\n${GREEN}Testing Get Attendance...${NC}"
attendance_response=$(make_request "GET" "/attendance" "" "true")
echo "$attendance_response" | python3 -m json.tool 2>/dev/null || echo "$attendance_response"

# Test Announcements API
echo -e "\n${YELLOW}8. Testing Announcements API${NC}"
echo "============================="
echo -e "\n${GREEN}Testing Get Announcements...${NC}"
announcements_response=$(make_request "GET" "/announcements" "" "true")
echo "$announcements_response" | python3 -m json.tool 2>/dev/null || echo "$announcements_response"

echo -e "\n${GREEN}Testing Get Active Announcements...${NC}"
active_announcements_response=$(make_request "GET" "/announcements-active" "" "true")
echo "$active_announcements_response" | python3 -m json.tool 2>/dev/null || echo "$active_announcements_response"

# Test Fees API
echo -e "\n${YELLOW}9. Testing Fees API${NC}"
echo "==================="
echo -e "\n${GREEN}Testing Get Fees...${NC}"
fees_response=$(make_request "GET" "/fees" "" "true")
echo "$fees_response" | python3 -m json.tool 2>/dev/null || echo "$fees_response"

# Test Timetables API
echo -e "\n${YELLOW}10. Testing Timetables API${NC}"
echo "=========================="
echo -e "\n${GREEN}Testing Get Timetables...${NC}"
timetables_response=$(make_request "GET" "/timetables" "" "true")
echo "$timetables_response" | python3 -m json.tool 2>/dev/null || echo "$timetables_response"

echo -e "\n${YELLOW}API Testing Complete!${NC}"
echo "====================="
echo -e "\n${GREEN}Notes:${NC}"
echo "- Update login credentials in the script to test with real data"
echo "- Make sure your Laravel server is running"
echo "- Make sure you have test data in your database"
echo "- Some endpoints may return empty results if no data exists"
