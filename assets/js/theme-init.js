// Theme Management System - Global
class ThemeManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.loadTheme();
        this.setupThemeChangeListener();
        this.applyThemeToElements();
    }
    
    loadTheme() {
        const savedTheme = localStorage.getItem('inventorypro_theme') || 'light';
        this.applyTheme(savedTheme);
    }
    
    applyTheme(themeName) {
        const root = document.documentElement;
        
        // Set data-bs-theme attribute
        if (themeName === 'dark' || themeName === 'royal' || themeName === 'contrast') {
            root.setAttribute('data-bs-theme', 'dark');
        } else if (themeName === 'auto') {
            // Auto-detect
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                root.setAttribute('data-bs-theme', 'dark');
            } else {
                root.removeAttribute('data-bs-theme');
            }
        } else {
            root.removeAttribute('data-bs-theme');
        }
        
        // Apply theme-specific CSS variables
        const themeConfig = this.getThemeConfig(themeName);
        if (themeConfig) {
            Object.keys(themeConfig).forEach(key => {
                root.style.setProperty(key, themeConfig[key]);
            });
        }
        
        // Save to localStorage
        localStorage.setItem('inventorypro_theme', themeName);
        document.cookie = `inventorypro_theme=${themeName}; path=/; max-age=31536000; SameSite=Lax`;
        
        // Dispatch event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: themeName }));
    }
    
    getThemeConfig(themeName) {
        const configs = {
            light: {
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
                '--bs-link-color': '#4e73df'
            },
            dark: {
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
                '--bs-link-color': '#6366f1'
            },
            ocean: {
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
                '--bs-link-color': '#0077be'
            },
            // Add other theme configurations...
        };
        
        return configs[themeName] || null;
    }
    
    setupThemeChangeListener() {
        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                const currentTheme = localStorage.getItem('inventorypro_theme');
                if (currentTheme === 'auto') {
                    this.applyTheme('auto');
                }
            });
        }
        
        // Listen for theme change events from other pages
        window.addEventListener('themeChanged', (e) => {
            this.applyTheme(e.detail);
        });
    }
    
    applyThemeToElements() {
        // Apply theme-specific classes to elements
        document.querySelectorAll('[data-theme-class]').forEach(element => {
            const themeClass = element.getAttribute('data-theme-class');
            element.classList.add(themeClass);
        });
    }
}

// Initialize theme manager on page load
document.addEventListener('DOMContentLoaded', function() {
    window.themeManager = new ThemeManager();
});