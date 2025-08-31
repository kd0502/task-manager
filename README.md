## Task Manager
A system to manage users and their tasks.

#### Project Technologies
Symfony v6.4 (LTS)\
PHP v8.4 \
MySQL v8.0 \
Nginx (Latest)

#### Steps to build and start the containers
##### Pre-requisites
1. Git
2. Docker Engine or Docker Desktop (depending on OS):
    - Windows / macOS → [Docker Desktop](https://www.docker.com/products/docker-desktop?utm_source=chatgpt.com)
    - Linux → [Docker Engine](https://docs.docker.com/engine/install/?utm_source=chatgpt.com) + [Docker Compose plugin](https://docs.docker.com/compose/install/?utm_source=chatgpt.com)
    
#### Project setup (Docker) - Using Terminal or CMD
1. Clone the project \
    <code> git clone https://github.com/kd0502/task-manager.git </code>
2. Go to project root directory \
    <code> cd task-manager </code>
3. Make sure docker is running \
    <code> docker --version </code>
4. Build project and run containers using docker compose \
    <code> docker compose up -d --build </code>
    
Note: This process may take up to 10 minutes to finish.

Database migrations will run automatically for the initial setup. \
To manually run migrations use below commands

1. Create migrations for new entities \
    <code>docker compose exec php php bin/console doctrine:migrations:diff</code>
2. Run migrations \
    <code>docker compose exec php php bin/console doctrine:migrations:migrate</code>
    
Install dependencies

<code> docker compose exec php composer install </code>
    
#### Access the project (Admin panel)

<code> http://localhost:8080 </code>

It will automatically redirect to Users section


#### API Endpoints

##### Users

| Method | Endpoint        | Description           | Auth Required |
| ------ | --------------- | --------------------- | ------------- |
| GET    | /api/users      | Get list of all users | No            |
| POST   | /api/users      | Create a new user     | No            |


##### Tasks

| Method | Endpoint                | Description                | Auth Required |
| ------ | ----------------------- | -------------------------- | ------------- |
| GET    | /api/users/{id}/tasks   | Get list of user's tasks   | No            |
| POST   | /api/users/{id}/tasks   | Create a new task for user | No            |
| PATCH  | /api/tasks/{id}         | Update a task status       | No            |
| DELETE | /api/tasks/{id}         | Delete a task              | No            |

#### Custom Commands

##### Generate Tasks reports based on status for each user

<code>docker compose exec php php bin/console app:tasks:report</code>

Expected output - Separate tables for each users with Tasks count per status (todo, in_progress, done)


#### Run the Test Script (Created for Create new user API)

<code>docker compose exec php php bin/phpunit</code>