# Duolingo Learning Platform

Web aplikacija za upravljanje kursevima jezika sa podrškom za
autentifikaciju korisnika, upravljanje lekcijama i upisima
(enrollments), administrativnu statistiku i integrisani sistem za
prevođenje teksta putem eksternog API-ja.

Aplikacija je podeljena na frontend (React + Vite) i backend (Laravel
API), uz MySQL bazu podataka. Projekat je u potpunosti dockerizovan i
može se pokrenuti lokalno korišćenjem Docker-a i docker-compose-a.

---

## Funkcionalnosti

- Registracija i prijava korisnika (Laravel Sanctum)
- Role-based pristup (admin, teacher, student)
- CRUD operacije nad jezicima, kursevima i lekcijama
- Upravljanje upisima (enrollments)
- Admin dashboard statistika
- Integracija sa MyMemory Translation API
- Prikaz zastava jezika (FlagCDN)
- Swagger (OpenAPI) dokumentacija

---

## Tehnologije

### Backend

- PHP 8.2
- Laravel
- Laravel Sanctum (autentifikacija)
- MySQL 8
- Swagger / OpenAPI (l5-swagger)

### Frontend

- React
- Vite
- Zustand (state management)
- TailwindCSS

### DevOps

- Docker
- Docker Compose
- phpMyAdmin

---

## Struktura projekta

/client -\> React frontend\
/server -\> Laravel backend\
docker-compose.yml

---

## Lokalno pokretanje bez Dockera

### Backend

cd server\
composer install\
php artisan key:generate\
php artisan migrate\
php artisan serve

Backend: http://localhost:8000

---

### Frontend

cd client\
npm install\
npm run dev

Frontend: http://localhost:5173

---

## Pokretanje pomoću Docker-a

docker compose up --build

Frontend: http://localhost:5173\
Backend API: http://localhost:8000\
phpMyAdmin: http://localhost:8080

MySQL:\
Host: mysql\
Port: 3306\
Database: duolingo\
Username: root\
Password: root

---

## Gašenje servisa

docker compose down

Brisanje volumena:

docker compose down -v

---

## Swagger dokumentacija

http://localhost:8000/api/documentation

Regenerisanje:

php artisan l5-swagger:generate

---

## Eksterni API-ji

- MyMemory Translation API\
- FlagCDN
