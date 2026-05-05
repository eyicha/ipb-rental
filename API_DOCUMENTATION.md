## IPB RENTAL API Documentation

### 📋 Base URL
```
http://localhost/api
```

### 🔑 Authentication
- Most endpoints require **Sanctum Token** authentication
- Get token from `/auth/login` or `/auth/register`
- Include in header: `Authorization: Bearer {token}`

---

## 🚀 PUBLIC ENDPOINTS (No Auth Required)

### Authentication
```
POST   /auth/register          - Register new user
POST   /auth/login             - Login user
```

### Items
```
GET    /items                  - Get all active items (paginated)
  Filters:
    ?q=search_term            - Search by name/description
    ?kategori=elektronik      - Filter by category
    ?sort=popular|harga_asc|harga_desc|rating|terbaru
    ?per_page=12

GET    /items/categories       - Get available categories
GET    /items/{id}             - Get item detail
```

### Profiles
```
GET    /profiles/{user_id}           - Get user profile
GET    /profiles/{user_id}/items     - Get user's items (paginated)
GET    /profiles/{user_id}/statistics - Get user statistics
```

---

## 🔐 PROTECTED ENDPOINTS (Requires Auth Token)

### Authentication
```
POST   /auth/logout            - Logout user
GET    /auth/me                - Get authenticated user info
```

### Profile Management
```
GET    /profile                - Get my profile
PUT    /profile                - Update my profile
  Fields: name, email, nim, whatsapp, lokasi, avatar (file)

POST   /profile/change-password - Change password
  Fields: current_password, password, password_confirmation
```

### My Items (User's own items)
```
GET    /my-items               - List my items (paginated)
POST   /my-items               - Create new item
  Fields: nama, deskripsi, kategori, harga_per_hari, deposit, stok, foto[] (array of files)

PUT    /my-items/{id}          - Update my item
  Fields: nama, deskripsi, kategori, harga_per_hari, deposit, stok, foto[] (optional)

DELETE /my-items/{id}          - Delete my item

POST   /my-items/{id}/toggle-status - Toggle item active/inactive
```

### Rentals
```
GET    /rentals                - Get my rentals (as penyewa & pemilik)
  ?status=pending|dp_paid|active|finished|declined
  ?per_page=10

POST   /rentals                - Create rental request
  Fields: item_id, tanggal_mulai, tanggal_selesai, catatan (optional)

GET    /rentals/{id}           - Get rental detail

POST   /rentals/{id}/action    - Accept/decline/mark as returned rental
  Fields: action (accept|decline|returned), bukti_dp (file, optional)

POST   /rentals/{id}/review    - Add review/rating to rental
  Fields: rating (1-5), ulasan (string, max 500)
```

### Chat/Messages
```
GET    /chat                   - Get conversation list
POST   /chat                   - Send message to user
  Fields: receiver_id, pesan (string, max 500)

GET    /chat/user/{user_id}    - Get messages with specific user
  ?per_page=20

POST   /chat/message/{message_id}/read - Mark message as read

POST   /chat/user/{user_id}/read-all   - Mark all messages from user as read
```

### Reports
```
GET    /reports                - Get my reports
  ?status=pending|diproses|selesai|ditolak
  ?per_page=10

POST   /reports                - Create report
  Fields: terlapor_id, item_id (optional), rental_id (optional),
          kategori, deskripsi, bukti[] (array of files, optional)

GET    /reports/{id}           - Get report detail
```

---

## 👨‍💼 ADMIN ONLY ENDPOINTS

### Admin Reports Management
```
GET    /admin/reports          - Get all reports
  ?status=pending|diproses|selesai|ditolak
  ?per_page=15

PUT    /admin/reports/{id}     - Update report status & add response
  Fields: status, balasan_admin (optional)
```

---

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success message",
  "data": {
    // Response data here
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "Items retrieved successfully",
  "data": [
    // Items here
  ],
  "pagination": {
    "total": 100,
    "per_page": 12,
    "current_page": 1,
    "last_page": 9,
    "from": 1,
    "to": 12
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": null
}
```

### Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

---

## 🧪 Example API Calls

### Register & Login
```bash
# Register
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "nim": "J0403241001",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Browse Items
```bash
# Get all items
curl http://localhost/api/items

# Search items
curl "http://localhost/api/items?q=laptop&kategori=elektronik&sort=harga_asc"

# Get item detail
curl http://localhost/api/items/1
```

### Create Rental
```bash
curl -X POST http://localhost/api/rentals \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "item_id": 1,
    "tanggal_mulai": "2026-05-10",
    "tanggal_selesai": "2026-05-15",
    "catatan": "Please prepare it early"
  }'
```

### Send Message
```bash
curl -X POST http://localhost/api/chat \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "receiver_id": 2,
    "pesan": "Halo, apakah item masih tersedia?"
  }'
```

---

## 🛠️ Status Codes
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## 📝 Notes
- All timestamps are in UTC
- File uploads use multipart/form-data
- Category values: elektronik, fotografi, audio, drone, akademik, olahraga, perabot, kendaraan, lainnya
- Rental status: pending, dp_paid, active, finished, declined
- Report status: pending, diproses, selesai, ditolak
