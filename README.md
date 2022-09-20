# Test Symfony 5.4 (back et front)

Cher·e candidat·e, veuillez suivre ce README et répondre à l'ensemble des questions.

## Ce test nécessite
- une connexion internet
- un compte Github *(https://github.com/)*
- un IDE compatible PHP (nous suggérons PhpStorm ou Visual Studio Code avec les plugins symfony, yaml, twig et les annotations php)
- Docker *(https://docs.docker.com/get-docker/)*
- Docker Compose si vous êtes dans un environnement Linux *(https://docs.docker.com/compose/install/)*

## Ce projet contient
- un serveur web déjà configuré, qui sera accessible via `https://localhost`
- une base de données PostgreSQL déjà accessible 
- un projet Symfony 5.4 avec composer et les dépendances adéquates

## Mise en route
- Clonez ce repo sur votre ordinateur
- Assurez-vous d'avoir coupé tout serveur web local pouvant être présent sur votre machine (Apache, Nginx, LAMP, WAMP, MAMP etc)
- Lancez `docker-compose build --pull --no-cache`
- Lancez `docker-compose up` (les logs seront affichés dans le terminal où vous aurez lancé cette commande)
- Ouvrez `https://localhost` dans votre navigateur web préféré et [acceptez le certificat de sécurité autogénéré](https://stackoverflow.com/questions/7580508/getting-chrome-to-accept-self-signed-localhost-certificate/15076602#15076602)

### Rappels
Pour lancer une commande dans le container Symfony :
```bash
docker-compose exec php \
    bin/console commande
```

## À vous de jouer !
Veuillez suivre les indications présentes dans le fichier [INSTRUCTIONS.md](INSTRUCTIONS.md) présent à la racine de ce projet.
Il contient les tâches à réaliser pour ce test.

## Envoyez vos résultats
Pour envoyer vos résultats, vous simplement push votre travail avec un pull request.

**Bonne chance !**



Add GitHub