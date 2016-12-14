# Inventaire

## Description

Outil en ligne permettant de gérer à plusieurs une base de données du matériel d’une unité en le liant à différents documents (data-sheet, caractérisation,…).

L’outil tente d’être le plus universel possible :
* pas de catégorie ou tags prédéfini

Les parties administrative et technique sont clairement différenciées pour permettre de remplir uniquement une partie si on le souhaite

## Aperçu

![Preview index](./README_preview_index.png)
![Preview add](./README_preview_add.png)
![Preview informations](./README_preview_informations.png)

## TODO

* **Identifiant labo** : Possibilité de choisir l’identifiant manuellement.
* **Intégration** : Si intégré, afficher un lien dans listing vers le conteneur. Mais également gérer les lots. Il peut arriver qu’un lot de filtre soit acheté et que l’un d’eux soit intégré dans un montage…
* **Base de données** : Passer de mysql à sqlite.
* **Rangement** : Ajouter une case pour indiquer où ranger le matériel quand non utilisé.
* **Catégories de caractéristiques** : Les caractéristiques vont vite devenir très nombreuses et difficiles à trouver dans le sélecteur, penser à créer des catégories de caracs (dimensions, optique, électronique,…).
* **Documents** : Supporter le glisser/déposer, supporter l’envoi de fichier depuis une adresse
* **Type de contrat** : Afficher le type de contrat dans l’intitulé des contrats pour un meilleur affichage = « sur Chaire Julien » plutôt que « sur Julien »
* **Droits** : Ajouter un gestionnaire des droits, qu’un stagiaire ne puisse pas éditer les informations par exemple. Pour plus de simplicité, peut-être juste cacher les boutons de validation…
* **Vous avez un message** : Toutes les modifications de la base doivent être suivies d’un message temporaire.
* **SQL** : Config sql par défaut, ajouter catégorie lot ?
* **Statistiques** : Ajouter des statistiques sur les éléments affichés (nombre d’entrées, prix, camemberts des responsables d’achat, des contrats,…
* **Page d’administration** : Vider le dossier trash, gestion de backup, édition de tags ou d’utilisateurs, suppression de catégorie vide…
* **Division cellulaire** : Que faire si l’on casse un élément et qu’il devient 2 ou 3 (cas déjà arrivé pour un milieu actif…).
* **Suppression sécurisée** : Un historique des modifications ? Avec nom et date ?
* **Traduction** : Supporter plusieurs langues ?
* **Réforme** : Gérer les "à réformer" et les "réformé" ?
* **Tri** : Le tri par catégorie dans index.php ne doit pas être fait sur le numéro correspondant à la catégorie mais au nom de la catégorie


## BUGS
* **Nouveau type de contrat** : Lors de la création d’un nouveau type de contrat contenant un apostrophe, le contrat est resté avec le dernier type de contrat créé, aucun nouveau type n’a été ajouté dans la base.



