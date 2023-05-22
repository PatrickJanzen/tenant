# tenant

this is the try to setup a multi tenant example for https://github.com/RamyHakam/multi_tenancy_bundle

-checkout
- run 'docker compose up -d'
- run 'docker compose exec php composer install'
- run 'docker compose exec php bin/console doctrine:schema:update --force --complete'
- open http://localhost:8090/build_db

current state: 
 getting an Exception -> SQLSTATE[HY000] [2002] No such file or directory
