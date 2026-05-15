# Fitur Trainer-Member Integration

## Deskripsi Fitur

Fitur ini mengintegrasikan sistem penyewaan personal trainer dengan komunikasi dan notifikasi antara member dan trainer di aplikasi Fitnez.

## Alur Fitur

### 1. **Sewa Personal Trainer** (`/member/hire-trainer`)
- **Fungsi**: Member dapat memilih dan menyewa trainer yang telah terdaftar
- **Proses**:
  1. Member melihat daftar trainer yang tersedia
  2. Member memilih trainer berdasarkan spesialisasi, pengalaman, dan harga
  3. Member membuat booking dengan memilih:
     - Tanggal dan waktu sesi
     - Tipe sesi (Online/Offline)
     - Lokasi (jika offline)
     - Catatan untuk trainer
  4. Sistem menghitung estimasi biaya otomatis
  5. Booking dibuat dengan status "pending"
  6. **Notifikasi otomatis dikirim ke trainer**

### 2. **Pendaftaran Menjadi Trainer** (`/trainer/daftar`)
- **Fungsi**: Member dapat mendaftar untuk menjadi trainer
- **Proses**:
  1. Member mengakses menu "Hire a Trainer" → "Daftar Jadi Trainer"
  2. Upload dokumen:
     - CV (PDF)
     - Sertifikat (PDF)
  3. Aplikasi dikirim ke admin untuk review
  4. Admin mereview dan approve/reject
  5. Jika approved, member mendapat akses trainer workspace

### 3. **Notifikasi Trainer** (`/trainer/notifications`)
- **Fungsi**: Trainer menerima notifikasi otomatis untuk:
  - **Permintaan sesi baru**: Saat member membuat booking
  - **Pembayaran masuk**: Saat booking dikonfirmasi
- **Informasi notifikasi**:
  - Nama member
  - Tanggal dan waktu sesi
  - Total pembayaran (untuk notifikasi konfirmasi)
- **Fitur**:
  - Badge unread count di menu
  - Mark as read per notifikasi
  - Mark all as read

### 4. **Chat Member-Trainer** (`/member/chat` & `/trainer/chat`)
- **Fungsi**: Komunikasi langsung antara member dan trainer
- **Fitur**:
  - Real-time messaging
  - Kontak otomatis muncul setelah booking dibuat
  - Riwayat percakapan tersimpan
  - Support untuk konsultasi program latihan
- **Akses**:
  - Member: Menu "Chat"
  - Trainer: Menu "Chat" di trainer workspace

## Struktur Menu

### Member Sidebar
```
🏠 Dashboard
🏋️ Workout Plan
🥗 Meal Plan
🤝 Hire a Trainer
   ├─ 🤝 Sewa Trainer          → Pilih dan booking trainer
   └─ ⭐ Daftar Jadi Trainer    → Pendaftaran menjadi trainer
💬 Chat                         → Komunikasi dengan trainer
💳 Memberships
👤 Profile
```

### Trainer Sidebar
```
📊 Dashboard
🗓️ Schedule
🏃 Classes
👥 Members
💬 Chat                         → Komunikasi dengan member
💰 Rent History
🔔 Notifications                → Notifikasi booking & pembayaran
👤 Profile
```

## Integrasi Backend

### Notifikasi Otomatis
Sistem secara otomatis membuat notifikasi di `BookingController`:

1. **Saat booking dibuat** (`store` method):
```php
Notification::create([
    'user_id'           => $booking->trainer_id,
    'title'             => 'Permintaan sesi baru',
    'body'              => "{$memberName} memesan sesi pada {$date}...",
    'notification_type' => 'booking_request',
]);
```

2. **Saat booking dikonfirmasi** (`updateStatus` method):
```php
Notification::create([
    'user_id'           => $booking->trainer_id,
    'title'             => 'Sesi dikonfirmasi & pembayaran',
    'body'              => "Total pembayaran Rp {$price}.",
    'notification_type' => 'payment_in',
]);
```

## API Endpoints

### Member
- `GET /api/trainers` - List trainer tersedia
- `POST /api/bookings` - Buat booking baru
- `GET /api/bookings` - List booking member
- `GET /api/chat/contacts` - List kontak chat
- `GET /api/chat/messages` - Ambil pesan
- `POST /api/chat/messages` - Kirim pesan
- `POST /api/trainer/application` - Submit aplikasi trainer

### Trainer
- `GET /api/bookings` - List booking trainer
- `PATCH /api/bookings/{id}/status` - Update status booking
- `GET /api/notifications` - List notifikasi
- `PATCH /api/notifications/{id}/read` - Mark as read
- `PATCH /api/notifications/read-all` - Mark all as read
- `GET /api/chat/contacts` - List kontak chat
- `GET /api/chat/messages` - Ambil pesan
- `POST /api/chat/messages` - Kirim pesan

## Testing

### Akun Demo
```
Member:  member@fitnez.test / member123
Trainer: trainer@fitnez.test / trainer123
Admin:   admin@fitnez.test / admin123
```

### Skenario Testing
1. **Booking Flow**:
   - Login sebagai member
   - Buka "Hire a Trainer" → "Sewa Trainer"
   - Pilih trainer dan buat booking
   - Cek notifikasi trainer

2. **Pendaftaran Trainer**:
   - Login sebagai member
   - Buka "Hire a Trainer" → "Daftar Jadi Trainer"
   - Upload CV dan sertifikat
   - Login sebagai admin untuk approve

3. **Chat**:
   - Setelah booking dibuat
   - Buka menu "Chat"
   - Kontak trainer/member otomatis muncul
   - Kirim pesan

4. **Notifikasi**:
   - Login sebagai trainer
   - Cek badge notifikasi di menu
   - Buka "Notifications"
   - Mark as read

## File yang Dimodifikasi/Ditambahkan

### Frontend
- `frontend/src/router/index.ts` - Route `/trainer/daftar`
- `frontend/src/pages/member/MemberTrainerApplyPage.vue` - Page pendaftaran trainer
- `frontend/src/components/layout/sidebarItems.ts` - Submenu structure
- `frontend/src/components/layout/WorkspaceSidebar.vue` - Support submenu
- `frontend/src/components/layout/WorkspaceLayout.vue` - Type definition submenu

### Backend
- Tidak ada perubahan (fitur sudah ada di project team)

## Fitur yang Sudah Ada di Project Team
- ✅ BookingController dengan notifikasi otomatis
- ✅ NotificationController dan NotificationStore
- ✅ ChatController untuk komunikasi
- ✅ TrainerApplicationApi untuk pendaftaran
- ✅ BookingModal dengan pemilihan online/offline
- ✅ TrainerNotificationsPage

## Kesimpulan

Fitur ini menghubungkan member dan trainer melalui:
1. **Booking system** - Member pilih dan sewa trainer
2. **Notification system** - Trainer dapat pengingat otomatis
3. **Chat system** - Komunikasi langsung untuk konsultasi
4. **Application system** - Member bisa daftar jadi trainer

Semua fitur terintegrasi dengan UI/UX yang konsisten dan mudah diakses melalui sidebar navigation.
