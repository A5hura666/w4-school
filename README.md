# W4-school - BOURGUIGNEAU Éthan GELLY Valentin

## Idées d'application

Application de cours en ligne :

- système de connexion
- publication/suppression/modification de cours (prof)
- ajouter un commentaire à un cours (*)
- soumission d'une réponse

## Structure BDD

- User:
    - id
    - email
    - Name
    - FirstName
    - Roles

- courses_category
  - id
  - Name

- courses_chapter
  - id
  - id_category
  - name
  - description
  - done
  - id_user

- courses
  - id
  - id_chapter
  - name
  - description
- questions

## Partage des tâches


DoctrineExtensions sortTable

webAssemblyPHP 