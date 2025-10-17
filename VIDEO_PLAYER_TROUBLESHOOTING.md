# 🎬 Video Player Troubleshooting Guide

## Problem: Video Player Loading Forever (Spinning/Stuck)

### Kemungkinan Penyebab:

1. **HTTPS Issue** - Server HTTP block iframe HTTPS
2. **CSP (Content Security Policy)** - Server block external iframe
3. **Firewall/Mod_Security** - Server block external requests
4. **Player Server Down** - External player sedang maintenance

---

## ✅ Solutions

### 1. Enable HTTPS on VPS
Video players seperti VidSrc dan Vidking butuh HTTPS untuk load properly.

**Install SSL dengan Certbot (Let's Encrypt - FREE):**
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install certbot python3-certbot-apache

# Generate SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renew
sudo certbot renew --dry-run
```

**Install SSL dengan Certbot (Nginx):**
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

---

### 2. Fix Apache Headers (Sudah di .htaccess)
File `.htaccess` sudah di-update dengan headers yang benar:
```apache
Header always set X-Frame-Options "SAMEORIGIN"
Header always set Content-Security-Policy "frame-src 'self' https://www.vidking.net https://vidsrc.me https://www.2embed.cc https://*.vidking.net https://*.vidsrc.me https://*.2embed.cc; frame-ancestors 'self'"
```

**Pastikan mod_headers enabled:**
```bash
sudo a2enmod headers
sudo systemctl restart apache2
```

---

### 3. Fix Nginx Configuration
Kalau pakai Nginx, tambahkan ini di server block:

```nginx
server {
    # ... existing config

    # Allow iframe from video players
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header Content-Security-Policy "frame-src 'self' https://www.vidking.net https://vidsrc.me https://www.2embed.cc https://autoembed.cc https://*.vidking.net https://*.vidsrc.me https://*.2embed.cc; frame-ancestors 'self'" always;

    # ... rest of config
}
```

Restart Nginx:
```bash
sudo systemctl restart nginx
```

---

### 4. Disable ModSecurity (Jika Terinstall)
ModSecurity kadang block iframe external:

```bash
# Temporary disable
sudo a2dismod security2
sudo systemctl restart apache2

# Or whitelist your domain in ModSecurity config
```

---

### 5. Check Firewall Rules
Pastikan outbound connections allowed:

```bash
# UFW
sudo ufw allow out 80/tcp
sudo ufw allow out 443/tcp

# iptables
sudo iptables -A OUTPUT -p tcp --dport 80 -j ACCEPT
sudo iptables -A OUTPUT -p tcp --dport 443 -j ACCEPT
```

---

### 6. Use Alternative Players
Aplikasi sudah support 4 players dengan fallback:

1. **Vidking** - Player original
2. **VidSrc** (Default) - Paling stable
3. **2Embed** - Backup player
4. **AutoEmbed** - Alternative player

**User bisa switch player dengan 1 klik** kalau satu player tidak work.

---

## Testing di VPS

### Test 1: Check HTTPS
```bash
curl -I https://yourdomain.com
```
Harus return `200 OK` dengan header SSL.

### Test 2: Check Headers
```bash
curl -I https://yourdomain.com/videos/1
```
Check apakah ada header:
- `X-Frame-Options: SAMEORIGIN`
- `Content-Security-Policy: frame-src ...`

### Test 3: Check Player URLs
Buka browser console (F12) di VPS dan check error:
- Mixed Content Error → Need HTTPS
- CSP Error → Need update Content-Security-Policy
- CORS Error → Need server config fix

---

## Quick Fix Checklist

- [ ] VPS menggunakan HTTPS (SSL installed)
- [ ] Apache mod_headers enabled
- [ ] .htaccess headers sudah correct
- [ ] ModSecurity disabled atau di-whitelist
- [ ] Firewall allow outbound 80/443
- [ ] Try switch ke player lain (VidSrc, 2Embed, AutoEmbed)
- [ ] Clear browser cache dan test ulang

---

## Still Not Working?

### Alternative Solution: Use Direct Video Links
Kalau semua player gagal, bisa ganti ke embed player yang self-hosted atau pakai direct video links dari CDN sendiri.

Contact hosting provider untuk:
1. Enable mod_headers
2. Disable ModSecurity rules yang block iframe
3. Enable HTTPS
4. Check firewall outbound rules

---

## Player Comparison

| Player | Stability | Speed | HTTPS Required | Quality |
|--------|-----------|-------|----------------|---------|
| VidSrc | ⭐⭐⭐⭐⭐ | Fast | Yes | 1080p |
| Vidking | ⭐⭐⭐⭐ | Medium | Yes | 720p-1080p |
| 2Embed | ⭐⭐⭐⭐ | Fast | Yes | 720p |
| AutoEmbed | ⭐⭐⭐ | Medium | Yes | 720p |

**Recommendation**: VidSrc (Default) karena paling stable dan cepat.

---

## Contact Support

Jika masih ada masalah setelah mengikuti guide ini:
1. Screenshot error di browser console (F12)
2. Screenshot `curl -I https://yourdomain.com/videos/1`
3. Info VPS: OS, Web Server (Apache/Nginx), PHP version
4. Test result dari masing-masing player
