# W4-school - BOURGUIGNEAU Éthan GELLY Valentin

## Idées d'application

Application de cours en ligne :

- système de connexion
- publication/suppression/modification de cours (prof)
- ajouter un commentaire à un cours (*)
- soumission d'une réponse

## Structure BDD

notifications
* id (INT, Primary Key, Auto Increment)
* user_id (INT, Foreign Key vers users.id)
* message (TEXT)
* is_read (BOOLEAN, défaut : false)
* created_at (TIMESTAMP)
## Partage des tâches


DoctrineExtensions sortTable

webAssemblyPHP 