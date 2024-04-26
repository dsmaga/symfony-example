# Symfony application example

Small example of Symfony application (CQRS + simple Event Sourcing) with Docker configuration.


# Installation

```bash
cp compose.override.yml.dist compose.override.yml
# Edit compose.override.yml to your needs
make create
```

# UI

After first installation database is empty. 
Fill it by using cli command.


## Cli

```bash
# Go to project directory
make app

# from docker container
./bin/console hello-kitty:manage
```

## Rest

http://localhost:8081/hello-kitty

- Items list:
  ```bash
  curl --location --request GET 'localhost:8081/hello-kitty' \
        --header 'Content-Type: application/json' 
  ```

- Create item:
  ```bash
  curl --location --request POST 'localhost:8081/hello-kitty' \
    --header 'Content-Type: application/json' \
    --data-raw '{"name": "Mimbla"}'  
  ```  
  Response:
  ```json
    {"id":"df0e5f19-ec46-452a-a3ea-ee8c527d228a"}
  ```
- Get single item by id (use id from previous response):
  ```bash
  curl --location --request GET 'localhost:8081/hello-kitty/df0e5f19-ec46-452a-a3ea-ee8c527d228a' \
        --header 'Content-Type: application/json'  
  ```
- Update item:
  ```curl
  curl --location --request PATCH 'localhost:8081/hello-kitty/df0e5f19-ec46-452a-a3ea-ee8c527d228a' \
	--header 'Content-Type: application/json' \
	--data-raw '{"name": "Mala Mi"}'
  ```
