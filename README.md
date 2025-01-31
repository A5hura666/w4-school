# W4-school - BOURGUIGNEAU Éthan (A5hura666) GELLY Valentin (Valentin-Gelly)

## Idées d'application

#### Application de cours en ligne (idée de base et de potentielles évolutions future) :

- Application de cours en ligne pour les élèves et les professeurs
- Les professeurs peuvent créer des cours, des chapitres et des leçons
- Les élèves peuvent suivre les cours, les chapitres, les leçons et faire des exercices
- Gestion administrateur pour gérer les professeurs et les élèves
- Système de notation et progression pour les élèves
- Système de paiement pour les cours
- Système de chat pour les élèves et les professeurs

## Fonctionnalitées développé 

- Système de connexion (login, logout, rôle)
- système de création de compte (élèves/prof)
- Système de réinitialisation de mot de passe
- Système de publication/suppression/modification de cours/chapitres/leçons (prof/admin)
- Système de mailing (envoi de mail lors de la création de compte et de la réinitialisation du mot de passe)
- Statistiques pour le backoffice (admin, prof)
- Ajout de cours dans une liste de favoris (élèves)
- Upload d'images pour les cours (prof)

## Points techniques réalisés

 - Application symfony version 6.4 full-stack :
   - Utilisation de doctrine pour la gestion de la base de donnée
   - Utilisation de twig (moteur de template) pour la gestion des vues de l'application
   - Utilisation de webpack pour la gestion des assets (css, js)
   - Base de donnée mysql


 - Système de mailing : Lors de la création de compte et de la réinitialisation du mot de passe un mail est envoyé
   - Pour la partie envoi de mail, on a utilisé le composant `Mailer` de symfony. On a configuré le fichier `.env` pour utiliser le serveur SMTP. Pour exploiter cela nous avons installé en local mailhog pour simuler un serveur SMTP.
   - De plus, pour simplifier les développement par la suite nous avons mis en place un service `MailService` qui permet d'envoyer des mails en utilisant le composant `Mailer` de symfony pour nous permettre de centraliser toute la logique d'envoi de mail.
   - Exemple d'utilisation du service `MailService` : réinitialisation du mot de passe, lors de la création de compte...


 - Système de connexion : Utilisation de l'authentification de symfony pour la connexion des utilisateurs à l'application (login, logout, registration)
   - Pour la connexion, on a utilisé le composant `Security` de symfony. On a configuré le fichier `security.yaml` pour définir les différentes stratégies d'authentification (form_login, logout, registration).
   - Nous avons fait en sorte que quand l'utilisateur se connecte, il est automatiquement redirigé vers la page du rôle qui lui est attribué (prof, élève ou admin).


 - Utilisation de Listener : 
   - On a créé une interface `Sortable`. Les classes `Chapters` et les `Lessons` implémentent cette interface pour que l'on puisse mieux utiliser le listener `SortableListener`.

   - Création d'un listener sur les éléments `Sortable` (chapitres, et leçons) pour mettre à jour l'ordre des éléments lors d'un edit/delete/create d'un élément. 
Pour cela, on utilise les événements `prePersist`, `preUpdate` et `preRemove` de doctrine et on déclare un listener dans le fichier `services.yaml`. 
Le listener sera alors lancé à chaque fois qu'un élément sera créé/modifié/supprimé et on test dans les fonctions si l'élément modifié est de type `Sortable`.
```yaml
    App\EventListener\SortableListener:
        tags:
            - { name: doctrine.event_listener, event: prePersist }
            - { name: doctrine.event_listener, event: preRemove }
            - { name: doctrine.event_listener, event: preUpdate }
```

- Création de système d'upload de multimédia pour les cours
   - Pour faire fonctionner tout le système d'upload de multimédia, nous avons créé un service `MediaService` qui permet de centraliser toute la logique d'upload de multimédia.
     Ce service permettra à terme de gérer l'upload de fichiers multimédia de tout type (images, vidéos, pdf...) et de les stocker dans différent répertoire se trouvant dans `public/uploads`.
     Pour le moment, nous avons implémenté la gestion de l'upload d'images pour les cours.
     Voici les quatres répertoires que nous avons créé pour stocker les fichiers multimédia : image, document, video, audio.
     Ce qui est intéressant, c'est que l'on ne va pas stocker les images directement en base de donnée, mais on va stocker le chemin de l'image dans la base de donnée.
     Cependant, à terme, il faudrait stocker les images dans un serveur de stockage de fichier comme AWS S3 par exemple.


#### - Création de graphique avec ChartJS pour l'interface `ADMIN`

#### - Utilisation de TailwindCSS pour le design de l'application

## Difficultés rencontrées

- La mise en place du système d'upload de multimédia pour les cours, car dans le temps imparti, nous avons préféré faire l'upload directement dans le projet plutôt que de passer par un serveur de stockage de fichier comme AWS S3.


- Il y a aussi toute la partie sur l'interface sortable qui a été difficile à mettre en place, car il a fallu comprendre comment fonctionne les listeners mais aussi toute la logique derrière pour mettre à jour l'ordre des éléments sans en oublier ou bien ne pas casser le fonctionnement de base
par exemple à un moment, on avait un problème de récursivité infinie, puisqu'on mettait à jour l'ordre des éléments dans le listener et cela déclenchait un autre événement qui rappelait le listener et ainsi de suite ou bien un problème de valeur qui devenait négative.


- Il y a aussi peut-être l'ampleur du projet que nous avons choisi avec la présence de deux backoffices (prof et admin) ainsi qu'un frontoffice (élève) qui a demandé beaucoup de temps pour mettre en place toutes les fonctionnalités.
Ce qui a fait que nous n'avons pas pu implémenter toutes les fonctionnalités que nous avions prévues au départ, je prendrais l'exemple du frontoffice qui n'est pas très développé et où nous aurions aimé mettre plus en valeur. Tout cela est frustrant, 
car nous commencions à avoir toute la partie back qui marchait bien.

## Améliorations possibles

- Dockeriser l'application pour faciliter le déploiement de l'application


- Mettre en place un serveur de stockage de fichier comme AWS S3 pour stocker les fichiers multimédia


- Pour cette partie, il y a des choses qui ont déjà était pensé, je prendrai l'exemple de la BDD qui est déjà bien structurée pour accueillir de nouvelles fonctionnalités.
  - Améliorer le frontoffice pour mettre en valeur les cours et les chapitres
  - Ajouter les exercices pour les élèves (codes de programmation, quiz...)
  - Ajouter un système de notation, des tags ou bien une progression pour les cours
  - Ajouter un système de recherche pour les cours
  - Ajouter un système de paiement pour les cours
  - Ajouter un système de chat pour les élèves et les profs
  - Ajouter un système de notification pour les élèves et les profs

## lancemenent du projet

```bash
composer install
```
```bash
npm install
```
```bash
npm run build
```
```bash
symfony server:start
```

```bash
php bin/console tailwind:build --watch
```