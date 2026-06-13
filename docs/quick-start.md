# Быстрый старт

Инструкция по локальному запуску проекта через Docker.

## 1. Клонировать проект

```bash
git clone <repository-url>
cd <project-directory>
```

## 2. Создать `.env`

Скопируйте пример файла окружения:

```bash
cp .env.local .env
```

При необходимости измените настройки базы данных и SMTP в `.env`.

## 3. Запустить проект

```bash
docker compose --env-file .env -f docker/docker-compose.yml up -d --build
```

Проверить, что контейнеры запущены:

```bash
docker ps
```

Будут запущены сервисы:

* `app` — Apache + PHP 8.5;
* `db` — MySQL 8.4.

## 4. Подготовить базу данных

SQL-файлы для подготовки базы находятся в папке `sql`:

* `schema.sql` — структура базы данных;
* `data.sql` — тестовые данные;
* `reset.sql` — сброс базы данных.

Перед импортом SQL-файлов загрузите переменные из `.env` в текущий терминал:

```bash
set -a
source .env
set +a
```

Для импорта SQL-файла используйте команду:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec -T db \
  mysql --default-character-set=utf8mb4 -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < path/to/file.sql
```

Порядок подготовки базы данных:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec -T db \
  mysql --default-character-set=utf8mb4 -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < sql/schema.sql

docker compose --env-file .env -f docker/docker-compose.yml exec -T db \
  mysql --default-character-set=utf8mb4 -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < sql/data.sql
```

При необходимости сбросить базу данных:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec -T db \
  mysql --default-character-set=utf8mb4 -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < sql/reset.sql
```

После сброса повторно выполните импорт `sql/schema.sql` и `sql/data.sql`.

## 5. Открыть проект

Проект доступен в браузере:

```text
http://localhost:8080
```

Если в `.env` изменено значение `APP_PORT`, используйте этот порт вместо `8080`.

## 6. Подключение к базе данных

Для подключения можно использовать любой MySQL-клиент.

```text
Host: 127.0.0.1
Port: 3307
Database: yeticave
User: указан в .env
Password: указан в .env
```

Если в `.env` изменено значение `DB_EXTERNAL_PORT`, используйте этот порт вместо `3307`.

## 7. Composer

Проверить Composer внутри контейнера:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec app composer --version
```

Установить зависимости:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec app composer install
```

## Полезные команды

Перезапустить контейнеры:

```bash
docker compose --env-file .env -f docker/docker-compose.yml up -d
```

Пересоздать контейнер `app` после изменения `.env`:

```bash
docker compose --env-file .env -f docker/docker-compose.yml up -d --force-recreate app
```

Пересобрать контейнер `app` после изменения `Dockerfile` или настроек PHP:

```bash
docker compose --env-file .env -f docker/docker-compose.yml build app
docker compose --env-file .env -f docker/docker-compose.yml up -d app
```

Проверить переменные окружения внутри контейнера:

```bash
docker compose --env-file .env -f docker/docker-compose.yml exec app printenv DB_NAME
docker compose --env-file .env -f docker/docker-compose.yml exec app printenv SMTP_PORT
```
