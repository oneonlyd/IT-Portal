/* ============================================================
   PORTAL FEATURES - Shared JavaScript
   Page Transitions, Dark Mode, Bilingual (i18n), Connection Indicator
   ============================================================ */

// ============================================================
// 1. BILINGUAL / i18n SYSTEM
// ============================================================
const I18N = {
    // === LOGIN PAGE ===
    'login.title': { id: 'IT PORTAL', en: 'IT PORTAL' },
    'login.tagline': { id: 'Satu Pintu Layanan Tim IT PT. Sandvik SMC', en: 'One-Stop Service for IT Team PT. Sandvik SMC' },
    'login.heading': { id: 'Masuk ke Portal', en: 'Sign In to Portal' },
    'login.subtitle': { id: 'Gunakan akun admin atau kredensial IT Anda', en: 'Use your admin account or IT credentials' },
    'login.username': { id: 'Username', en: 'Username' },
    'login.password': { id: 'Password', en: 'Password' },
    'login.forgot': { id: 'Lupa password?', en: 'Forgot password?' },
    'login.remember': { id: 'Ingat perangkat ini', en: 'Remember this device' },
    'login.submit': { id: 'Masuk Portal', en: 'Sign In' },
    'login.error': { id: 'Kredensial yang Anda masukkan salah.', en: 'The credentials you entered are incorrect.' },
    'login.copyright': { id: '© 2026 IT Internship - Devina Setya Maharani', en: '© 2026 IT Internship - Devina Setya Maharani' },

    // === DASHBOARD PAGE ===
    'dash.nav.dashboard': { id: 'Dashboard', en: 'Dashboard' },
    'dash.nav.admin': { id: 'Admin Panel (Kelola)', en: 'Admin Panel (Manage)' },
    'dash.loginAs': { id: 'Masuk Sebagai:', en: 'Logged In As:' },
    'dash.profileBtn': { id: 'Detail Profil', en: 'Profile Details' },
    'dash.settingsBtn': { id: 'Pengaturan', en: 'Settings' },
    'dash.logoutBtn': { id: 'Log out', en: 'Log out' },
    'dash.systemLabel': { id: 'Sistem Portal Terintegrasi', en: 'Integrated Portal System' },
    'dash.appsTitle': { id: 'Daftar Aplikasi', en: 'Application List' },
    'dash.appsSubtitle': { id: 'Silakan pilih salah satu aplikasi internal di bawah ini untuk memulai sesi kerja Anda.', en: 'Please select one of the internal applications below to start your work session.' },
    'dash.searchPlaceholder': { id: 'Cari nama aplikasi...', en: 'Search application name...' },
    'dash.filterAll': { id: 'Semua', en: 'All' },
    'dash.filterWeb': { id: 'Web', en: 'Web' },
    'dash.filterDesktop': { id: 'Desktop', en: 'Desktop' },
    'dash.showing': { id: 'Menampilkan', en: 'Showing' },
    'dash.apps': { id: 'Aplikasi', en: 'Applications' },
    'dash.emptyTitle': { id: 'Aplikasi Tidak Ditemukan', en: 'No Applications Found' },
    'dash.emptySubtitle': { id: 'Gunakan kata kunci pencarian yang berbeda atau lakukan reset.', en: 'Use a different search keyword or reset.' },
    'dash.resetSearch': { id: 'Reset Pencarian', en: 'Reset Search' },
    'dash.copyright': { id: '© 2026 IT Internship - Devina Setya Maharani', en: '© 2026 IT Internship - Devina Setya Maharani' },
    'dash.openAutoFill': { id: 'Buka & Auto-Fill', en: 'Open & Auto-Fill' },
    'dash.openLink': { id: 'Buka Link', en: 'Open Link' },
    'dash.protected': { id: '•••••••• (Terproteksi)', en: '•••••••• (Protected)' },

    // === DASHBOARD MODALS ===
    'modal.profile.title': { id: 'Detail Profil', en: 'Profile Details' },
    'modal.profile.userId': { id: 'ID Pengguna', en: 'User ID' },
    'modal.profile.email': { id: 'Email Kantor', en: 'Office Email' },
    'modal.profile.close': { id: 'Tutup', en: 'Close' },
    'modal.settings.title': { id: 'Pengaturan Akun', en: 'Account Settings' },
    'modal.settings.fullname': { id: 'Nama Lengkap', en: 'Full Name' },
    'modal.settings.role': { id: 'Role/Jabatan', en: 'Role/Position' },
    'modal.settings.note': { id: 'Pengaturan disimpan secara lokal pada memori browser untuk simulasi frontend.', en: 'Settings are saved locally in browser memory for frontend simulation.' },
    'modal.settings.save': { id: 'Simpan', en: 'Save' },
    'modal.settings.cancel': { id: 'Batal', en: 'Cancel' },

    // === LAUNCH MODAL ===
    'modal.launch.sso': { id: 'SSO Kredensial Autofill', en: 'SSO Credential Autofill' },
    'modal.launch.targetUrl': { id: 'Target URL / Path:', en: 'Target URL / Path:' },
    'modal.launch.username': { id: 'Username Aplikasi:', en: 'Application Username:' },
    'modal.launch.password': { id: 'Password Aplikasi:', en: 'Application Password:' },
    'modal.launch.note': { id: 'Catatan Keamanan:', en: 'Security Note:' },
    'modal.launch.copy': { id: 'Salin', en: 'Copy' },
    'modal.launch.info': { id: 'Sistem mendeteksi tipe aplikasi. Kredensial di atas berhasil didekripsi di backend dan siap digunakan untuk login otomatis.', en: 'The system detects the application type. The credentials above have been decrypted by the backend and are ready for automatic login.' },
    'modal.launch.open': { id: 'Buka Sekarang (Tab Baru)', en: 'Open Now (New Tab)' },
    'modal.launch.back': { id: 'Kembali Ke Portal', en: 'Back to Portal' },

    // === ADMIN PAGE ===
    'admin.badge': { id: 'ADMIN HAK AKSES', en: 'ADMIN ACCESS' },
    'admin.badgeSub': { id: 'Panel Kontrol Portal', en: 'Portal Control Panel' },
    'admin.title': { id: 'Manajemen Aplikasi', en: 'Application Management' },
    'admin.subtitle': { id: 'Kelola daftar portal aplikasi yang terbit di dashboard, ubah nama, deskripsi, warna, sertakan path.', en: 'Manage the list of portal applications published on the dashboard, change names, descriptions, colors, and paths.' },
    'admin.addBtn': { id: 'Tambah Aplikasi', en: 'Add Application' },
    'admin.tableActive': { id: 'Daftar Aplikasi Aktif', en: 'Active Applications List' },
    'admin.searchTable': { id: 'Cari dalam tabel...', en: 'Search in table...' },
    'admin.colName': { id: 'Nama Aplikasi', en: 'Application Name' },
    'admin.colType': { id: 'Tipe Portal', en: 'Portal Type' },
    'admin.colUrl': { id: 'URL/Path Executable', en: 'URL/Path Executable' },
    'admin.colVisual': { id: 'Visual Banner', en: 'Visual Banner' },
    'admin.colAction': { id: 'Aksi Kerja', en: 'Actions' },
    'admin.footerNote': { id: '* Berhasil disinkronisasi ke memori runtime client secara optimal.', en: '* Successfully synchronized to client runtime memory optimally.' },
    'admin.backLink': { id: '← Kembali ke Beranda Portal Utama', en: '← Back to Main Portal Home' },

    // === ADMIN FORM MODAL ===
    'admin.form.addTitle': { id: 'Tambah Aplikasi Baru', en: 'Add New Application' },
    'admin.form.appName': { id: 'Nama Aplikasi', en: 'Application Name' },
    'admin.form.portalType': { id: 'Tipe Portal', en: 'Portal Type' },
    'admin.form.colorModel': { id: 'Model Warna Banner', en: 'Banner Color Model' },
    'admin.form.urlLabel': { id: 'URL Aplikasi (Web)', en: 'Application URL (Web)' },
    'admin.form.urlLabelDesktop': { id: 'Path Eksekusi File (Desktop OS Client)', en: 'File Execution Path (Desktop OS Client)' },
    'admin.form.username': { id: 'Username Portal', en: 'Portal Username' },
    'admin.form.password': { id: 'Password Portal', en: 'Portal Password' },
    'admin.form.importantNote': { id: 'Menambahkan atau mengedit aplikasi melalui interface ini akan langsung memperbarui data internal aplikasi browser Anda secara seketika.', en: 'Adding or editing applications through this interface will immediately update your browser\'s internal application data instantly.' },
    'admin.form.important': { id: 'Penting:', en: 'Important:' },
    'admin.form.save': { id: 'Simpan & Terbitkan', en: 'Save & Publish' },
    'admin.form.cancel': { id: 'Batal', en: 'Cancel' },
    'admin.form.noDesc': { id: 'Tanpa deskripsi', en: 'No description' },

    // === CONNECTION INDICATOR ===
    'connection.online': { id: 'Terhubung', en: 'Connected' },
    'connection.offline': { id: 'Terputus', en: 'Disconnected' },
};

/**
 * Get current language from localStorage (default: 'id')
 */
function getCurrentLang() {
    return localStorage.getItem('portalLang') || 'id';
}

/**
 * Translate a key to current language
 */
function t(key) {
    const lang = getCurrentLang();
    const entry = I18N[key];
    if (!entry) return key;
    return entry[lang] || entry['id'] || key;
}

/**
 * Apply translations to all elements with data-i18n and data-i18n-placeholder attributes
 */
function applyTranslations() {
    const lang = getCurrentLang();

    // Translate text content
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        const entry = I18N[key];
        if (entry) {
            el.textContent = entry[lang] || entry['id'];
        }
    });

    // Translate placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.getAttribute('data-i18n-placeholder');
        const entry = I18N[key];
        if (entry) {
            el.placeholder = entry[lang] || entry['id'];
        }
    });

    // Update language toggle button label
    const langLabel = document.getElementById('lang-label');
    if (langLabel) {
        langLabel.textContent = lang === 'id' ? 'EN' : 'ID';
    }

    // Update connection indicator text
    updateConnectionIndicator();
}

/**
 * Toggle between 'id' and 'en'
 */
function toggleLanguage() {
    const current = getCurrentLang();
    const next = current === 'id' ? 'en' : 'id';
    localStorage.setItem('portalLang', next);
    applyTranslations();
}


// ============================================================
// 2. DARK MODE SYSTEM
// ============================================================

/**
 * Check if dark mode is enabled
 */
function isDarkMode() {
    return localStorage.getItem('portalDarkMode') === 'true';
}

/**
 * Apply dark mode state to DOM
 */
function applyDarkMode() {
    const dark = isDarkMode();
    if (dark) {
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');
    }

    // Update toggle icon
    const darkIcon = document.getElementById('dark-mode-icon');
    if (darkIcon) {
        darkIcon.setAttribute('data-lucide', dark ? 'sun' : 'moon');
        // Re-render only this icon
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

/**
 * Toggle dark mode on/off
 */
function toggleDarkMode() {
    const current = isDarkMode();
    localStorage.setItem('portalDarkMode', (!current).toString());
    applyDarkMode();
}


// ============================================================
// 3. PAGE TRANSITION SYSTEM
// ============================================================

/**
 * Navigate to a URL with a smooth fade-out transition
 */
function navigateTo(url) {
    const overlay = document.getElementById('page-transition-overlay');
    if (overlay) {
        overlay.classList.remove('fade-out');
        overlay.classList.add('fade-in');
        setTimeout(() => {
            window.location.href = url;
        }, 300);
    } else {
        window.location.href = url;
    }
}

/**
 * Initialize page transitions:
 * - Fade-in on page load
 * - Intercept <a> link clicks for fade-out
 */
function initPageTransitions() {
    const overlay = document.getElementById('page-transition-overlay');
    if (!overlay) return;

    // Fade-in: remove overlay on load
    requestAnimationFrame(() => {
        overlay.classList.add('fade-out');
    });

    // Intercept internal link clicks
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');

        // Skip external links, anchors, javascript:, and # links
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('http') || href.startsWith('mailto:')) return;

        // Skip links with onclick handlers (they manage their own navigation)
        if (link.hasAttribute('onclick')) return;

        e.preventDefault();
        navigateTo(href);
    });
}


// ============================================================
// 4. CONNECTION STATUS INDICATOR
// ============================================================

/**
 * Create and inject the connection indicator element
 */
function createConnectionIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'connection-indicator';
    indicator.className = 'connection-indicator';
    indicator.innerHTML = `
        <span class="status-dot"></span>
        <span id="connection-text"></span>
    `;
    document.body.appendChild(indicator);
}

/**
 * Update indicator state based on navigator.onLine
 */
function updateConnectionIndicator() {
    const indicator = document.getElementById('connection-indicator');
    const online = navigator.onLine;

    // 1. Update global floating connection indicator if it exists (e.g. legacy support)
    if (indicator) {
        const textEl = document.getElementById('connection-text');
        if (online) {
            indicator.className = 'connection-indicator online';
            if (textEl) textEl.textContent = t('connection.online');
        } else {
            indicator.className = 'connection-indicator offline';
            if (textEl) textEl.textContent = t('connection.offline');
        }
    }

    // 2. Update card connection badges dynamically
    document.querySelectorAll('.connection-card-badge').forEach(badge => {
        const dot = badge.querySelector('.status-dot');
        const textSpan = badge.querySelector('.status-text');
        
        if (online) {
            badge.className = 'connection-card-badge bg-green-500/25 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1.5 z-10';
            if (dot) {
                dot.className = 'status-dot w-1.5 h-1.5 rounded-full bg-green-400';
            }
            if (textSpan) {
                textSpan.textContent = t('connection.online');
            }
        } else {
            badge.className = 'connection-card-badge bg-red-500/25 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1.5 z-10';
            if (dot) {
                dot.className = 'status-dot w-1.5 h-1.5 rounded-full bg-red-400';
            }
            if (textSpan) {
                textSpan.textContent = t('connection.offline');
            }
        }
    });
}

/**
 * Initialize connection listeners
 */
function initConnectionIndicator() {
    // createConnectionIndicator(); // Commented out to prevent injecting global bottom-right indicator
    updateConnectionIndicator();

    window.addEventListener('online', updateConnectionIndicator);
    window.addEventListener('offline', updateConnectionIndicator);
}


// ============================================================
// 5. INITIALIZATION (runs on every page)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    // Apply dark mode first (before render to avoid flash)
    applyDarkMode();

    // Initialize page transitions
    initPageTransitions();

    // Initialize connection indicator
    if (!document.body.hasAttribute('data-no-connection-indicator')) {
        initConnectionIndicator();
    }

    // Apply translations
    applyTranslations();
});

// Apply dark mode immediately (before DOMContentLoaded) to prevent flash
(function() {
    if (localStorage.getItem('portalDarkMode') === 'true') {
        document.documentElement.classList.add('dark');
        // Also add to body as soon as it exists
        const observer = new MutationObserver(() => {
            if (document.body) {
                document.body.classList.add('dark');
                observer.disconnect();
            }
        });
        observer.observe(document.documentElement, { childList: true });
    }
})();
