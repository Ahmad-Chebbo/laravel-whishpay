## 📥 Installation

Install via Composer:

```bash
composer require ahmad-chebbo/laravel-whishpay
```

---

## ⚙️ Publish Configuration

```bash
php artisan whish:install
```

Or manually:

```bash
php artisan vendor:publish --tag=whish-config
```

---

## 🔐 Environment Variables

Add the following to your `.env`:

```env
WHISH_SANDBOX_URL=https://lb.sandbox.whish.money/itel-service/api
WHISH_MODE=sandbox
WHISH_CHANNEL=your_channel
WHISH_SECRET=your_secret
WHISH_WEBSITE_URL=https://yourwebsite.com

# Optional
WHISH_FAKE=true
```

### ✅ Production URL

```env
WHISH_PRODUCTION_URL=https://whish.money/itel-service/api
```

---
