# 🔐 Admin Panel Access Guide

## Default Admin Account

Saat aplikasi pertama kali di-setup, ada 1 akun admin default yang dibuat otomatis:

```
Email: admin@ipbrental.ac.id
Password: admin123
```

## Cara Login ke Admin Panel

1. **Go to**: `http://localhost:8000/admin` (atau sesuai domain Anda)
2. **Klik** tombol login jika belum authenticated
3. **Enter credentials**:
   - Email: `admin@ipbrental.ac.id`
   - Password: `admin123`
4. **Submit** → Akan langsung redirect ke `/admin` dashboard

## Admin Panel Features

### 📊 Dashboard
- View overall statistics:
  - Total users
  - Total items
  - Total rentals
  - Pending reports
  - Pending verifications
  - Rentals by status breakdown
- See recent rental transactions (latest 8)

### 👥 Users Management
- **View all users** (paginated, 20 per page)
- **Search** by name or email
- **Filter** by role (user/admin)
- **Change user role** from "user" to "admin" or vice versa
- **Delete users** (with confirmation)

### 📦 Items Management
- View all items uploaded by users
- **Update** item details (status, price, etc.)
- **Delete** inappropriate or spam items

### 🔄 Rentals Management
- View all rental transactions
- See rental details (item, renter, owner, dates, status)
- Track rental status flow

### 📋 Reports Management
- View user reports (disputes, complaints)
- Filter by status (pending, resolved, rejected)
- **View report details** including evidence/attachments
- **Update report status** to resolve issues
- Respond or add notes to reports

### ✅ Verifications Management
- **View pending KTM/KTP uploads** from users
- Review document images
- **Approve** verification → status becomes "verified"
- **Reject** verification with notes → user must reupload
- See verification history for each user

## Creating Additional Admin Users

If you need more admin accounts:

1. **Login** dengan akun admin default
2. **Go to**: `/admin/users`
3. **Find** the user yang ingin dijadikan admin
4. **Click** atau update role dropdown ke "admin"
5. **Save** changes
6. User tersebut sekarang punya akses ke `/admin`

## Security Notes

⚠️ **Important**:
1. **Change default password** segera setelah pertama kali setup
   - Edit password via Profile page atau database
2. **Create dedicated admin accounts** untuk setiap admin
   - Jangan share 1 password untuk multiple people
3. **Backup database** secara regular
4. **Review reports & verifications** secara berkala
5. **Monitor user activity** melalui dashboard

## Troubleshooting

**Q: Can't login to admin panel**
A: Check if:
- Email dan password benar (case-sensitive)
- User account exist di database
- User role = 'admin'
- Session cookie not blocked

**Q: Don't see certain sections in admin**
A: Admin must have `role = 'admin'` di database users table

**Q: Admin dashboard tidak load**
A: Check:
- `/storage/logs/laravel.log` untuk error messages
- Database connection working
- All middleware properly configured

## Database Admin Check

Jika perlu verify via database:

```sql
db.users.findOne({email: "admin@ipbrental.ac.id"})
```

Result should show:
```json
{
  "_id": ObjectId(...),
  "email": "admin@ipbrental.ac.id",
  "role": "admin",
  ...
}
```

---

**Last Updated**: 2026-05-05
