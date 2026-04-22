/**
 * Bootstrap file for initializing global configurations.
 * Add any global setup code here.
 */

// Set up CSRF token for API requests
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.csrfToken = token.getAttribute('content');
}
