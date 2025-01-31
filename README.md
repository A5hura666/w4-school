# W4-school - BOURGUIGNEAU Éthan (A5hura666) GELLY Valentin (Valentin-Gelly)

## Idées d'application

Application de cours en ligne :

- système de connexion
- publication/suppression/modification de cours (prof)
- ajouter un commentaire à un cours (*)
- soumission d'une réponse

## Fonctionnalitées développé 

- système de connexion
- publication/suppression/modification de cours/chapitres/leçons (prof/admin)
- système de création de compte (élèves/prof)
- ajout de cours dans une liste de favoris (élèves)


## Points techniques réalisés

 - Application symfony full-stack : 
Utilisation de .twig pour la partie front-end et de symfony pour la partie back-end


 - Système de mailing : Lors de la création de compte et de la réinitialisation du mot de passe un mail est envoyé
   - Pour la partie envoi de mail, on a utilisé le composant `Mailer` de symfony. On a configuré le fichier `.env` pour utiliser le serveur SMTP. Pour exploiter cela nous avons installé en local mailhog pour simuler un serveur SMTP.
   - De plus, pour simplifier les développement par la suite nous avons mis en place un service `MailService` qui permet d'envoyer des mails en utilisant le composant `Mailer` de symfony pour nous permettre de centraliser toute la logique d'envoi de mail.
   - Exemple d'utilisation du service `MailService` : réinitialisation du mot de passe, lors de la création de compte...


 - Système de connexion : Utilisation de l'authentification de symfony pour la connexion des utilisateurs à l'application (login, logout, registration)
   - Pour la connexion, on a utilisé le composant `Security` de symfony. On a configuré le fichier `security.yaml` pour définir les différentes stratégies d'authentification (form_login, logout, registration).
   - Nous avons fait en sorte que quand l'utilisateur se connecte, il est automatiquement redirigé vers la page du rôle qui lui est attribué (prof, élève ou admin).


 - Utilisation de Listener : 

On a créé une interface `Sortable`. Les classes `Chapters` et les `Lessons` implémentent cette interface pour que l'on puisse mieux utiliser le listener `SortableListener`.

Création d'un listener sur les éléments `Sortable` (chapitres, et leçons) pour mettre à jour l'ordre des éléments lors d'un edit/delete/create d'un élément. 
Pour cela, on utilise les événements `prePersist`, `preUpdate` et `preRemove` de doctrine et on déclare un listener dans le fichier `services.yaml`. 
Le listener sera alors lancé à chaque fois qu'un élément sera créé/modifié/supprimé et on test dans les fonctions si l'élément modifié est de type `Sortable`.
```yaml
    App\EventListener\SortableListener:
        tags:
            - { name: doctrine.event_listener, event: prePersist }
            - { name: doctrine.event_listener, event: preRemove }
            - { name: doctrine.event_listener, event: preUpdate }
```

#### - Création de graphique avec ChartJS pour l'interface `ADMIN`

#### - Utilisation de TailwindCSS pour le design de l'application

#### - Création de système d'upload de multimédia pour les cours

Pour faire fonctionner tout le système d'upload de multimédia, nous avons créé un service `MediaService` qui permet de centraliser toute la logique d'upload de multimédia.
Ce service permettra à terme de gérer l'upload de fichiers multimédia de tout type (images, vidéos, pdf...) et de les stocker dans différent répertoire se trouvant dans `public/uploads`.
Pour le moment, nous avons implémenté la gestion de l'upload d'images pour les cours.
Voici les quatres répertoires que nous avons créé pour stocker les fichiers multimédia : image, document, video, audio.
Ce qui est intéressant c'est que l'on ne va pas stocker les images directement en base de donnée mais on va stocker le chemin de l'image dans la base de donnée.
Cependant, à terme il faudrait stocker les images dans un serveur de stockage de fichier comme AWS S3 par exemple.

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