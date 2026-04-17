# Mobile App API Integration Guide

Complete API documentation for the Event Management Mobile App.

## Base URL

```
http://192.168.1.211:8000/api
```

## Authentication

All protected endpoints require Bearer token in Authorization header:

```
Authorization: Bearer {token}
```

## Response Format

All responses follow this format:

```json
{
  "success": true|false,
  "message": "Description",
  "data": {},
  "error": "Error details (if any)"
}
```

---

## Public Endpoints

### 1. Get Events List

**Gets a list of events with filtering, search, and pagination**

```
GET /events
```

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `page` | integer | Page number | 1 |
| `limit` | integer | Items per page | 10 |
| `search` | string | Search by name/location | "Hội thảo" |
| `trang_thai` | string | Event status filter | "sap_to_chuc" |
| `loai` | integer | Event type/category ID | 1 |

**Status Options:**

- `sap_to_chuc` - Upcoming
- `dang_dien_ra` - Ongoing
- `da_ket_thuc` - Completed

**Example Request:**

```javascript
GET /events?page=1&limit=10&search=sự kiện&trang_thai=sap_to_chuc&loai=2
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "ma_su_kien": 1,
            "ten_su_kien": "Hội thảo Công Nghệ",
            "mo_ta": "Hội thảo về công nghệ...",
            "mo_ta_chi_tiet": "Detailed description...",
            "thoi_gian_bat_dau": "2024-04-20 14:00:00",
            "thoi_gian_ket_thuc": "2024-04-20 16:00:00",
            "dia_diem": "Phòng 101, Tòa A",
            "anh_su_kien": "events/image.jpg",
            "trang_thai_thuc_te": "sap_to_chuc",
            "so_luong_hien_tai": 25,
            "so_luong_toi_da": 50,
            "diem_cong": 10,
            "ma_loai_su_kien": 2,
            "loaiSuKien": {
                "ma_loai_su_kien": 2,
                "ten_loai": "Hội thảo"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 45,
        "last_page": 5
    }
}
```

---

### 2. Get Event Detail

**Gets detailed information about a specific event**

```
GET /events/{id}
```

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | Event ID |

**Example Request:**

```javascript
GET / events / 1;
```

**Response:**

```json
{
    "success": true,
    "data": {
        "ma_su_kien": 1,
        "ten_su_kien": "Hội thảo Công Nghệ",
        "mo_ta": "...",
        "mo_ta_chi_tiet": "Detailed description...",
        "thoi_gian_bat_dau": "2024-04-20 14:00:00",
        "thoi_gian_ket_thuc": "2024-04-20 16:00:00",
        "dia_diem": "Phòng 101, Tòa A",
        "anh_su_kien": "events/image.jpg",
        "trang_thai_thuc_te": "sap_to_chuc",
        "so_luong_hien_tai": 25,
        "so_luong_toi_da": 50,
        "diem_cong": 10,
        "loaiSuKien": {
            "ma_loai_su_kien": 2,
            "ten_loai": "Hội thảo"
        },
        "nguoiTao": {
            "ma_sinh_vien": 1,
            "ho_ten": "Nguyễn Văn A",
            "email": "nguyenvana@ntu.edu"
        }
    }
}
```

---

### 3. Get Event Types

**Gets list of all event categories/types**

```
GET /api/event-types
```

**Example Request:**

```javascript
GET / api / event - types;
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "ma_loai_su_kien": 1,
            "ten_loai": "Talkshow"
        },
        {
            "ma_loai_su_kien": 2,
            "ten_loai": "Hội thảo"
        },
        {
            "ma_loai_su_kien": 3,
            "ten_loai": "Workshop"
        }
    ]
}
```

---

### 4. Login

**Authenticates user and returns token**

```
POST /login
```

**Request Body:**

```json
{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "Mobile App"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "user": {
            "ma_sinh_vien": 1,
            "ho_ten": "Nguyễn Văn A",
            "email": "user@example.com",
            "so_dien_thoai": "0123456789",
            "anh_dai_dien": null
        }
    }
}
```

---

## Protected Endpoints (Require Authentication)

### User Management

#### Get Current User

```
GET /user
```

**Response:**

```json
{
    "success": true,
    "data": {
        "ma_sinh_vien": 1,
        "ho_ten": "Nguyễn Văn A",
        "email": "user@example.com"
    }
}
```

#### Get User Profile

```
GET /user/profile
```

#### Update User Profile

```
POST /user/profile/update
```

**Request Body:**

```json
{
    "ho_ten": "Nguyễn Văn B",
    "so_dien_thoai": "0987654321"
}
```

#### Change Password

```
POST /user/change-password
```

**Request Body:**

```json
{
    "current_password": "old_password",
    "new_password": "new_password",
    "new_password_confirmation": "new_password"
}
```

---

### Event Registration

#### Register for Event

**User registers to participate in an event**

```
POST /registrations/{eventId}
```

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `eventId` | integer | Event ID |

**Response:**

```json
{
    "success": true,
    "message": "Registration successful",
    "data": {
        "ma_dang_ky": 1,
        "ma_sinh_vien": 1,
        "ma_su_kien": 1,
        "trang_thai_tham_gia": "da_dang_ky",
        "thoi_gian_dang_ky": "2024-04-15 10:00:00"
    }
}
```

#### Cancel Registration

**User cancels event registration**

```
DELETE /registrations/{eventId}
```

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `eventId` | integer | Event ID |

**Response:**

```json
{
    "success": true,
    "message": "Registration cancelled"
}
```

#### Check Registration Status

**Checks if user is registered for an event**

```
GET /registrations/check/{eventId}
```

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `eventId` | integer | Event ID |

**Response:**

```json
{
    "success": true,
    "data": {
        "is_registered": true,
        "registration": {
            "ma_dang_ky": 1,
            "ma_sinh_vien": 1,
            "trang_thai_tham_gia": "da_dang_ky"
        }
    }
}
```

#### Get Registration History

**Gets user's event participation history**

```
GET /registrations/history?page=1&limit=20
```

**Query Parameters:**
| Parameter | Type | Default |
|-----------|------|---------|
| `page` | integer | 1 |
| `limit` | integer | 20 |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "ma_dang_ky": 1,
            "ma_su_kien": 1,
            "ten_su_kien": "Hội thảo Công Nghệ",
            "trang_thai_tham_gia": "da_tham_gia",
            "thoi_gian_dang_ky": "2024-04-15 10:00:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 20,
        "total": 15
    }
}
```

---

### QR Code Check-in

#### Scan QR Code (Single)

**Submits a single QR code scan for check-in**

```
POST /registrations/app-scan
```

**Request Body:**

```json
{
    "ma_su_kien": 1,
    "diff": 2500
}
```

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `ma_su_kien` | integer | Event ID |
| `diff` | integer | Time difference from QR generation (ms) |

**Response:**

```json
{
    "success": true,
    "message": "Check-in successful",
    "data": {
        "message": "Bạn đã điểm danh thành công",
        "status": "success"
    }
}
```

#### Scan QR Code Batch

**Submits multiple QR code scans**

```
POST /registrations/app-scan-batch
```

**Request Body:**

```json
{
    "scans": [
        {
            "ma_su_kien": 1,
            "diff": 2500
        },
        {
            "ma_su_kien": 2,
            "diff": 3000
        }
    ]
}
```

---

### Notifications

#### Get Notifications

```
GET /notifications?page=1&limit=20
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "ma_thong_bao": 1,
            "tieu_de": "Notification Title",
            "noi_dung": "Notification content",
            "da_doc": false,
            "created_at": "2024-04-15 10:00:00"
        }
    ]
}
```

#### Get Unread Notifications

```
GET /notifications/unread
```

#### Mark Notification as Read

```
POST /notifications/{id}/read
```

#### Mark All as Read

```
POST /notifications/read-all
```

#### Delete Notification

```
DELETE /notifications/{id}
```

---

### Points

#### Get Total Points

```
GET /points/total
```

**Response:**

```json
{
    "success": true,
    "data": {
        "total_points": 450,
        "current_month": 75
    }
}
```

#### Get Points History

```
GET /points/history?page=1&limit=20
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "ma_diem": 1,
            "so_diem": 10,
            "ly_do": "Check-in event",
            "thoi_gian": "2024-04-15 14:00:00"
        }
    ]
}
```

#### Get Leaderboard

```
GET /points/leaderboard?limit=20
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "rank": 1,
            "ma_sinh_vien": 1,
            "ho_ten": "Nguyễn Văn A",
            "total_points": 500
        }
    ]
}
```

---

## Error Codes

| Code | Message       | Description                   |
| ---- | ------------- | ----------------------------- |
| 200  | Success       | Request successful            |
| 201  | Created       | Resource created successfully |
| 400  | Bad Request   | Invalid parameters            |
| 401  | Unauthorized  | Missing or invalid token      |
| 403  | Forbidden     | Not authorized to access      |
| 404  | Not Found     | Resource not found            |
| 422  | Unprocessable | Validation error              |
| 500  | Server Error  | Internal server error         |

---

## Implementation Examples

### Example 1: Fetch Events with Filter

```javascript
import api from "./services/api";

async function getEvents(search, status, category, page = 1) {
    try {
        const response = await api.get("/events", {
            params: {
                search,
                trang_thai: status,
                loai: category,
                page,
                limit: 10,
            },
        });

        return response.data;
    } catch (error) {
        console.error("Error:", error);
    }
}
```

### Example 2: Register for Event

```javascript
async function registerEvent(eventId) {
    try {
        const response = await api.post(`/registrations/${eventId}`);
        return response.data;
    } catch (error) {
        Alert.alert("Error", error.response?.data?.message);
    }
}
```

### Example 3: Check Registration Status

```javascript
async function checkRegistration(eventId) {
    try {
        const response = await api.get(`/registrations/check/${eventId}`);
        const { is_registered } = response.data.data;
        return is_registered;
    } catch (error) {
        console.error("Error:", error);
    }
}
```

---

## CORS Headers

The API should have these CORS headers configured:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

## Rate Limiting

- No rate limiting currently set
- Recommended: 100 requests/minute per IP

## Best Practices

1. Always use proper error handling
2. Implement request/response interceptors
3. Store token securely (AsyncStorage)
4. Validate input before sending
5. Use pagination for large datasets
6. Implement retry logic for failed requests
7. Cache responses when appropriate

---

**Last Updated**: 2024-04-15  
**API Version**: 1.0  
**Status**: Production Ready
