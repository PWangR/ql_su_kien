# Chay du an bang Docker

Docker trong du an nay dung de gom moi truong Laravel vao mot bo container gom:

- `nginx`: web server, mo cong `http://localhost:8080`
- `app`: PHP-FPM chay Laravel
- `db`: MySQL 8, mo cong may host `3307`
- `redis`: Redis, mo cong may host `6380`
- `node`: Vite dev server, mo cong `http://localhost:5173`
- `scheduler`: chay Laravel scheduler cho cac tac vu hen gio, bao gom thong bao nhac su kien

## Cach chay nhanh tren Windows

1. Mo Docker Desktop.
2. Double click `start-docker.bat`.
3. Truy cap `http://localhost:8080`.

Script nay se tu dong:

- tao `.env` tu `.env.docker.example` neu chua co
- build va khoi dong container
- chay `composer install`
- chay migration
- tao storage link

Dung du an:

```bat
stop-docker.bat
```

## Lenh thu cong

Khoi dong:

```bash
docker compose up -d --build
```

Chay migration:

```bash
docker compose exec app php artisan migrate
```

Chay lenh artisan:

```bash
docker compose exec app php artisan route:list
```

Xem log:

```bash
docker compose logs -f app
docker compose logs -f scheduler
docker compose logs -f node
```

Kiem tra database Docker:

```bat
check-docker-db.bat
```

Import du lieu mau tu `ql_su_kien.sql` vao MySQL container:

```bat
import-docker-db.bat
```

Dung container:

```bash
docker compose down
```

Xoa ca database volume de lam lai tu dau:

```bash
docker compose down -v
```

## Cong mac dinh

- Web: `8080`
- Vite: `5173`
- MySQL host port: `3307`
- Redis host port: `6380`

Co the doi cong bang cach them cac bien nay vao `.env`:

```dotenv
APP_PORT=8081
VITE_PORT=5174
DB_FORWARD_PORT=3308
REDIS_FORWARD_PORT=6381
```
