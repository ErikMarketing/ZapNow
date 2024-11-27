# ⚡ Zap Now
> One-click WordPress core cache clearing made simple.

## Overview
Ultra Light Zap Cache Version. A simple WordPress plugin that adds a cache clearing button to your admin bar. Instantly clear WordPress internal caches, transients, and update caches with a single click. Accessible to authenticated administrators while browsing your site or working in the dashboard.

## 🚀 Features
* One-click clearing from admin bar
* Frontend + backend support
* WordPress core cache clearing
* Visual clearing feedback
* Admin-only access
* Transient cleanup
* Theme cache clearing
* Update cache refresh

## ⚡ Cache Types Cleared
* WordPress Object Cache
* All Transients
* Theme Customizer Cache
* Plugin Update Cache
* Theme Update Cache
* Menu Cache
* Rewrite Rules

## 📋 Requirements
* WordPress 5.0+
* PHP 7.2+
* Administrator privileges

## 💻 Installation
```bash
# Manual Installation
1. Download zap-now.zip
2. Upload to wp-content/plugins/
3. Activate in WordPress

# From GitHub
1. Download latest release
2. Upload via WordPress Plugins > Add New > Upload Plugin
3. Install Now
4. Activate

# From WordPress
1. Go to Plugins > Add New Plugin
2. Upload Plugin > Choose File "Zap Now"
3. Install Now
4. Activate
```

## 🔧 Usage
1. Look for "Zap Now!" in admin bar
2. Click to clear all caches
3. Confirm action
4. Wait for success message showing cleared cache types

## 🔒 Security Features

* Admin-only access with capability verification
* Multi-layer nonce verification (frontend and backend)
* Frontend security checks before AJAX calls
* Direct access prevention
* Enhanced error handling and user feedback
* Consistent nonce implementation using class constants

## 🛠️ Technical Details
* Cleans transients from database
* Refreshes theme customizer
* Forces update checks
* Flushes rewrite rules
* Clears navigation cache
* Minimal resource usage
* Comprehensive security checks
* No bloat

## 🤔 When to Use
* After updating themes
* After modifying menus
* When customizer changes do not appear
* To force plugin update checks
* When transients need cleanup
* After permalink structure changes

## 📦 Contributing
Pull requests welcome.

## 🔄 Changelog

### 1.0.1
* Added frontend nonce verification before AJAX calls
* Implemented NONCE_ACTION class constant for consistency
* Enhanced security with multiple verification layers
* Improved error handling
* Added security checks

### 1.0.0
* Initial release

## 📝 License
[GPL-3.0+](http://www.gnu.org/licenses/gpl-3.0.txt)

---
Made with ☕ by [ErikMarketing](https://erik.marketing)
