# Larva Interactions

Web interactions use Larva stack.

> Larva = Laravel + Livewire + AlpineJS + TailwindCSS

## How to run?

Make sure you use PHP version 8.3 or higher and NodeJS version 20 or higher.

```bash
# First tab terminal
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan storage:link
```

```bash
# Second tab terminal
pnpm i
pnpm run dev
```

## Interactions

- Button
- Third-party library
    - Select (Tom Select)
    - Alert (Sweet Alert)
    - Text Area (Trix Editor)
- Nested Sortable
- Form
- Upload CSV file
- Datatable
- Reset Password

### Job Batching

In order to make Job Batching works, I use queue with Redis and job batching feature in Laravel. The reason has mentioned in [Laravel documentation](https://laravel.com/docs/11.x/queues#introduction).

> While building your web application, you may have some tasks, such as parsing and storing an uploaded CSV file, that take too long to perform during a typical web request. Thankfully, Laravel allows you to easily create queued jobs that may be processed in the background. By moving time intensive tasks to a queue, your application can respond to web requests with blazing speed and provide a better user experience to your customers.

Brief steps:

- You already install Redis in your computer.
- Run `redis-server` in tab terminal.
- Run `redis-cli` in another tab terminal to create a new Redis user instead of a default user.

```bash
acl setuser kresna on +@all -@dangerous >nanana321 ~*
```

Info:

- `on`: Activate user.
- `+@all`: Add all the commands in Redis
- `-@dangerous`: Remove dangerous commands
- `><password>`: Add password. Example `>nanana321` means add "nanana321" to the list of valid passwords.
- `~<pattern>`: Add pattern of keys that can be mentioned as part of commands. For instance `~*` means allows all the keys.

- Update `.env` file.

```diff
- QUEUE_CONNECTION=database
+ QUEUE_CONNECTION=redis

+ REDIS_USERNAME=kresna
- REDIS_PASSWORD=null
+ REDIS_PASSWORD=nanana321
```

- Run `php artisan queue:work` to start queue.

### Job Batching in Docker Container

To run this case with Docker container, I use docker image from serversideup/docker-php. In this project, I try to test Laravel Job Batching (e.g. Process CSV File). The ServerSideUp docs recommend to create 2 container in order to use queue worker. In order to use queue worker, I need to create 2 containers. First container is web app, the second container is queue worker. The 2 container use the same Docker image.

> Use database or redis as QUEUE_CONNECTION such as database (MySQL or PgSQL) or redis (Redis) in order to make it works. SQLite doesn't work because it doesn't share connection between 2 containers.

Steps:

1. Copy example .env file and update the value of necessary key.

```bash
cp .env.example .env
```

Usually, I update values on keys like:

```bash
DB_HOST=???
DB_DATABASE=???
DB_USERNAME=???
DB_PASSWORD=???

QUEUE_CONNECTION=???

REDIS_HOST=???
REDIS_PASSWORD=???
REDIS_PORT=6379
# I'm using predis because it is simple to install instead of phpredis.
# I understand that performance of phpredis is better than predis.
REDIS_CLIENT=predis
```

2. Run necessary command below.

```bash
composer install
php artisan key:generate
# Copy csv file to storage/app folder because I need to use this as example.
cp csvfile/* storage/app
```

3. Build docker image with command below.

```bash
# Build a Docker image
docker build -f local.Dockerfile --no-cache -t senkustream/larva-interactions:local .
```

4. Run docker compose with command below.

```
docker compose -f local.docker-compose.yml up -d
```

5. Open [http://localhost:8080](http://localhost:8080), go to Job Batching page and start import CSV file.
