// Main app configuration
const API_BASE = 'http://localhost:8000/api';

// Utility function to check if user is authenticated
function isAuthenticated() {
    return localStorage.getItem('user_id') !== null;
}

// Get auth token
function getAuthToken() {
    return localStorage.getItem('auth_token');
}

// Set auth token
function setAuthToken(token) {
    localStorage.setItem('auth_token', token);
}

// Clear auth data
function clearAuthData() {
    localStorage.removeItem('user_id');
    localStorage.removeItem('auth_token');
}

// API request helper
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE}${endpoint}`;
    const headers = {
        'Content-Type': 'application/json',
        ...options.headers
    };

    if (isAuthenticated()) {
        headers['Authorization'] = `Bearer ${getAuthToken()}`;
    }

    const response = await fetch(url, {
        ...options,
        headers,
        credentials: 'include'
    });

    if (!response.ok) {
        if (response.status === 401) {
            // Redirect to login if unauthorized
            clearAuthData();
            window.location.href = '/frontend/pages/login.html';
        }
        throw new Error(`API error: ${response.status}`);
    }

    return response.json();
}

// Redirect to dashboard if already logged in
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname;
    
    if (isAuthenticated() && (currentPage.includes('login.html') || currentPage.includes('register.html'))) {
        window.location.href = '/frontend/pages/dashboard.html';
    }
});
