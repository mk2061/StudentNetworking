// assets/js/theme.js
(() => {
    const THEMES = ['light','dark','auto','blue','purple','green','amber','high_contrast'];
    const DEFAULT = 'light';

    // Apply theme
    function applyTheme(theme) {
        if (!THEMES.includes(theme)) theme = DEFAULT;
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('app-theme', theme);
        // Sync cookie for PHP
        document.cookie = `app_theme=${theme}; path=/; max-age=31536000; SameSite=Lax`;
    }

    // Load from localStorage → fallback to system → default
    function loadTheme() {
        let theme = localStorage.getItem('app-theme');
        if (!theme || !THEMES.includes(theme)) {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        applyTheme(theme);
    }

    // Public API
    window.setAppTheme = applyTheme;

    // Init on load
    document.addEventListener('DOMContentLoaded', loadTheme);

    // Listen for system changes (auto mode)
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (localStorage.getItem('app-theme') === 'auto') {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
})();