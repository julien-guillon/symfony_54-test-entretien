
# Tâches à réaliser
1. ~~Créez une entité Article comprenant les champs suivants :~~

   | champ        |  type   | limite | nullable |
   |:-------:|:------------:|:--------:|:---:|
   | title        | varchar | 150    |   non    |
   | slug         | varchar | 150    |   non    |
   | introduction | varchar | 255    |   oui    |
   | content      |  text   |        |   non    |
   | photo        |  file   |        |   oui    |
~~Le champ slug doit être unique~~

2. ~~Créez un controller `src\Controller\ArticleController`~~
3. ~~Créez la route `/articles/add`~~
    - ~~créez et affichez un formulaire permettant d'ajouter un article~~
    - ~~prévoir la redimension et/ou le recadrage automatique de la photo de l'article~~ 
4. ~~Créez la route `/articles`~~
    - ~~listez sur cette page les articles en affichant uniquement le titre, avec au bout trois boutons permettant :~~
        - ~~l'accès à l'article~~
        - ~~la modification de l'article~~
        - ~~la suppression de l'article~~
5. ~~Créez la route `/article/{slug}`~~
    - ~~cette page devra afficher un article, en vous basant sur la maquette suivante (mobile et desktop)~~
      ![Maquette desktop page article](Desktop.png)
      ![Maquette mobile page article](Mobile.png)
6. ~~Créez la route `/article/update/{slug}`~~
    - ~~créez un formulaire permettant de modifier uniquement l'introduction et le contenu de l'article~~
7. ~~Créez la route `/article/delete/{slug}`~~
    - ~~cette route devra supprimer l'article~~
8. ~~Créez un CRUD REST pour l'entité Article~~
9. ~~(Bonus) : Créez des fixtures pour remplir la base de données~~
10. (Bonus) : Créez une série de tests sur le CRUD REST
