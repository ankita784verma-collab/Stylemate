# StyleMate - Personal Digital Wardrobe Assistant

## Project Structure

### Backend (`/backend`)

```
backend/
├── index.php                 # Main router
├── .env                      # Environment configuration (DO NOT commit)
├── .env.example              # Example environment file
├── api/
│   ├── auth/
│   │   ├── login.php        # Login endpoint
│   │   ├── register.php     # Registration endpoint
│   │   └── logout.php       # Logout endpoint
│   ├── clothing/
│   │   ├── add.php          # Add clothing item
│   │   ├── list.php         # Get wardrobe items
│   │   └── delete.php       # Delete clothing item
│   └── outfit/
│       └── generate.php     # Generate outfit suggestions
├── config/
│   └── db.php              # Database connection
├── database/
│   └── stylemate_db.sql    # Database schema
└── uploads/                # Clothing images storage
```

**API Endpoints:**
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `POST /api/clothing/add` - Add clothing item
- `GET /api/clothing/list` - Get user's wardrobe
- `DELETE /api/clothing/delete` - Delete clothing item
- `POST /api/outfit/generate` - Generate outfit recommendations

### Frontend (`/frontend`)

```
frontend/
├── index.html              # Landing page
├── .env                    # Environment configuration
├── pages/
│   ├── dashboard.html      # Main dashboard
│   ├── login.html          # Login page
│   ├── register.html       # Registration page
│   ├── wardrobe.html       # Wardrobe management
│   ├── outfit.html         # Outfit generator
│   └── style_profiles.html # Analytics page
├── css/
│   └── style.css          # Main stylesheet
└── js/
    ├── app.js             # Main app config & utilities
    ├── auth.js            # Authentication functions
    ├── wardrobe.js        # Wardrobe management
    └── outfit.js          # Outfit generation
```

## Installation & Setup

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Node.js (optional, for package management)

### Step 1: Database Setup
```bash
# Import database schema
mysql -u root -p stylemate_db < backend/database/stylemate_db.sql
```

### Step 2: Backend Configuration
```bash
# Copy .env.example to .env
cp backend/.env.example backend/.env

# Edit .env with your database credentials
# Important: Do NOT commit .env file!
# Database credentials should be kept secret
```

### Step 3: Frontend Configuration
```bash
# Copy .env.example to .env
cp frontend/.env.example frontend/.env

# Update API_BASE if running on different host/port
```

### Step 4: Start Development Server
```bash
# PHP built-in server (backend)
cd backend
php -S localhost:8000

# Frontend: Open in browser
http://localhost:8080/frontend/index.html
```

## Features

### 1. User Authentication
- Registration with email & password
- Login with credentials
- Session management
- Logout functionality

### 2. Wardrobe Management
- Upload clothing items with images
- Organize by category, color, style, season
- Delete items
- View complete wardrobe

### 3. AI Outfit Generator
- Generate outfit suggestions
- Filter by occasion & season
- Get recommendations based on wardrobe
- View suggested combinations

### 4. Style Analytics
- Wardrobe statistics
- Color distribution analysis
- Style preferences tracking
- Category breakdown

## Configuration Files
**DO NOT COMMIT THIS FILE** - Keep it secret!

```
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_secure_password
DB_NAME=stylemate_db
GEMINI_API_KEY=your_api_key_from_google
APP_ENV=production
```

### Frontend .env
```
API_BASE=http://your-domain.com/backend/api
APP_ENV=production
```

**Note:** Use `.env.example` as a template and create your own `.env` files without committing them._BASE=http://localhost:8000/api
APP_ENV=development
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Clothing Items Table
```sql
CREATE TABLE clothing_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    color VARCHAR(100),
    secondary_color VARCHAR(100),
    pattern VARCHAR(100),
    style VARCHAR(100),
    season VARCHAR(100),
    occasion VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

### Categories Table
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);
```

## API Documentation

### Authentication Endpoints

#### Login
```
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}

Response:
{
    "success": true,
    "message": "Login successful",
    "user_id": 1,
    "token": "jwt_token"
}
```

#### Register
```
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}

Response:
{
    "success": true,
    "message": "Registration successful"
}
```

### Clothing Endpoints

#### Add Item
```
POST /api/clothing/add
Content-Type: multipart/form-data

{
    "name": "Blue T-Shirt",
    "category_id": 1,
    "color": "Blue",
    "image": <file>
}
```

#### Get Wardrobe
```
GET /api/clothing/list

Response:
{
    "success": true,
    "items": [...]
}
```

## Development Notes

### File Organization Principles
- **Backend**: API-first architecture with clear route organization
- **Frontend**: Separation of pages, styles, and scripts
- **Configuration**: Centralized .env usage
- **Security**: Password hashing, session management, CORS

### Best Practices
- Use parameterized queries to prevent SQL injection
- Validate all user inputs
- Store sensitive data in .env files
- Use proper HTTP status codes
- Implement CORS for API calls

## Future Enhancements

- [ ] Advanced filtering in wardrobe
- [ ] Social sharing of outfits
- [ ] Seasonal outfit calendar
- [ ] Integration with fashion APIs
- [ ] Mobile app development
- [ ] Real-time collaboration
- [ ] Advanced analytics dashboard
- [ ] Machine learning for outfit suggestions

## License

MIT License - Feel free to use and modify

## Support

For issues or questions, please create an issue in the repository.
