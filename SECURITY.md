# Security Guidelines for StyleMate

## Important Security Notes

### 1. Environment Variables (.env files)
- **NEVER commit `.env` files to version control**
- `.env` files contain sensitive information (database passwords, API keys)
- Use `.env.example` as a template for developers
- Always add `.env` to `.gitignore` ✓

### 2. API Keys
- Gemini API Key should be stored in `backend/.env`
- Never hardcode API keys in source files
- Rotate API keys periodically
- Use restricted API keys with minimal permissions

### 3. Database Security
- Use strong passwords for database users
- Create a dedicated database user for the app
- Don't use root credentials in production
- Keep database backups secure
- Consider database encryption for production

### 4. File Uploads
- Store uploaded files outside webroot when possible
- Validate file types on server side
- Use secure filenames (rename with unique IDs)
- Implement file size limits ✓
- Consider virus scanning for production

### 5. Authentication
- Always use HTTPS in production
- Hash passwords with bcrypt ✓
- Implement rate limiting for login attempts
- Set secure session cookies
- Implement CSRF protection

### 6. SQL Injection Prevention
- Use parameterized queries ✓
- Validate and sanitize all inputs
- Use prepared statements ✓
- Principle of least privilege for DB user

### 7. CORS & API Security
- Whitelist trusted domains for CORS
- Implement API rate limiting
- Use authentication tokens
- Validate API request origin
- Use HTTPS only in production

### 8. Dependencies
- Keep PHP, MySQL, and all dependencies updated
- Review third-party packages for vulnerabilities
- Use composer for package management
- Regular security audits

### 9. Error Handling
- Don't expose sensitive information in error messages
- Log errors securely
- Use generic error messages for users
- Log security events

### 10. Deployment Checklist
- [ ] Set APP_ENV=production
- [ ] Use HTTPS only
- [ ] Disable debug mode
- [ ] Set strong database password
- [ ] Secure API keys
- [ ] Implement proper logging
- [ ] Set file upload limits
- [ ] Configure firewall rules
- [ ] Regular backups
- [ ] Security monitoring

## Files in .gitignore (Do NOT commit)
- `.env` - Production secrets
- `vendor/` - Composer dependencies
- `logs/` - Application logs
- `uploads/` - User uploaded files
- `.vscode/` - IDE settings
- `*.log` - Log files

## Quick Setup (Secure)
```bash
# 1. Create .env from example
cp backend/.env.example backend/.env

# 2. Edit .env with strong credentials
nano backend/.env

# 3. Verify .gitignore includes .env
grep ".env" .gitignore

# 4. Never commit .env
git status  # Ensure .env is NOT listed
```

## Additional Resources
- [OWASP Top 10](https://owasp.org/Top10/)
- [PHP Security Handbook](https://www.php.net/manual/en/security.php)
- [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)
