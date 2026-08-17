// Authentication functions
const AuthService = {
    async login(email, password) {
        try {
            const response = await apiRequest('/auth/login', {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });
            if (response.success) {
                localStorage.setItem('user_id', response.user_id);
                setAuthToken(response.token);
                window.location.href = '/frontend/pages/dashboard.html';
            }
            return response;
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
    },

    async register(name, email, password) {
        try {
            const response = await apiRequest('/auth/register', {
                method: 'POST',
                body: JSON.stringify({ name, email, password })
            });
            if (response.success) {
                localStorage.setItem('user_id', response.user_id);
                setAuthToken(response.token);
                window.location.href = '/frontend/pages/dashboard.html';
            }
            return response;
        } catch (error) {
            console.error('Register error:', error);
            throw error;
        }
    },

    async logout() {
        try {
            await apiRequest('/auth/logout', { method: 'POST' });
            clearAuthData();
            window.location.href = '/frontend/index.html';
        } catch (error) {
            console.error('Logout error:', error);
            clearAuthData();
            window.location.href = '/frontend/index.html';
        }
    }
};

// Setup login form
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = loginForm.querySelector('input[name="email"]').value;
            const password = loginForm.querySelector('input[name="password"]').value;
            const errorAlert = document.getElementById('errorAlert');

            try {
                await AuthService.login(email, password);
            } catch (error) {
                errorAlert.textContent = error.message || 'Login failed';
                errorAlert.classList.remove('d-none');
            }
        });
    }

    // Setup register form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = registerForm.querySelector('input[name="name"]').value;
            const email = registerForm.querySelector('input[name="email"]').value;
            const password = registerForm.querySelector('input[name="password"]').value;
            const errorAlert = document.getElementById('errorAlert');

            try {
                await AuthService.register(name, email, password);
            } catch (error) {
                errorAlert.textContent = error.message || 'Registration failed';
                errorAlert.classList.remove('d-none');
            }
        });
    }

    // Setup logout buttons
    const logoutBtns = document.querySelectorAll('#logoutBtn');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', () => AuthService.logout());
    });
});
