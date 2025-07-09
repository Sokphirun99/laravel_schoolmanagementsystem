# School Management System API Documentation

This API provides comprehensive endpoints for managing a school management system, including students, teachers, courses, assignments, attendance, fees, and more.

## Base URL
```
http://your-domain.com/api
```

## Authentication

The API uses Laravel Sanctum for authentication. After login, include the Bearer token in the Authorization header for all protected routes.

### Authentication Endpoints

#### Login
```http
POST /auth/login
```

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "roles": [...]
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

#### Register
```http
POST /auth/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Get Authenticated User
```http
GET /auth/user
Authorization: Bearer {token}
```

#### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

#### Change Password
```http
POST /auth/change-password
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

## Student Management

### Get All Students
```http
GET /students
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional) - Page number for pagination

### Create Student
```http
POST /students
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "user_id": 1,
    "school_class_id": 1,
    "section_id": 1,
    "roll_number": "STU001",
    "admission_date": "2024-01-15",
    "date_of_birth": "2010-05-20",
    "gender": "male",
    "phone": "+1234567890",
    "address": "123 Main St",
    "emergency_contact": "+0987654321"
}
```

### Get Single Student
```http
GET /students/{id}
Authorization: Bearer {token}
```

### Update Student
```http
PUT /students/{id}
Authorization: Bearer {token}
```

### Delete Student
```http
DELETE /students/{id}
Authorization: Bearer {token}
```

### Get Student Grades
```http
GET /students/{id}/grades
Authorization: Bearer {token}
```

### Get Student Attendance
```http
GET /students/{id}/attendance
Authorization: Bearer {token}
```

## Teacher Management

### Get All Teachers
```http
GET /teachers
Authorization: Bearer {token}
```

### Create Teacher
```http
POST /teachers
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "user_id": 1,
    "employee_id": "TEACH001",
    "hire_date": "2024-01-01",
    "qualification": "M.Ed Mathematics",
    "experience_years": 5,
    "phone": "+1234567890",
    "address": "456 Oak St",
    "emergency_contact": "+0987654321"
}
```

### Get Teacher Courses
```http
GET /teachers/{id}/courses
Authorization: Bearer {token}
```

### Get Teacher Students
```http
GET /teachers/{id}/students
Authorization: Bearer {token}
```

## Course Management

### Get All Courses
```http
GET /courses
Authorization: Bearer {token}
```

### Create Course
```http
POST /courses
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "Mathematics 101",
    "code": "MATH101",
    "description": "Basic mathematics course",
    "subject_id": 1,
    "teacher_id": 1,
    "school_class_id": 1,
    "section_id": 1,
    "credit_hours": 3,
    "semester": "Fall",
    "academic_year": "2024-2025"
}
```

### Get Course Assignments
```http
GET /courses/{id}/assignments
Authorization: Bearer {token}
```

### Get Course Students
```http
GET /courses/{id}/students
Authorization: Bearer {token}
```

### Enroll Student in Course
```http
POST /courses/{id}/enroll
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "student_id": 1
}
```

## Assignment Management

### Get All Assignments
```http
GET /assignments
Authorization: Bearer {token}
```

### Create Assignment
```http
POST /assignments
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "title": "Algebra Quiz 1",
    "description": "Basic algebra problems",
    "course_id": 1,
    "subject_id": 1,
    "due_date": "2024-02-15",
    "max_points": 100,
    "assignment_type": "quiz",
    "instructions": "Complete all problems showing work"
}
```

### Get Assignment Grades
```http
GET /assignments/{id}/grades
Authorization: Bearer {token}
```

### Submit Grade for Assignment
```http
POST /assignments/{id}/grade
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "student_id": 1,
    "points_earned": 85,
    "feedback": "Good work, minor calculation error on problem 3"
}
```

## Attendance Management

### Get All Attendance Records
```http
GET /attendance
Authorization: Bearer {token}
```

**Query Parameters:**
- `student_id` (optional) - Filter by student
- `course_id` (optional) - Filter by course
- `start_date` (optional) - Filter by date range
- `end_date` (optional) - Filter by date range

### Record Attendance
```http
POST /attendance
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "student_id": 1,
    "course_id": 1,
    "subject_id": 1,
    "date": "2024-01-15",
    "status": "present",
    "notes": "On time"
}
```

### Get Student Attendance Summary
```http
GET /attendance/student/{studentId}/summary
Authorization: Bearer {token}
```

**Query Parameters:**
- `start_date` (optional) - Default: start of current month
- `end_date` (optional) - Default: end of current month

### Bulk Record Attendance
```http
POST /attendance/bulk
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "course_id": 1,
    "subject_id": 1,
    "date": "2024-01-15",
    "attendance": [
        {
            "student_id": 1,
            "status": "present",
            "notes": "On time"
        },
        {
            "student_id": 2,
            "status": "absent",
            "notes": "Sick"
        }
    ]
}
```

## Announcement Management

### Get All Announcements
```http
GET /announcements
Authorization: Bearer {token}
```

**Query Parameters:**
- `target_audience` (optional) - Filter by audience (all, students, teachers, parents)
- `is_active` (optional) - Filter by active status

### Create Announcement
```http
POST /announcements
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "title": "School Holiday Notice",
    "content": "School will be closed on Friday for maintenance",
    "target_audience": "all",
    "priority": "high",
    "expires_at": "2024-02-01T00:00:00Z",
    "is_active": true
}
```

### Get Active Announcements
```http
GET /announcements-active
Authorization: Bearer {token}
```

**Query Parameters:**
- `audience` (optional) - Filter by target audience

## Fee Management

### Get All Fees
```http
GET /fees
Authorization: Bearer {token}
```

**Query Parameters:**
- `student_id` (optional) - Filter by student
- `status` (optional) - Filter by status (pending, paid, overdue, partial)
- `fee_type` (optional) - Filter by fee type

### Create Fee
```http
POST /fees
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "student_id": 1,
    "fee_type": "Tuition",
    "amount": 500.00,
    "due_date": "2024-02-01",
    "description": "Monthly tuition fee",
    "academic_year": "2024-2025",
    "semester": "Spring"
}
```

### Mark Fee as Paid
```http
POST /fees/{id}/mark-paid
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "payment_method": "Credit Card",
    "transaction_id": "TXN123456",
    "payment_notes": "Paid online"
}
```

### Get Student Fee Summary
```http
GET /fees/student/{studentId}/summary
Authorization: Bearer {token}
```

## Timetable Management

### Get All Timetable Entries
```http
GET /timetables
Authorization: Bearer {token}
```

**Query Parameters:**
- `school_class_id` (optional) - Filter by class
- `section_id` (optional) - Filter by section
- `day_of_week` (optional) - Filter by day

### Create Timetable Entry
```http
POST /timetables
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "course_id": 1,
    "subject_id": 1,
    "teacher_id": 1,
    "school_class_id": 1,
    "section_id": 1,
    "day_of_week": "monday",
    "start_time": "09:00",
    "end_time": "10:00",
    "room": "Room 101"
}
```

### Get Teacher Timetable
```http
GET /timetables/teacher/{teacherId}
Authorization: Bearer {token}
```

### Get Class Timetable
```http
GET /timetables/class
Authorization: Bearer {token}
```

**Query Parameters:**
- `school_class_id` (required) - Class ID
- `section_id` (optional) - Section ID

## Dashboard Statistics

### Get Dashboard Stats
```http
GET /dashboard/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "total_students": 150,
        "total_teachers": 25,
        "total_courses": 30,
        "total_assignments": 120,
        "pending_fees": 15000.00,
        "recent_announcements": [...]
    }
}
```

## Error Responses

All endpoints return errors in the following format:

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting

The API is rate limited to 60 requests per minute per user.

## Pagination

List endpoints return paginated results:

```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [...],
        "first_page_url": "...",
        "from": 1,
        "last_page": 5,
        "last_page_url": "...",
        "next_page_url": "...",
        "path": "...",
        "per_page": 15,
        "prev_page_url": null,
        "to": 15,
        "total": 150
    }
}
```
