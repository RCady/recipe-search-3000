## Getting started
Follow the below instructions to get things running.

### Additional Instructions for Ryan's Submission
##### Seed the database
```
./vendor/bin/sail artisan migrate --seed
```

#### Running the CreateRecipe Command
```shell
# Ingredient format: amount,unit,ingredient
./vendor/bin/sail artisan recipes:create "Recipe Name" author@email.com \
    --description "Recipe Description" \
    --ingredients 1,cup,flour \
    --ingredients 0.5,cup,sugar \
    --steps "Add flour" \
    --steps "Add sugar" \
    --steps "Whisk together"
```

### Pre-requisites
- docker
- docker-compose

### Check out this repository
`git clone git@github.com:wildalaskan/skeleton-app.git`

`cd skeleton-app`

### Run composer to kickstart laravel sail

```bash
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    bash -c "composer install"
```

### Run the application
`cp .env.example .env`

`./vendor/bin/sail up -d`

`./vendor/bin/sail artisan key:generate`

`./vendor/bin/sail artisan migrate`

### Kickstart the nuxt frontend
`./vendor/bin/sail npm install --prefix frontend`

### Run the frontend
`./vendor/bin/sail npm run dev --prefix frontend`

### Confirm your application
Visit the frontend http://localhost:3000

Visit the backend http://localhost:8888


### Connecting to your database from localhost
`docker exec -it laravel-mysql-1 bash -c "mysql -uroot -ppassword"`

Or use any database GUI and connect to 127.0.0.1 port 3333


### Other tips
`./vendor/bin/sail down` to bring down the stack

Sometimes it's necessary to restart the nuxt app when adding new routes. Simply `ctrl+c` on the npm command execute
`./vendor/bin/sail npm run dev --prefix frontend` again
