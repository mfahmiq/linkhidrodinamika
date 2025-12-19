# Hidrodinamika Link Profile

Website Linktree-style untuk Hidrodinamika dengan sistem autentikasi.

## Features

- 🌓 Dark/Light Mode
- 🔒 Admin Authentication System
- 📱 Fully Responsive
- ⚡ Fast & Lightweight
- 🎨 Modern UI/UX

## Tech Stack

- HTML5
- CSS3 (Modern CSS Variables)
- Vanilla JavaScript
- localStorage for data persistence

## Default Admin Credentials

```
Username: admin
Password: admin123
```

> **Note**: Change these credentials in `login.js` for production use.

## Local Development

1. Clone this repository
2. Open `index.html` in your browser
3. Click "Admin Panel" to access the admin dashboard

## Deployment

This site is ready to deploy to Vercel, Netlify, or any static hosting service.

### Deploy to Vercel

[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=YOUR_REPO_URL)

Or manually:
```bash
npm i -g vercel
vercel
```

## File Structure

```
├── index.html          # Public page
├── login.html          # Admin login
├── admin.html          # Admin panel
├── styles.css          # All styles
├── app.js             # Public page logic
├── login.js           # Authentication logic
├── admin.js           # Admin panel logic
├── logo-hdi.png       # Logo
└── vercel.json        # Vercel config
```

## License

© 2025 Hidrodinamika. All rights reserved.
