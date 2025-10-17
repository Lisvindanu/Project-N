# 📋 ProjectN - Dokumentasi Singkat

## 🎯 Deskripsi Aplikasi
**ProjectN** adalah aplikasi *Netflix wannabe* berbasis **Laravel 12** dengan autentikasi manual dan **Bootstrap**.  
User dapat menonton video dari **VidKing**, membuat playlist, berkomentar, dan admin dapat mengelola konten.

---

## 📊 Database Schema (ERD)

### Tabel Utama:
1. **users** - Menyimpan data user dengan role (admin/user)  
2. **videos** - Menyimpan video dengan TMDB ID  
3. **categories** - Kategori video (Action, Drama, dll)  
4. **playlists** - Playlist pribadi user  
5. **playlist_video** - Pivot table many-to-many  
6. **comments** - Komentar user pada video  
7. **watch_histories** - Tracking progres nonton user  

---

## 🏗️ Struktur Class & File Penting

### 📁 Models (`app/Models/`)
- **User.php** - Relasi: playlists, comments, watchHistories  
- **Video.php** - Relasi: category, playlists, comments  
- **Category.php** - Relasi: videos  
- **Playlist.php** - Relasi: user & videos (many-to-many)  
- **Comment.php** - Relasi: user & video  
- **WatchHistory.php** - Relasi: user & video  

### 📁 Controllers (`app/Http/Controllers/`)

#### Auth & Profile
- `AuthController.php` - Login, Register, Logout  
- `ProfileController.php` - Edit profile & upload foto  

#### User Features
- `HomeController.php` - Homepage & video detail  
- `PlaylistController.php` - CRUD Playlist  
- `CommentController.php` - CRUD Comment  
- `WatchHistoryController.php` - Save watch progress  

#### Admin Features (`Admin/`)
- `DashboardController.php` - Admin dashboard dengan statistik  
- `CategoryController.php` - CRUD Categories  
- `VideoController.php` - CRUD Videos (fetch dari TMDB API)  
- `UserController.php` - CRUD Users (edit role, delete)  

### 📁 Middleware
- `AdminMiddleware.php` - Cek role admin  

### 📁 Policies (`app/Policies/`)
- `PlaylistPolicy.php` - Authorization playlist (hanya owner)  
- `CommentPolicy.php` - Authorization comment (hanya owner)  

---

## 🔄 CRUD Operations

### 1️⃣ Users (Admin)
| Action | Route | Controller Method |
|--------|--------|------------------|
| Read | `/admin/users` | `UserController@index` |
| Update | `/admin/users/{id}/edit` | `UserController@update` |
| Delete | `/admin/users/{id}` | `UserController@destroy` |

### 2️⃣ Videos (Admin)
| Action | Route | Controller Method |
|--------|--------|------------------|
| Create | `/admin/videos/create` | `VideoController@store` |
| Read | `/admin/videos` | `VideoController@index` |
| Update | `/admin/videos/{id}/edit` | `VideoController@update` |
| Delete | `/admin/videos/{id}` | `VideoController@destroy` |

> **Special:** Fetch metadata dari TMDB API saat create  

### 3️⃣ Categories (Admin)
| Action | Route | Controller Method |
|--------|--------|------------------|
| Create | `/admin/categories/create` | `CategoryController@store` |
| Read | `/admin/categories` | `CategoryController@index` |
| Update | `/admin/categories/{id}/edit` | `CategoryController@update` |
| Delete | `/admin/categories/{id}` | `CategoryController@destroy` |

### 4️⃣ Playlists (User)
| Action | Route | Controller Method |
|--------|--------|------------------|
| Create | `/playlists/create` | `PlaylistController@store` |
| Read | `/playlists` | `PlaylistController@index` |
| Update | `/playlists/{id}/edit` | `PlaylistController@update` |
| Delete | `/playlists/{id}` | `PlaylistController@destroy` |
| Add Video | `/playlists/{id}/add-video` | `PlaylistController@addVideo` |
| Remove Video | `/playlists/{id}/remove-video/{video}` | `PlaylistController@removeVideo` |

### 5️⃣ Comments (User)
| Action | Route | Controller Method |
|--------|--------|------------------|
| Create | `/videos/{id}/comments` | `CommentController@store` |
| Read | Otomatis di halaman video | - |
| Update | `/comments/{id}` | `CommentController@update` |
| Delete | `/comments/{id}` | `CommentController@destroy` |

### 6️⃣ Watch Histor
