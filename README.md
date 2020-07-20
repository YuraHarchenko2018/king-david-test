## Instalation local
1. cp .env.example .env
2. Modify .env variables (
    // to make it easier for you to deploy
    set => {
        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=laravel
        DB_USERNAME=root
        DB_PASSWORD=1111
    }, 
    set => randon string for JWT_SECRET_KEY
    set => 'folk' or 'intervention' for IMAGE_ENGINE
)
3. docker run --rm -v $(pwd):/app composer install
4. docker-compose up -d --build
5. docker-compose exec app php artisan migrate
6. docker-compose exec app php artisan queue:listen

# Have two image-engine "folk' and "intervention", which working async.
# Have full needed statistic. And realized other conditions.

# I could not use "policy" in my project.
# I can't install "mongo" for my project. I tried.