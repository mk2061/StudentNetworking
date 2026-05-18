/**
 * InventoryPro Theme Manager
 * Centralized theme management for consistent theming across all pages
 */

class ThemeManager {
    constructor() {
        this.themes = {
            light: {
                name: 'Light Mode',
                icon: 'bi-sun',
                description: 'Clean and bright interface',
                cssVars: {
                    '--bs-body-bg': '#f8f9fa',
                    '--bs-body-color': '#212529',
                    '--bs-card-bg': '#ffffff',
                    '--bs-card-border-color': '#e3e6f0',
                    '--bs-primary': '#4e73df',
                    '--bs-secondary': '#858796',
                    '--bs-success': '#1cc88a',
                    '--bs-info': '#36b9cc',
                    '--bs-warning': '#f6c23e',
                    '--bs-danger': '#e74a3b',
                    '--bs-link-color': '#4e73df',
                    '--bs-primary-rgb': '78, 115, 223',
                    '--bs-border-color': '#e3e6f0'
                },
                isDark: false
            },
            dark: {
                name: 'Dark Mode',
                icon: 'bi-moon',
                description: 'Easy on the eyes in low light',
                cssVars: {
                    '--bs-body-bg': '#121212',
                    '--bs-body-color': '#e0e0e0',
                    '--bs-card-bg': '#1e1e1e',
                    '--bs-card-border-color': '#2d3748',
                    '--bs-primary': '#6366f1',
                    '--bs-secondary': '#94a3b8',
                    '--bs-success': '#10b981',
                    '--bs-info': '#06b6d4',
                    '--bs-warning': '#f59e0b',
                    '--bs-danger': '#ef4444',
                    '--bs-link-color': '#6366f1',
                    '--bs-primary-rgb': '99, 102, 241',
                    '--bs-border-color': '#2d3748'
                },
                isDark: true
            },
            auto: {
                name: 'Auto (System)',
                icon: 'bi-circle-half',
                description: 'Follows your system preference',
                cssVars: {},
                isDark: null // Will be determined by system
            },
            ocean: {
                name: 'Ocean Blue',
                icon: 'bi-droplet',
                description: 'Cool tones of blue',
                cssVars: {
                    '--bs-body-bg': '#f0f8ff',
                    '--bs-body-color': '#0a2a45',
                    '--bs-card-bg': '#ffffff',
                    '--bs-card-border-color': '#c5d9f2',
                    '--bs-primary': '#0077be',
                    '--bs-secondary': '#5d8aa8',
                    '--bs-success': '#2e8b57',
                    '--bs-info': '#1e90ff',
                    '--bs-warning': '#ffa500',
                    '--bs-danger': '#dc143c',
                    '--bs-link-color': '#0077be',
                    '--bs-primary-rgb': '0, 119, 190',
                    '--bs-border-color': '#c5d9f2'
                },
                isDark: false
            },
            royal: {
                name: 'Royal Purple',
                icon: 'bi-palette',
                description: 'Elegant purple accents',
                cssVars: {
                    '--bs-body-bg': '#1a1a2e',
                    '--bs-body-color': '#e6e6fa',
                    '--bs-card-bg': '#16213e',
                    '--bs-card-border-color': '#0f3460',
                    '--bs-primary': '#8a2be2',
                    '--bs-secondary': '#9370db',
                    '--bs-success': '#32cd32',
                    '--bs-info': '#00bfff',
                    '--bs-warning': '#ffd700',
                    '--bs-danger': '#ff1493',
                    '--bs-link-color': '#8a2be2',
                    '--bs-primary-rgb': '138, 43, 226',
                    '--bs-border-color': '#0f3460'
                },
                isDark: true
            },
            forest: {
                name: 'Forest Green',
                icon: 'bi-tree-fill',
                description: 'Nature-inspired design',
                cssVars: {
                    '--bs-body-bg': '#f0fff4',
                    '--bs-body-color': '#22543d',
                    '--bs-card-bg': '#ffffff',
                    '--bs-card-border-color': '#c6f6d5',
                    '--bs-primary': '#2d8b4e',
                    '--bs-secondary': '#68d391',
                    '--bs-success': '#38a169',
                    '--bs-info': '#4fd1c7',
                    '--bs-warning': '#d69e2e',
                    '--bs-danger': '#e53e3e',
                    '--bs-link-color': '#2d8b4e',
                    '--bs-primary-rgb': '45, 139, 78',
                    '--bs-border-color': '#c6f6d5'
                },
                isDark: false
            },
            amber: {
                name: 'Amber Glow',
                icon: 'bi-lightning-charge-fill',
                description: 'Warm amber tones',
                cssVars: {
                    '--bs-body-bg': '#fffaf0',
                    '--bs-body-color': '#7c2d12',
                    '--bs-card-bg': '#ffffff',
                    '--bs-card-border-color': '#fed7aa',
                    '--bs-primary': '#d97706',
                    '--bs-secondary': '#fbbf24',
                    '--bs-success': '#059669',
                    '--bs-info': '#0ea5e9',
                    '--bs-warning': '#f59e0b',
                    '--bs-danger': '#dc2626',
                    '--bs-link-color': '#d97706',
                    '--bs-primary-rgb': '217, 119, 6',
                    '--bs-border-color': '#fed7aa'
                },
                isDark: false
            },
            contrast: {
                name: 'High Contrast',
                icon: 'bi-eye',
                description: 'Enhanced visibility',
                cssVars: {
                    '--bs-body-bg': '#000000',
                    '--bs-body-color': '#ffffff',
                    '--bs-card-bg': '#000000',
                    '--bs-card-border-color': '#ffffff',
                    '--bs-primary': '#00ff00',
                    '--bs-secondary': '#ff00ff',
                    '--bs-success': '#00ffff',
                    '--bs-info': '#ffff00',
                    '--bs-warning': '#ff8000',
                    '--bs-danger': '#ff0000',
                    '--bs-link-color': '#00ffff',
                    '--bs-primary-rgb': '0, 255, 0',
                    '--bs-border-color': '#ffffff',
                    '--bs-border-width': '2px'
                },
                isDark: true
            }
        };
        
        this.init();
    }

    init() {
        // Load saved theme or default to light
        this.currentTheme = this.getSavedTheme();
        
        // Apply theme immediately
        this.applyTheme(this.currentTheme);
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Initialize theme toggle UI if available
        this.initThemeUI();
    }

    getSavedTheme() {
        // Try to get from localStorage
        const saved = localStorage.getItem('inventorypro_theme');
        if (saved && this.themes[saved]) {
            return saved;
        }
        
        // Try to get from cookie
        const cookieTheme = this.getCookie('inventorypro_theme');
        if (cookieTheme && this.themes[cookieTheme]) {
            return cookieTheme;
        }
        
        // Default to light
        return 'light';
    }

    getCookie(name) {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [cookieName, cookieValue] = cookie.trim().split('=');
            if (cookieName === name) {
                return decodeURIComponent(cookieValue);
            }
        }
        return null;
    }

    applyTheme(themeName) {
        const theme = this.themes[themeName];
        if (!theme) {
            console.error(`Theme "${themeName}" not found`);
            return;
        }

        const root = document.documentElement;
        
        // Set Bootstrap theme attribute
        if (themeName === 'auto') {
            // Auto mode - use system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                root.setAttribute('data-bs-theme', 'dark');
            } else {
                root.removeAttribute('data-bs-theme');
            }
        } else if (theme.isDark) {
            root.setAttribute('data-bs-theme', 'dark');
        } else {
            root.removeAttribute('data-bs-theme');
        }

        // Remove all previous theme CSS variables
        Object.keys(this.themes).forEach(t => {
            if (this.themes[t].cssVars) {
                Object.keys(this.themes[t].cssVars).forEach(varName => {
                    root.style.removeProperty(varName);
                });
            }
        });

        // Apply new theme CSS variables
        if (theme.cssVars && Object.keys(theme.cssVars).length > 0) {
            Object.entries(theme.cssVars).forEach(([varName, value]) => {
                root.style.setProperty(varName, value);
            });
        }

        // Save theme preference
        this.currentTheme = themeName;
        localStorage.setItem('inventorypro_theme', themeName);
        this.setCookie('inventorypro_theme', themeName, 365);
        
        // Update UI if available
        this.updateThemeUI(themeName);
        
        // Dispatch theme change event
        this.dispatchThemeChangeEvent(themeName);
    }

    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/;SameSite=Lax";
    }

    setupEventListeners() {
        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (this.currentTheme === 'auto') {
                    this.applyTheme('auto');
                }
            });
        }

        // Listen for theme changes from other tabs
        window.addEventListener('storage', (e) => {
            if (e.key === 'inventorypro_theme' && e.newValue && this.themes[e.newValue]) {
                this.applyTheme(e.newValue);
            }
        });
    }

    initThemeUI() {
        // Find and initialize theme option elements
        document.querySelectorAll('[data-theme]').forEach(element => {
            element.addEventListener('click', (e) => {
                const themeName = element.getAttribute('data-theme');
                if (this.themes[themeName]) {
                    this.applyTheme(themeName);
                }
            });
        });
    }

    updateThemeUI(activeTheme) {
        // Update all theme option elements
        document.querySelectorAll('[data-theme]').forEach(element => {
            const themeName = element.getAttribute('data-theme');
            if (themeName === activeTheme) {
                element.classList.add('active');
                element.classList.add('border-primary');
                element.classList.add('border-2');
                
                // Update badge if exists
                const badge = element.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge bg-primary';
                    badge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Active';
                }
            } else {
                element.classList.remove('active', 'border-primary', 'border-2');
                
                // Reset badge if exists
                const badge = element.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge bg-outline-primary';
                    badge.textContent = 'Select';
                }
            }
        });
    }

    dispatchThemeChangeEvent(themeName) {
        const event = new CustomEvent('themeChanged', {
            detail: {
                theme: themeName,
                themeData: this.themes[themeName]
            }
        });
        window.dispatchEvent(event);
    }

    getCurrentTheme() {
        return this.currentTheme;
    }

    getThemeData(themeName) {
        return this.themes[themeName] || null;
    }

    getAllThemes() {
        return this.themes;
    }

    // Utility method to check if current theme is dark
    isDarkMode() {
        if (this.currentTheme === 'auto') {
            return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        return this.themes[this.currentTheme]?.isDark || false;
    }
}

// Initialize global theme manager
window.ThemeManager = new ThemeManager();

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeManager;
}