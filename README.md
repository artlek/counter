# Counter App application
Counter App is a simple web aplication to manage counters and meters in your home. It helps to controll usage of your counters.
You can add a counter point and the app will show you how many kWhs you used in a period. It also shows what is the cost of consumed electricity.
The app is created to learn purpose.

## How to use it
Is recommended use Docker to try this app.
1. Install Docker, run Docker Desktop
2. Download this repository and unzip it
3. Open app folder in terminal
4. Build image in Docker by command: <code>docker-compose build</code> and then <code>docker-compose up -d</code>
5. Open app container by command: <code>docker exec -it counter-main_fpm_1 bash</code>. Name 'counter-main_fpm_1' may be different according what Docker named the container. 
6. Install vendors: <code>composer install</code>
7. Make database migrations: <code>php bin/console doctrine:migrations:migrate --no-interaction</code>
9. Open localhost:10302 in your webbrowser

version: 1.0.0
