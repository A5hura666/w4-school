/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";
// import './bootstrap.js';
import 'flowbite';

import './toast';

document.addEventListener('DOMContentLoaded', () => {
    function applyTheme(theme) {
        const body = document.body;
        if (theme === 'dark') {
            body.classList.add('dark');
        } else {
            body.classList.remove('dark');
        }
    }

    function toggleTheme() {
        const currentTheme = localStorage.getItem('theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    }

    function initTheme() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const defaultTheme = savedTheme ? savedTheme : (prefersDarkScheme ? 'dark' : 'light');
        applyTheme(defaultTheme);
    }

    const themeToggleButton = document.getElementById('theme-toggle');
    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', toggleTheme);
    }

    initTheme();
});