# Deployment Guide - Vercel

Panduan lengkap untuk deploy website Hidrodinamika Link Profile ke Vercel.

## 📋 Prerequisites

- Akun GitHub (gratis)
- Akun Vercel (gratis)
- Git terinstall di komputer

## 🚀 Cara Deploy ke Vercel

### Metode 1: Deploy via GitHub (Recommended)

#### Step 1: Upload ke GitHub

1. **Buat Repository Baru di GitHub**
   - Buka https://github.com/new
   - Nama repository: `hidrodinamika-link-profile`
   - Set ke Public atau Private
   - Jangan centang "Initialize with README"
   - Klik "Create repository"

2. **Upload Files ke GitHub**
   
   Buka terminal/command prompt di folder project, lalu jalankan:
   
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/USERNAME/hidrodinamika-link-profile.git
   git push -u origin main
   ```
   
   Ganti `USERNAME` dengan username GitHub Anda.

#### Step 2: Deploy ke Vercel

1. **Login ke Vercel**
   - Buka https://vercel.com
   - Klik "Sign Up" atau "Login"
   - Login dengan akun GitHub Anda

2. **Import Project**
   - Klik "Add New..." → "Project"
   - Pilih repository `hidrodinamika-link-profile`
   - Klik "Import"

3. **Configure Project**
   - **Project Name**: `hidrodinamika-link-profile` (atau nama lain)
   - **Framework Preset**: Other (atau biarkan default)
   - **Root Directory**: `./` (default)
   - **Build Command**: (kosongkan)
   - **Output Directory**: (kosongkan)
   - Klik "Deploy"

4. **Tunggu Deployment**
   - Vercel akan otomatis build dan deploy
   - Proses biasanya 30-60 detik
   - Setelah selesai, Anda akan dapat URL seperti:
     `https://hidrodinamika-link-profile.vercel.app`

### Metode 2: Deploy via Vercel CLI

1. **Install Vercel CLI**
   ```bash
   npm install -g vercel
   ```

2. **Login ke Vercel**
   ```bash
   vercel login
   ```

3. **Deploy**
   ```bash
   cd "d:\Hidrodinamika Link Profile"
   vercel
   ```

4. **Ikuti Prompts**
   - Set up and deploy? `Y`
   - Which scope? (pilih account Anda)
   - Link to existing project? `N`
   - What's your project's name? `hidrodinamika-link-profile`
   - In which directory is your code located? `./`
   - Want to override the settings? `N`

5. **Production Deployment**
   ```bash
   vercel --prod
   ```

### Metode 3: Drag & Drop (Paling Mudah)

1. **Zip Project Files**
   - Zip semua file dalam folder project
   - Pastikan semua file (.html, .css, .js, .png) termasuk

2. **Upload ke Vercel**
   - Login ke https://vercel.com
   - Drag & drop file zip ke dashboard Vercel
   - Tunggu deployment selesai

## ⚙️ Konfigurasi Custom Domain (Optional)

Jika Anda punya domain sendiri:

1. **Buka Project di Vercel Dashboard**
2. **Klik "Settings" → "Domains"**
3. **Tambahkan domain Anda**
   - Contoh: `links.hidrodinamika.com`
4. **Update DNS Settings**
   - Tambahkan CNAME record di DNS provider Anda
   - Point ke `cname.vercel-dns.com`
5. **Tunggu DNS Propagation** (5-48 jam)

## 🔄 Auto-Deploy (Continuous Deployment)

Jika deploy via GitHub:

1. **Setiap kali push ke GitHub**, Vercel otomatis deploy
2. **Preview Deployments** untuk setiap branch
3. **Production Deployment** untuk main/master branch

Untuk update website:
```bash
git add .
git commit -m "Update content"
git push
```

Vercel akan otomatis deploy perubahan!

## 🔒 Environment Variables (Optional)

Untuk production, Anda bisa set environment variables:

1. **Buka Project Settings di Vercel**
2. **Klik "Environment Variables"**
3. **Tambahkan variables** (jika diperlukan di masa depan)

## 📊 Monitoring & Analytics

Vercel menyediakan:
- **Analytics**: Traffic dan performance metrics
- **Logs**: Real-time deployment logs
- **Speed Insights**: Performance monitoring

Akses via dashboard Vercel.

## 🛠️ Troubleshooting

### Issue: 404 Not Found
- **Solusi**: Pastikan `index.html` ada di root directory

### Issue: Images tidak muncul
- **Solusi**: Pastikan `logo-hdi.png` ter-upload dan path-nya benar

### Issue: Admin panel tidak bisa diakses
- **Solusi**: Pastikan semua file (.html, .js) ter-upload dengan benar

### Issue: localStorage tidak bekerja
- **Solusi**: localStorage bekerja normal di Vercel, pastikan browser tidak dalam mode incognito

## 📱 Testing Deployment

Setelah deploy, test:

1. ✅ Buka URL Vercel Anda
2. ✅ Test dark/light mode toggle
3. ✅ Klik semua link
4. ✅ Login ke admin panel (admin/admin123)
5. ✅ Edit content dan save
6. ✅ Logout
7. ✅ Test responsive (mobile/tablet)

## 🎉 Selesai!

Website Anda sekarang live di internet!

**URL Anda**: `https://[project-name].vercel.app`

Share URL ini untuk akses public page, dan gunakan `/login.html` untuk admin access.

---

## 📞 Support

Jika ada masalah:
- Vercel Docs: https://vercel.com/docs
- Vercel Support: https://vercel.com/support
- GitHub Issues: (buat issue di repository Anda)

## 🔐 Security Note

> **PENTING**: Untuk production, pertimbangkan:
> 1. Ganti default admin credentials
> 2. Implement backend authentication
> 3. Gunakan database untuk data persistence
> 4. Enable HTTPS (Vercel otomatis provide SSL)

---

**Happy Deploying! 🚀**
