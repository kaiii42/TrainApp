# TrainApp API Documentation

Dokumentasi lengkap REST API untuk integrasi aplikasi Android TrainApp.

---

## Informasi Umum

### Base URL

```
Production: https://train-admin.rf.gd/api
Local: http://127.0.0.1:8000/api
```

### Headers Wajib

Semua request harus menyertakan header berikut:

```
Content-Type: application/json
Accept: application/json
```

Untuk endpoint yang memerlukan autentikasi, tambahkan:

```
Authorization: Bearer {token}
```

### Format Response

Semua response menggunakan format JSON dengan struktur:

**Success Response:**
```json
{
    "success": true,
    "message": "Pesan sukses",
    "data": { ... }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Pesan error",
    "errors": { ... }
}
```

### HTTP Status Codes

| Code | Keterangan |
|------|------------|
| 200 | OK - Request berhasil |
| 201 | Created - Data berhasil dibuat |
| 400 | Bad Request - Request tidak valid |
| 401 | Unauthorized - Token tidak valid/expired |
| 403 | Forbidden - Akses ditolak |
| 404 | Not Found - Data tidak ditemukan |
| 422 | Unprocessable Entity - Validasi gagal |
| 500 | Internal Server Error - Error server |

---

## Daftar Endpoint

### Public Endpoints (Tanpa Autentikasi)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/register` | Registrasi user baru |
| POST | `/login` | Login user |
| GET | `/stations` | Daftar stasiun |
| GET | `/stations/{id}` | Detail stasiun |
| GET | `/schedules` | Daftar jadwal |
| GET | `/schedules/{id}` | Detail jadwal |
| GET | `/schedules/search` | Cari jadwal |
| GET | `/banners` | Daftar promo banner |

### Protected Endpoints (Memerlukan Token)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/user` | Profil user login |
| PUT | `/user/profile` | Update profil |
| POST | `/logout` | Logout user |
| GET | `/transactions` | Daftar transaksi user |
| POST | `/transactions` | Buat transaksi baru |
| GET | `/transactions/{id}` | Detail transaksi |
| POST | `/transactions/{id}/cancel` | Batalkan transaksi |

---

## 1. Autentikasi

### 1.1 Register

Mendaftarkan user baru ke sistem.

**Endpoint:**
```
POST /api/register
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "johndoe@example.com",
    "phone": "081234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validasi:**
| Field | Rules |
|-------|-------|
| name | Wajib, string, max 255 karakter |
| email | Wajib, email valid, unik |
| phone | Opsional, string, max 20 karakter |
| password | Wajib, min 8 karakter, harus dikonfirmasi |

**Response Sukses (201):**
```json
{
    "success": true,
    "message": "Registration successful",
    "data": {
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "johndoe@example.com",
            "phone": "081234567890",
            "role": "user",
            "avatar": null,
            "is_active": true,
            "created_at": "2026-01-17T10:30:00.000000Z",
            "updated_at": "2026-01-17T10:30:00.000000Z"
        },
        "token": "2|laravel_sanctum_xxxxxxxxxxxxxxxxxxxxxxxx"
    }
}
```

**Response Error - Validasi (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password field confirmation does not match."]
    }
}
```

**Contoh Android (Retrofit):**
```kotlin
@POST("register")
suspend fun register(@Body request: RegisterRequest): Response<AuthResponse>

data class RegisterRequest(
    val name: String,
    val email: String,
    val phone: String?,
    val password: String,
    val password_confirmation: String
)
```

---

### 1.2 Login

Login user dan mendapatkan token autentikasi.

**Endpoint:**
```
POST /api/login
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "johndoe@example.com",
    "password": "password123"
}
```

**Validasi:**
| Field | Rules |
|-------|-------|
| email | Wajib, email valid |
| password | Wajib, string |

**Response Sukses (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "johndoe@example.com",
            "phone": "081234567890",
            "role": "user",
            "avatar": null,
            "is_active": true,
            "created_at": "2026-01-17T10:30:00.000000Z",
            "updated_at": "2026-01-17T10:30:00.000000Z"
        },
        "token": "3|laravel_sanctum_xxxxxxxxxxxxxxxxxxxxxxxx"
    }
}
```

**Response Error - Kredensial Salah (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

**Response Error - Akun Nonaktif (403):**
```json
{
    "success": false,
    "message": "Your account is inactive"
}
```

**Contoh Android (Retrofit):**
```kotlin
@POST("login")
suspend fun login(@Body request: LoginRequest): Response<AuthResponse>

data class LoginRequest(
    val email: String,
    val password: String
)

data class AuthResponse(
    val success: Boolean,
    val message: String,
    val data: AuthData?
)

data class AuthData(
    val user: User,
    val token: String
)
```

---

### 1.3 Logout

Logout user dan menghapus token.

**Endpoint:**
```
POST /api/logout
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

**Request Body:** Tidak ada

**Response Sukses (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

**Response Error - Unauthorized (401):**
```json
{
    "message": "Unauthenticated."
}
```

**Contoh Android (Retrofit):**
```kotlin
@POST("logout")
suspend fun logout(@Header("Authorization") token: String): Response<BaseResponse>
```

---

### 1.4 Get User Profile

Mendapatkan data profil user yang sedang login.

**Endpoint:**
```
GET /api/user
```

**Headers:**
```
Accept: application/json
Authorization: Bearer {token}
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "phone": "081234567890",
        "role": "user",
        "avatar": null,
        "is_active": true,
        "created_at": "2026-01-17T10:30:00.000000Z",
        "updated_at": "2026-01-17T10:30:00.000000Z"
    }
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("user")
suspend fun getUser(@Header("Authorization") token: String): Response<UserResponse>
```

---

### 1.5 Update Profile

Mengupdate data profil user.

**Endpoint:**
```
PUT /api/user/profile
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "phone": "089876543210"
}
```

**Validasi:**
| Field | Rules |
|-------|-------|
| name | Opsional, string, max 255 karakter |
| phone | Opsional, string, max 20 karakter |

**Response Sukses (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 2,
        "name": "John Doe Updated",
        "email": "johndoe@example.com",
        "phone": "089876543210",
        "role": "user",
        "avatar": null,
        "is_active": true,
        "created_at": "2026-01-17T10:30:00.000000Z",
        "updated_at": "2026-01-17T11:00:00.000000Z"
    }
}
```

**Contoh Android (Retrofit):**
```kotlin
@PUT("user/profile")
suspend fun updateProfile(
    @Header("Authorization") token: String,
    @Body request: UpdateProfileRequest
): Response<UserResponse>

data class UpdateProfileRequest(
    val name: String?,
    val phone: String?
)
```

---

## 2. Stasiun

### 2.1 Get All Stations

Mendapatkan daftar semua stasiun aktif.

**Endpoint:**
```
GET /api/stations
```

**Headers:**
```
Accept: application/json
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Stasiun Kota Baru Malang",
            "code": "ML",
            "city": "Malang",
            "latitude": "-7.9770000",
            "longitude": "112.6370000",
            "is_active": true,
            "created_at": "2026-01-17T10:00:00.000000Z",
            "updated_at": "2026-01-17T10:00:00.000000Z"
        },
        {
            "id": 2,
            "name": "Stasiun Surabaya Gubeng",
            "code": "SGU",
            "city": "Surabaya",
            "latitude": "-7.2650000",
            "longitude": "112.7520000",
            "is_active": true,
            "created_at": "2026-01-17T10:00:00.000000Z",
            "updated_at": "2026-01-17T10:00:00.000000Z"
        }
    ]
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("stations")
suspend fun getStations(): Response<StationsResponse>

data class StationsResponse(
    val success: Boolean,
    val data: List<Station>
)

data class Station(
    val id: Int,
    val name: String,
    val code: String,
    val city: String,
    val latitude: String?,
    val longitude: String?,
    val is_active: Boolean,
    val created_at: String,
    val updated_at: String
)
```

---

### 2.2 Get Station Detail

Mendapatkan detail stasiun berdasarkan ID.

**Endpoint:**
```
GET /api/stations/{id}
```

**Parameters:**
| Nama | Tipe | Keterangan |
|------|------|------------|
| id | Integer | ID stasiun (path parameter) |

**Headers:**
```
Accept: application/json
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Stasiun Kota Baru Malang",
        "code": "ML",
        "city": "Malang",
        "latitude": "-7.9770000",
        "longitude": "112.6370000",
        "is_active": true,
        "created_at": "2026-01-17T10:00:00.000000Z",
        "updated_at": "2026-01-17T10:00:00.000000Z"
    }
}
```

**Response Error - Not Found (404):**
```json
{
    "success": false,
    "message": "Station not found"
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("stations/{id}")
suspend fun getStation(@Path("id") id: Int): Response<StationDetailResponse>
```

---

## 3. Jadwal

### 3.1 Get All Schedules

Mendapatkan daftar semua jadwal aktif.

**Endpoint:**
```
GET /api/schedules
```

**Headers:**
```
Accept: application/json
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "train_id": 8,
            "origin_station_id": 1,
            "destination_station_id": 4,
            "departure_time": "06:00:00",
            "arrival_time": "08:30:00",
            "duration": "2j 30m",
            "price": "75000.00",
            "available_days": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
            "is_active": true,
            "created_at": "2026-01-17T10:00:00.000000Z",
            "updated_at": "2026-01-17T10:00:00.000000Z",
            "train": {
                "id": 8,
                "name": "Penataran",
                "train_number": "KA-008",
                "class": "ekonomi",
                "capacity": 600
            },
            "origin_station": {
                "id": 1,
                "name": "Stasiun Kota Baru Malang",
                "code": "ML",
                "city": "Malang"
            },
            "destination_station": {
                "id": 4,
                "name": "Stasiun Surabaya Gubeng",
                "code": "SGU",
                "city": "Surabaya"
            }
        }
    ]
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("schedules")
suspend fun getSchedules(): Response<SchedulesResponse>

data class SchedulesResponse(
    val success: Boolean,
    val data: List<Schedule>
)

data class Schedule(
    val id: Int,
    val train_id: Int,
    val origin_station_id: Int,
    val destination_station_id: Int,
    val departure_time: String,
    val arrival_time: String,
    val duration: String,
    val price: String,
    val available_days: List<String>,
    val is_active: Boolean,
    val train: Train,
    val origin_station: Station,
    val destination_station: Station
)

data class Train(
    val id: Int,
    val name: String,
    val train_number: String,
    @SerializedName("class") val trainClass: String,
    val capacity: Int
)
```

---

### 3.2 Get Schedule Detail

Mendapatkan detail jadwal berdasarkan ID.

**Endpoint:**
```
GET /api/schedules/{id}
```

**Parameters:**
| Nama | Tipe | Keterangan |
|------|------|------------|
| id | Integer | ID jadwal (path parameter) |

**Headers:**
```
Accept: application/json
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "train_id": 8,
        "origin_station_id": 1,
        "destination_station_id": 4,
        "departure_time": "06:00:00",
        "arrival_time": "08:30:00",
        "duration": "2j 30m",
        "price": "75000.00",
        "available_days": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
        "is_active": true,
        "train": {
            "id": 8,
            "name": "Penataran",
            "train_number": "KA-008",
            "class": "ekonomi",
            "capacity": 600
        },
        "origin_station": {
            "id": 1,
            "name": "Stasiun Kota Baru Malang",
            "code": "ML",
            "city": "Malang"
        },
        "destination_station": {
            "id": 4,
            "name": "Stasiun Surabaya Gubeng",
            "code": "SGU",
            "city": "Surabaya"
        }
    }
}
```

**Response Error - Not Found (404):**
```json
{
    "success": false,
    "message": "Schedule not found"
}
```

---

### 3.3 Search Schedules

Mencari jadwal berdasarkan stasiun asal, tujuan, dan tanggal.

**Endpoint:**
```
GET /api/schedules/search
```

**Query Parameters:**
| Nama | Tipe | Wajib | Keterangan |
|------|------|-------|------------|
| origin | Integer | Ya | ID stasiun asal |
| destination | Integer | Ya | ID stasiun tujuan |
| date | String | Ya | Tanggal perjalanan (format: YYYY-MM-DD) |

**Headers:**
```
Accept: application/json
```

**Contoh Request:**
```
GET /api/schedules/search?origin=1&destination=4&date=2026-01-20
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "train_id": 8,
            "origin_station_id": 1,
            "destination_station_id": 4,
            "departure_time": "06:00:00",
            "arrival_time": "08:30:00",
            "duration": "2j 30m",
            "price": "75000.00",
            "available_days": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
            "is_active": true,
            "train": {
                "id": 8,
                "name": "Penataran",
                "train_number": "KA-008",
                "class": "ekonomi",
                "capacity": 600
            },
            "origin_station": {
                "id": 1,
                "name": "Stasiun Kota Baru Malang",
                "code": "ML",
                "city": "Malang"
            },
            "destination_station": {
                "id": 4,
                "name": "Stasiun Surabaya Gubeng",
                "code": "SGU",
                "city": "Surabaya"
            }
        },
        {
            "id": 2,
            "train_id": 8,
            "origin_station_id": 1,
            "destination_station_id": 4,
            "departure_time": "14:00:00",
            "arrival_time": "16:30:00",
            "duration": "2j 30m",
            "price": "75000.00",
            "available_days": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
            "is_active": true,
            "train": { ... },
            "origin_station": { ... },
            "destination_station": { ... }
        }
    ]
}
```

**Response Sukses - Tidak Ada Jadwal (200):**
```json
{
    "success": true,
    "data": []
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("schedules/search")
suspend fun searchSchedules(
    @Query("origin") origin: Int,
    @Query("destination") destination: Int,
    @Query("date") date: String
): Response<SchedulesResponse>
```

---

## 4. Promo Banner

### 4.1 Get All Banners

Mendapatkan daftar promo banner yang aktif.

**Endpoint:**
```
GET /api/banners
```

**Headers:**
```
Accept: application/json
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Promo Akhir Tahun",
            "description": "Diskon 20% untuk semua rute kereta eksekutif. Berlaku hingga 31 Desember 2024.",
            "image": null,
            "link": null,
            "discount_percentage": 20,
            "start_date": null,
            "end_date": null,
            "is_active": true,
            "order": 1,
            "created_at": "2026-01-17T10:00:00.000000Z",
            "updated_at": "2026-01-17T10:00:00.000000Z"
        },
        {
            "id": 2,
            "title": "Member Get Member",
            "description": "Ajak teman dan dapatkan cashback hingga Rp 100.000",
            "image": null,
            "link": null,
            "discount_percentage": null,
            "start_date": null,
            "end_date": null,
            "is_active": true,
            "order": 2,
            "created_at": "2026-01-17T10:00:00.000000Z",
            "updated_at": "2026-01-17T10:00:00.000000Z"
        }
    ]
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("banners")
suspend fun getBanners(): Response<BannersResponse>

data class BannersResponse(
    val success: Boolean,
    val data: List<PromoBanner>
)

data class PromoBanner(
    val id: Int,
    val title: String,
    val description: String?,
    val image: String?,
    val link: String?,
    val discount_percentage: Int?,
    val start_date: String?,
    val end_date: String?,
    val is_active: Boolean,
    val order: Int
)
```

---

## 5. Transaksi

### 5.1 Get User Transactions

Mendapatkan daftar transaksi user yang sedang login.

**Endpoint:**
```
GET /api/transactions
```

**Headers:**
```
Accept: application/json
Authorization: Bearer {token}
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "booking_code": "TRN20260117ABC123",
            "user_id": 2,
            "schedule_id": 1,
            "travel_date": "2026-01-20",
            "passenger_count": 2,
            "total_price": "150000.00",
            "status": "pending",
            "payment_method": "transfer",
            "paid_at": null,
            "created_at": "2026-01-17T10:30:00.000000Z",
            "updated_at": "2026-01-17T10:30:00.000000Z",
            "schedule": {
                "id": 1,
                "departure_time": "06:00:00",
                "arrival_time": "08:30:00",
                "duration": "2j 30m",
                "price": "75000.00",
                "train": {
                    "id": 8,
                    "name": "Penataran",
                    "train_number": "KA-008",
                    "class": "ekonomi"
                },
                "origin_station": {
                    "id": 1,
                    "name": "Stasiun Kota Baru Malang",
                    "code": "ML",
                    "city": "Malang"
                },
                "destination_station": {
                    "id": 4,
                    "name": "Stasiun Surabaya Gubeng",
                    "code": "SGU",
                    "city": "Surabaya"
                }
            }
        }
    ]
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("transactions")
suspend fun getTransactions(
    @Header("Authorization") token: String
): Response<TransactionsResponse>

data class TransactionsResponse(
    val success: Boolean,
    val data: List<Transaction>
)

data class Transaction(
    val id: Int,
    val booking_code: String,
    val user_id: Int,
    val schedule_id: Int,
    val travel_date: String,
    val passenger_count: Int,
    val total_price: String,
    val status: String,
    val payment_method: String?,
    val paid_at: String?,
    val created_at: String,
    val updated_at: String,
    val schedule: Schedule?
)
```

---

### 5.2 Create Transaction

Membuat transaksi/pemesanan tiket baru.

**Endpoint:**
```
POST /api/transactions
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "schedule_id": 1,
    "travel_date": "2026-01-20",
    "passenger_count": 2,
    "payment_method": "transfer"
}
```

**Validasi:**
| Field | Rules |
|-------|-------|
| schedule_id | Wajib, integer, harus ada di tabel schedules |
| travel_date | Wajib, format YYYY-MM-DD, minimal hari ini |
| passenger_count | Wajib, integer, min 1, max 10 |
| payment_method | Wajib, nilai: `transfer`, `ewallet`, atau `credit_card` |

**Response Sukses (201):**
```json
{
    "success": true,
    "message": "Transaction created successfully",
    "data": {
        "id": 1,
        "booking_code": "TRN20260117ABC123",
        "user_id": 2,
        "schedule_id": 1,
        "travel_date": "2026-01-20",
        "passenger_count": 2,
        "total_price": "150000.00",
        "status": "pending",
        "payment_method": "transfer",
        "paid_at": null,
        "created_at": "2026-01-17T10:30:00.000000Z",
        "updated_at": "2026-01-17T10:30:00.000000Z",
        "schedule": {
            "id": 1,
            "departure_time": "06:00:00",
            "arrival_time": "08:30:00",
            "duration": "2j 30m",
            "price": "75000.00",
            "train": { ... },
            "origin_station": { ... },
            "destination_station": { ... }
        }
    }
}
```

**Response Error - Validasi (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "schedule_id": ["The selected schedule id is invalid."],
        "travel_date": ["The travel date must be a date after or equal to today."],
        "passenger_count": ["The passenger count must be between 1 and 10."]
    }
}
```

**Contoh Android (Retrofit):**
```kotlin
@POST("transactions")
suspend fun createTransaction(
    @Header("Authorization") token: String,
    @Body request: CreateTransactionRequest
): Response<TransactionResponse>

data class CreateTransactionRequest(
    val schedule_id: Int,
    val travel_date: String,
    val passenger_count: Int,
    val payment_method: String
)
```

---

### 5.3 Get Transaction Detail

Mendapatkan detail transaksi berdasarkan ID.

**Endpoint:**
```
GET /api/transactions/{id}
```

**Parameters:**
| Nama | Tipe | Keterangan |
|------|------|------------|
| id | Integer | ID transaksi (path parameter) |

**Headers:**
```
Accept: application/json
Authorization: Bearer {token}
```

**Response Sukses (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "booking_code": "TRN20260117ABC123",
        "user_id": 2,
        "schedule_id": 1,
        "travel_date": "2026-01-20",
        "passenger_count": 2,
        "total_price": "150000.00",
        "status": "pending",
        "payment_method": "transfer",
        "paid_at": null,
        "created_at": "2026-01-17T10:30:00.000000Z",
        "updated_at": "2026-01-17T10:30:00.000000Z",
        "schedule": {
            "id": 1,
            "departure_time": "06:00:00",
            "arrival_time": "08:30:00",
            "duration": "2j 30m",
            "price": "75000.00",
            "train": {
                "id": 8,
                "name": "Penataran",
                "train_number": "KA-008",
                "class": "ekonomi",
                "capacity": 600
            },
            "origin_station": {
                "id": 1,
                "name": "Stasiun Kota Baru Malang",
                "code": "ML",
                "city": "Malang"
            },
            "destination_station": {
                "id": 4,
                "name": "Stasiun Surabaya Gubeng",
                "code": "SGU",
                "city": "Surabaya"
            }
        },
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "johndoe@example.com",
            "phone": "081234567890"
        }
    }
}
```

**Response Error - Not Found (404):**
```json
{
    "success": false,
    "message": "Transaction not found"
}
```

**Response Error - Forbidden (403):**
```json
{
    "success": false,
    "message": "You are not authorized to view this transaction"
}
```

**Contoh Android (Retrofit):**
```kotlin
@GET("transactions/{id}")
suspend fun getTransaction(
    @Header("Authorization") token: String,
    @Path("id") id: Int
): Response<TransactionDetailResponse>
```

---

### 5.4 Cancel Transaction

Membatalkan transaksi yang masih pending.

**Endpoint:**
```
POST /api/transactions/{id}/cancel
```

**Parameters:**
| Nama | Tipe | Keterangan |
|------|------|------------|
| id | Integer | ID transaksi (path parameter) |

**Headers:**
```
Accept: application/json
Authorization: Bearer {token}
```

**Request Body:** Tidak ada

**Response Sukses (200):**
```json
{
    "success": true,
    "message": "Transaction cancelled successfully",
    "data": {
        "id": 1,
        "booking_code": "TRN20260117ABC123",
        "user_id": 2,
        "schedule_id": 1,
        "travel_date": "2026-01-20",
        "passenger_count": 2,
        "total_price": "150000.00",
        "status": "cancelled",
        "payment_method": "transfer",
        "paid_at": null,
        "created_at": "2026-01-17T10:30:00.000000Z",
        "updated_at": "2026-01-17T11:00:00.000000Z"
    }
}
```

**Response Error - Already Cancelled (400):**
```json
{
    "success": false,
    "message": "Transaction is already cancelled"
}
```

**Response Error - Cannot Cancel (400):**
```json
{
    "success": false,
    "message": "Only pending transactions can be cancelled"
}
```

**Contoh Android (Retrofit):**
```kotlin
@POST("transactions/{id}/cancel")
suspend fun cancelTransaction(
    @Header("Authorization") token: String,
    @Path("id") id: Int
): Response<TransactionResponse>
```

---

## Integrasi Android

### Setup Retrofit

**build.gradle (app level):**
```gradle
dependencies {
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.11.0'
}
```

**ApiClient.kt:**
```kotlin
object ApiClient {
    private const val BASE_URL = "https://train-admin.rf.gd/api/"

    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }

    private val client = OkHttpClient.Builder()
        .addInterceptor(loggingInterceptor)
        .connectTimeout(30, TimeUnit.SECONDS)
        .readTimeout(30, TimeUnit.SECONDS)
        .build()

    val instance: ApiService by lazy {
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(ApiService::class.java)
    }
}
```

**ApiService.kt:**
```kotlin
interface ApiService {
    // Auth
    @POST("register")
    suspend fun register(@Body request: RegisterRequest): Response<AuthResponse>

    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<AuthResponse>

    @POST("logout")
    suspend fun logout(@Header("Authorization") token: String): Response<BaseResponse>

    @GET("user")
    suspend fun getUser(@Header("Authorization") token: String): Response<UserResponse>

    @PUT("user/profile")
    suspend fun updateProfile(
        @Header("Authorization") token: String,
        @Body request: UpdateProfileRequest
    ): Response<UserResponse>

    // Stations
    @GET("stations")
    suspend fun getStations(): Response<StationsResponse>

    @GET("stations/{id}")
    suspend fun getStation(@Path("id") id: Int): Response<StationDetailResponse>

    // Schedules
    @GET("schedules")
    suspend fun getSchedules(): Response<SchedulesResponse>

    @GET("schedules/{id}")
    suspend fun getSchedule(@Path("id") id: Int): Response<ScheduleDetailResponse>

    @GET("schedules/search")
    suspend fun searchSchedules(
        @Query("origin") origin: Int,
        @Query("destination") destination: Int,
        @Query("date") date: String
    ): Response<SchedulesResponse>

    // Banners
    @GET("banners")
    suspend fun getBanners(): Response<BannersResponse>

    // Transactions
    @GET("transactions")
    suspend fun getTransactions(
        @Header("Authorization") token: String
    ): Response<TransactionsResponse>

    @POST("transactions")
    suspend fun createTransaction(
        @Header("Authorization") token: String,
        @Body request: CreateTransactionRequest
    ): Response<TransactionResponse>

    @GET("transactions/{id}")
    suspend fun getTransaction(
        @Header("Authorization") token: String,
        @Path("id") id: Int
    ): Response<TransactionDetailResponse>

    @POST("transactions/{id}/cancel")
    suspend fun cancelTransaction(
        @Header("Authorization") token: String,
        @Path("id") id: Int
    ): Response<TransactionResponse>
}
```

### Menyimpan Token

**SharedPreferences Helper:**
```kotlin
object TokenManager {
    private const val PREF_NAME = "trainapp_prefs"
    private const val KEY_TOKEN = "auth_token"

    fun saveToken(context: Context, token: String) {
        val prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        prefs.edit().putString(KEY_TOKEN, token).apply()
    }

    fun getToken(context: Context): String? {
        val prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        return prefs.getString(KEY_TOKEN, null)
    }

    fun clearToken(context: Context) {
        val prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
        prefs.edit().remove(KEY_TOKEN).apply()
    }

    fun getBearerToken(context: Context): String {
        return "Bearer ${getToken(context)}"
    }
}
```

### Contoh Penggunaan di ViewModel

```kotlin
class MainViewModel : ViewModel() {

    fun login(email: String, password: String) {
        viewModelScope.launch {
            try {
                val response = ApiClient.instance.login(
                    LoginRequest(email, password)
                )

                if (response.isSuccessful && response.body()?.success == true) {
                    val token = response.body()?.data?.token
                    // Simpan token
                    TokenManager.saveToken(context, token!!)
                    // Navigate to home
                } else {
                    // Handle error
                    val errorMessage = response.body()?.message ?: "Login failed"
                }
            } catch (e: Exception) {
                // Handle network error
            }
        }
    }

    fun getSchedules() {
        viewModelScope.launch {
            try {
                val response = ApiClient.instance.getSchedules()

                if (response.isSuccessful && response.body()?.success == true) {
                    val schedules = response.body()?.data
                    // Update UI with schedules
                }
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
}
```

---

## Status Transaksi

| Status | Keterangan |
|--------|------------|
| `pending` | Menunggu pembayaran |
| `paid` | Sudah dibayar |
| `cancelled` | Dibatalkan |
| `completed` | Perjalanan selesai |

## Metode Pembayaran

| Kode | Keterangan |
|------|------------|
| `transfer` | Transfer Bank |
| `ewallet` | E-Wallet (GoPay, OVO, Dana, dll) |
| `credit_card` | Kartu Kredit |

## Kelas Kereta

| Kode | Keterangan |
|------|------------|
| `ekonomi` | Kelas Ekonomi |
| `bisnis` | Kelas Bisnis |
| `eksekutif` | Kelas Eksekutif |

---

## Catatan Penting

1. **Token Expiration**: Token tidak memiliki expiry time default. Logout akan menghapus token.

2. **Rate Limiting**: Tidak ada rate limiting saat ini, tapi disarankan untuk tidak melakukan request berlebihan.

3. **Error Handling**: Selalu handle semua kemungkinan error response dan network errors.

4. **HTTPS**: Selalu gunakan HTTPS untuk production.

5. **Token Storage**: Simpan token dengan aman, gunakan EncryptedSharedPreferences untuk keamanan lebih.

---



---

**Versi API:** 1.0
**Terakhir Diperbarui:** Januari 2026
