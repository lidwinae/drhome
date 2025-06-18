# DrHome

Platform Digital untuk Memudahkan Perencanaan dan Pembangunan Rumah. Temukan designer, contractor untuk membangun rumah Anda. Dr. Home: platform berbasis web yang menghubungkan klien dengan desainer dan kontraktor profesional untuk proyek desain dan konstruksi rumah, menawarkan fitur seperti pencarian desain siap pakai, pengajuan request custom, portofolio desainer/kontraktor, sistem chat real-time, pembelian desain, serta manajemen proyek terintegrasi dengan otentikasi berbasis session dan otorisasi role-based (klien, desainer, kontraktor, admin) untuk pengalaman yang aman dan terpersonalisasi.

# Installation Steps

1. Clone project
```bash
git clone https://github.com/aryayudh06/DrHome
```

2. Masuk ke direktori project
```bash
cd DrHome
```

3. Install composer
```bash
composer install
```

4. Install npm
```bash
npm install
```

5. Copy .env.example menjadi .env dan modifikasi isi .env
```bash
cp .env.example .env
```

6. Generate key untuk aplikasi
```bash
php artisan key:generate
```

7. Lakukan migrasi dengan seeder
```bash
php artisan migrate:fresh --seed
```

8. Membuat symbolic link dari public/storage ke storage/app/public
```bash
php artisan storage:link
```

9. Menjalankan Vite / Mix untuk kompilasi aset frontend
``` bash
npm run dev
```

10. Menjalankan server Laravel
```bash
php artisan serve
```

# Struktur Proyek / Folder

```bash
 🏠 DrHome
 ├── 📂 app
 │   ├── 📂 Http
 │   │   ├── 📂 Controllers
 │   │   │   ├── 📂 Api
 │   │   │   │   ├── 📄 ContractorController.php
 │   │   │   │   ├── 📄 DesignController.php
 │   │   │   │   ├── 📄 DesignerController.php
 │   │   │   │   └── 📄 MailsAdminController.php
 │   │   │   ├── 📂 Auth
 │   │   │   │   ├── 📄 AuthenticatedSessionController.php
 │   │   │   │   ├── 📄 ConfirmablePasswordController.php
 │   │   │   │   ├── 📄 EmailVerificationNotificationController.php
 │   │   │   │   ├── 📄 EmailVerificationPromptController.php
 │   │   │   │   ├── 📄 NewPasswordController.php
 │   │   │   │   ├── 📄 PasswordResetLinkController.php
 │   │   │   │   ├── 📄 RegisteredUserController.php
 │   │   │   │   └── 📄 VerifyEmailController.php
 │   │   │   ├── 📂 Settings
 │   │   │   │   ├── 📄 PasswordController.php
 │   │   │   │   └── 📄 ProfileController.php
 │   │   │   ├── 📄 AvatarController.php
 │   │   │   ├── 📄 ChatController.php
 │   │   │   ├── 📄 Controller.php
 │   │   │   ├── 📄 MyRequestController.php
 │   │   │   ├── 📄 PurchasedDesignController.php
 │   │   │   ├── 📄 RequestContractorController.php
 │   │   │   ├── 📄 RequestController.php
 │   │   │   ├── 📄 RequestDesignerController.php
 │   │   │   └── 📄 UserController.php
 │   │   ├── 📂 Middleware
 │   │   │   ├── 📄 ChatAccess.php
 │   │   │   ├── 📄 EnsureIsAdmin.php
 │   │   │   ├── 📄 EnsureIsDesignerOrContractor.php
 │   │   │   ├── 📄 HandleAppearance.php
 │   │   │   └── 📄 HandleInertiaRequests.php
 │   │   ├── 📂 Requests
 │   │   │   ├── 📂 Auth
 │   │   │   │   └── 📄 LoginRequest.php
 │   │   │   └── 📂 Settings
 │   │   │       └── 📄 ProfileUpdateRequest.php
 │   ├── 📂 Mail
 │   │   └── 📄 CustomEmail.php
 │   ├── 📂 Models
 │   │   ├── 📄 Chat.php
 │   │   ├── 📄 Contractor.php
 │   │   ├── 📄 Design.php
 │   │   ├── 📄 Designer.php
 │   │   ├── 📄 EmailHistory.php
 │   │   ├── 📄 MailsAdmin.php
 │   │   ├── 📄 PurchasedDesign.php
 │   │   ├── 📄 RequestContractor.php
 │   │   ├── 📄 RequestDesigner.php
 │   │   └── 📄 User.php
 │   ├── 📂 Providers
 │   │   └── 📄 AppServiceProvider.php
 ├── 📂 bootstrap
 │   ├── 📂 cache
 │   │   ├── 📄 .gitignore
 │   │   ├── 📄 packages.php
 │   │   └── 📄 services.php
 │   ├── 📄 app.php
 │   └── 📄 providers.php
 ├── 📂 config
 │   ├── 📄 app.php
 │   ├── 📄 auth.php
 │   ├── 📄 cache.php
 │   ├── 📄 database.php
 │   ├── 📄 filesystems.php
 │   ├── 📄 logging.php
 │   ├── 📄 mail.php
 │   ├── 📄 queue.php
 │   ├── 📄 services.php
 │   └── 📄 session.php
 ├── 📂 database
 │   ├── 📂 factories
 │   │   └── 📄 UserFactory.php
 │   ├── 📂 migrations
 │   │   ├── 📄 0001_01_01_000000_create_users_table.php
 │   │   ├── 📄 0001_01_01_000001_create_cache_table.php
 │   │   ├── 📄 0001_01_01_000002_create_jobs_table.php
 │   │   ├── 📄 2025_05_01_162027_create_designs_table.php
 │   │   ├── 📄 2025_05_05_115944_add_role_status_to_users_table.php
 │   │   ├── 📄 2025_05_05_124058_create_mails_admin_table.php
 │   │   ├── 📄 2025_05_07_203728_create_riwayat_email_table.php
 │   │   ├── 📄 2025_05_16_122534_add_to_users_table.php
 │   │   ├── 📄 2025_05_16_162221_create_contractors_table.php
 │   │   ├── 📄 2025_05_16_183222_create_designers_table.php
 │   │   ├── 📄 2025_06_03_193741_create_purchased_designs_table.php
 │   │   ├── 📄 2025_06_04_143203_create_request_contractors_table.php
 │   │   ├── 📄 2025_06_04_204015_create_request_designers_table.php
 │   │   └── 📄 2025_06_05_123253_create_chats_table.php
 │   ├── 📂 seeders
 │   │   ├── 📄 DatabaseSeeder.php
 │   │   ├── 📄 DesignsTableSeeder.php
 │   │   └── 📄 UsersTableSeeder.php
 │   └── 📄 .gitignore
 ├── 📂 docker
 │   └── 📂 apache
 │       └── ⚙️ 000-default.conf
 ├── 📂 node_modules
 ├── 📂 public
 │   ├── 📂 background
 │   ├── 📂 build-assets
 │   ├── 📂 designers
 │   ├── 📂 designs
 │   ├── 📂 images
 │   ├── 📂 portfolio
 │   ├── 📂 storage
 │   ├── 📂 videos
 │   ├── ⚙️ .htaccess
 │   ├── ⭐ favicon.ico
 │   ├── 📄 hot
 │   ├── 📄 index.php
 │   └── 📄 robots.txt
 ├── 📂 resources
 │   ├── 📂 css
 │   │   └── #️⃣ app.css
 │   ├── 📂 js
 │   │   ├── 📂 components
 │   │   │   ├── 📂 landing
 │   │   │   │   ├── 🟩 LandingFooter.vue
 │   │   │   │   └── 🟩 LandingHeader.vue
 │   │   │   ├── 📂 ui
 │   │   │   │   ├── 📂 avatar
 │   │   │   │   ├── 📂 breadcrumb
 │   │   │   │   ├── 📂 button
 │   │   │   │   ├── 📂 card
 │   │   │   │   ├── 📂 checkbox
 │   │   │   │   ├── 📂 collapsible
 │   │   │   │   ├── 📂 dialog
 │   │   │   │   ├── 📂 dropdown-menu
 │   │   │   │   ├── 📂 input
 │   │   │   │   ├── 📂 label
 │   │   │   │   ├── 📂 navigation-menu
 │   │   │   │   ├── 📂 separator
 │   │   │   │   ├── 📂 sheet
 │   │   │   │   ├── 📂 sidebar
 │   │   │   │   ├── 📂 skeleton
 │   │   │   │   └── 📂 tooltip
 │   │   │   ├── 🟩 AppContent.vue
 │   │   │   ├── 🟩 AppearanceTabs.vue
 │   │   │   ├── 🟩 AppHeader.vue
 │   │   │   ├── 🟩 AppLogo.vue
 │   │   │   ├── 🟩 AppLogoIcon.vue
 │   │   │   ├── 🟩 AppShell.vue
 │   │   │   ├── 🟩 AppSidebar.vue
 │   │   │   ├── 🟩 AppSidebarHeader.vue
 │   │   │   ├── 🟩 Breadcrumbs.vue
 │   │   │   ├── 🟩 DeleteUser.vue
 │   │   │   ├── 🟩 Heading.vue
 │   │   │   ├── 🟩 HeadingSmall.vue
 │   │   │   ├── 🟩 Icon.vue
 │   │   │   ├── 🟩 InputError.vue
 │   │   │   ├── 🟩 NavFooter.vue
 │   │   │   ├── 🟩 NavMain.vue
 │   │   │   ├── 🟩 NavUser.vue
 │   │   │   ├── 🟩 PlaceholderPattern.vue
 │   │   │   ├── 🟩 TextLink.vue
 │   │   │   ├── 🟩 UserInfo.vue
 │   │   │   └── 🟩 UserMenuContent.vue
 │   │   ├── 📂 composables
 │   │   │   ├── 📄 useAppearance.ts
 │   │   │   └── 📄 useInitials.ts
 │   │   ├── 📂 layouts
 │   │   │   ├── 📂 app
 │   │   │   │   ├── 🟩 AppHeaderLayout.vue
 │   │   │   │   └── 🟩 AppSidebarLayout.vue
 │   │   │   ├── 📂 auth
 │   │   │   │   ├── 🟩 AuthCardLayout.vue
 │   │   │   │   ├── 🟩 AuthSimpleLayout.vue
 │   │   │   │   └── 🟩 AuthSplitLayout.vue
 │   │   │   ├── 📂 settings
 │   │   │   │   ├── 🟩 Layout.vue
 │   │   │   │   ├── 🟩 ApplyLayout.vue
 │   │   │   │   ├── 🟩 AuthLayout.vue
 │   │   │   │   └── 🟩 LandingLayout.vue
 │   │   ├── 📂 lib
 │   │   │   └── 📄 utils.ts
 │   │   ├── 📂 pages
 │   │   │   ├── 📂 admin
 │   │   │   │   ├── 🟩 AddContractor.vue
 │   │   │   │   ├── 🟩 AddDesigner.vue
 │   │   │   │   ├── 🟩 Admin.vue
 │   │   │   │   ├── 🟩 Ban.
 │   │   │   │   ├── 🟩 Mail.vue
 │   │   │   │   └── 🟩 New.vue
 │   │   │   ├── 📂 auth
 │   │   │   │   ├── 🟩 ConfirmPassword.vue
 │   │   │   │   ├── 🟩 ForgotPassword.vue
 │   │   │   │   ├── 🟩 Login.vue
 │   │   │   │   ├── 🟩 Register.vue
 │   │   │   │   ├── 🟩 ResetPassword.vue
 │   │   │   │   └── 🟩 VerifyEmail.vue
 │   │   │   ├── 📂 Build_group
 │   │   │   │   ├── 🟩 Build.vue
 │   │   │   │   ├── 🟩 DesignerDetail.vue
 │   │   │   │   └── 🟩 RequestDesigner.vue
 │   │   │   ├── 📂 contractor
 │   │   │   │   ├── 🟩 BuildContractor.vue
 │   │   │   │   ├── 🟩 ContractorDetail.vue
 │   │   │   │   └── 🟩 RequestContractor.vue
 │   │   │   ├── 📂 Design_group
 │   │   │   │   ├── 🟩 Design.vue
 │   │   │   │   └── 🟩 DesignDetail.vue
 │   │   │   ├── 📂 settings
 │   │   │   │   ├── 🟩 Appearance.vue
 │   │   │   │   ├── 🟩 Password.vue
 │   │   │   │   └── 🟩 Profile.vue
 │   │   │   ├── 🟩 Chat.vue
 │   │   │   ├── 🟩 CustomerService.vue
 │   │   │   ├── 🟩 Landing.vue
 │   │   │   ├── 🟩 MyRequest.vue
 │   │   │   ├── 🟩 MyRequestDetail.vue
 │   │   │   ├── 🟩 Profile.vue
 │   │   │   ├── 🟩 PurchasedDesigns.vue
 │   │   │   ├── 🟩 PurchaseDesign.vue
 │   │   │   ├── 🟩 Request.vue
 │   │   │   └── 🟩 RequestDetail.vue
 │   │   ├── 📂 types
 │   │   │   ├── 📄 globals.d.ts
 │   │   │   ├── 📄 index.d.ts
 │   │   │   └── 📄 ziggy.d.ts
 │   │   ├── 📄 app.ts
 │   │   └── 📄 ssr.ts
 │   ├── 📂 views
 │   │   └── 📄 app.blade.php
 ├── 📂 routes
 │   ├── 📄 admin.php
 │   ├── 📄 api.php
 │   ├── 📄 auth.php
 │   ├── 📄 console.php
 │   ├── 📄 settings.php
 │   └── 📄 web.php
 ├── 📂 storage
 │   ├── 📂 app
 │   ├── 📂 framework
 │   └── 📂 logs
 ├── 📂 tests
 │   ├── 📂 Feature
 │   ├── 📂 Unit
 │   └── 📄 TestCase.php
 ├── 📂 vendor
 ├── ⚙️ .editorconfig
 ├── ⚙️ .env
 ├── 💲 .env.example
 ├── 📄 .gitattributes
 ├── 📄 .gitignore
 ├── 📄 .prettierignore
 ├── 📄 .prettierrc
 ├── 📄 artisan
 ├── 📦 components.json
 ├── 📦 composer.json
 ├── 📦 composer.lock
 ├── 🐳 Dockerfile
 ├── 📄 eslint.config.js
 ├── 📦 package-lock.json
 ├── 📦 package.json
 ├── 📄 phpunit.xml
 ├── 📄 railway.toml
 ├── 📖 README.md
 ├── 📜 render.yaml
 ├── 📄 tsconfig.json
 ├── 📄 vercel.json
 └── 📄 vite.config.ts
```

## Landing Page Preview as Guest User

![Landing Page Preview](https://github.com/aryayudh06/DrHome/blob/main/public/images/previewlanding.png?raw=true)