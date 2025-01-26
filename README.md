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
- Système de mailing : Lors de la créatio nde compte et de la réinitialisation du mot de passe un mai lest envoyé
 - Système de connexion : Utilisation de l'authentification de symfony
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