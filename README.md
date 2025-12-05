# Tinder Backend API

A Laravel-based Tinder-like API with like/dislike functionality, pagination, and automated email notifications.

**Technical Assignment for HyperHire**

## Submission

- **GitHub Repository:** https://github.com/GhostzeroID/tinder-backend
- **Swagger Documentation:** http://localhost:8000/api/documentation (accessible after running locally)
- **Submit to:** UJJWAL@HYPERHIRE.IN

**Note:** No deployment required - reviewer will test locally

## Requirements

- PHP 8.2+
- Composer
- MySQL

## Installation

```bash
git clone https://github.com/yourusername/tinder-backend.git
cd tinder-backend
composer install
cp .env.example .env
php artisan key:generate
```

## Database Configuration

Create a MySQL database:
```sql
CREATE DATABASE tinder_backend;
```

Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tinder_backend
DB_USERNAME=root
DB_PASSWORD=your_password
```

Run migrations:
```bash
php artisan migrate
```

## Add Test Data

```bash
php artisan tinker
```

```php
App\Models\Person::create(['name' => 'John Doe', 'age' => 25, 'pictures' => ['photo1.jpg', 'photo2.jpg'], 'location' => 'Jakarta']);
App\Models\Person::create(['name' => 'Jane Smith', 'age' => 23, 'pictures' => ['photo3.jpg'], 'location' => 'Bandung']);
App\Models\Person::create(['name' => 'Mike Wilson', 'age' => 28, 'pictures' => ['photo4.jpg'], 'location' => 'Surabaya']);
```

Type `exit` when done.

## Generate API Documentation

```bash
php artisan l5-swagger:generate
```

## Start Server

```bash
php artisan serve
```

Server runs at: `http://localhost:8000`

## API Documentation (Swagger)

Open browser and go to:
```
http://localhost:8000/api/documentation
```

You can test all endpoints directly from Swagger UI.

## API Endpoints

### 1. Get People (with pagination)
```bash
GET /api/people?page=1&per_page=10
```

Example:
```bash
curl http://localhost:8000/api/people
```

### 2. Like/Dislike Someone
```bash
POST /api/like
Content-Type: application/json

{
    "person_id": 1,
    "target_id": 2,
    "type": "like"
}
```

Example:
```bash
curl -X POST http://localhost:8000/api/like \
  -H "Content-Type: application/json" \
  -d '{"person_id": 1, "target_id": 2, "type": "like"}'
```

### 3. Get Liked People
```bash
GET /api/liked/{personId}
```

Example:
```bash
curl http://localhost:8000/api/liked/1
```

## Email Notification Setup

The app sends email alerts when someone gets 50+ likes.

Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

For testing without sending real emails:
```env
MAIL_MAILER=log
```

### Testing the Notification System

1. **Create test data with 50+ likes:**
```bash
php artisan tinker
```

Then run:
```php
// Create a person with 52 likes for testing
$person = App\Models\Person::first();
$others = App\Models\Person::where('id', '!=', $person->id)->get();

$count = 0;
foreach ($others as $other) {
    for ($i = 0; $i < 6; $i++) {
        App\Models\Like::create([
            'person_id' => $other->id,
            'target_id' => $person->id,
            'type' => 'like'
        ]);
        $count++;
        if ($count >= 52) break 2;
    }
}
exit
```

2. **Run the notification command:**
```bash
php artisan check:popular
```

Expected output:
```
Checking for popular people...
Total people in database: 10

[ALERT] John Doe (ID: 1)
   Likes received: 52
   Status: Popular (threshold exceeded)
   Email sent to: admin@example.com

Total notification emails sent: 1
```

3. **Check the email log:**
```bash
tail -30 storage/logs/laravel.log
```

You should see the email notification with person details.

## Cronjob Setup (Production)

Add to crontab:
```bash
crontab -e
```

Add this line:
```cron
* * * * * cd /path/to/tinder-backend && php artisan schedule:run >> /dev/null 2>&1
```

The notification runs every hour automatically.

## Database Schema

**people table:**
- id, name, age, pictures (json), location

**likes table:**
- id, person_id (FK), target_id (FK), type (like/dislike)

## Common Issues

**Swagger not showing:**
```bash
php artisan l5-swagger:generate
php artisan config:clear
```

**Permission errors:**
```bash
chmod -R 775 storage bootstrap/cache
```

**Database connection error:**
- Check MySQL is running
- Verify `.env` credentials are correct

## Quick Test

After setup, test with curl:

```bash
# Get people list
curl http://localhost:8000/api/people

# Like someone
curl -X POST http://localhost:8000/api/like \
  -H "Content-Type: application/json" \
  -d '{"person_id": 1, "target_id": 2, "type": "like"}'

# Get liked list
curl http://localhost:8000/api/liked/1
```

Or use Swagger UI at: `http://localhost:8000/api/documentation`

## Assignment Requirements Checklist

### People Data Structure
✓ name (string)  
✓ age (integer)  
✓ pictures (json array)  
✓ location (string)

### Required Features
✓ List of recommended people with pagination  
✓ Like person endpoint  
✓ Dislike person endpoint  
✓ Liked people list API  
✓ Cronjob - email notification when person gets 50+ likes

### Infrastructure
✓ PHP Laravel framework  
✓ MySQL database with proper schema  
✓ Swagger documentation (testable)  
✓ Foreign keys with cascade delete  
✓ API validation

## Submission Checklist

Before submitting to UJJWAL@HYPERHIRE.IN:

- [ ] Push all code to GitHub repository
- [ ] README is clear and complete
- [ ] Test all endpoints work correctly locally
- [ ] Swagger documentation accessible at http://localhost:8000/api/documentation
- [ ] Migrations run successfully
- [ ] Cronjob command tested (`php artisan check:popular`)
- [ ] All required features implemented
- [ ] Database schema created with foreign keys

Email should include:
- GitHub repository link
- Brief description of implementation
- Instructions that reviewer can follow README to test locally

## License

MIT
