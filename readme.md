# **Projet Symfony : Gestion des événements et des avis**

## **Installation**

1. **Cloner le projet**  
   git clone <url_du_projet>  
   cd <nom_du_dossier>

2. **Installer les dépendances**  
   composer install

3. **Configurer la base de données**  
   Modifier le fichier `.env` ou `.env.local` avec vos informations de connexion :  
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/nom_de_la_db"

4. **Créer la base de données et charger les fixtures**  
   php bin/console doctrine:database:create  
   php bin/console doctrine:migrations:migrate  
   php bin/console doctrine:fixtures:load

5. **Lancer le serveur Symfony**  
   symfony server:start

---

## **Identifiants de test**

- **Administrateur** : admin@test.com / **adminpass**  
- **Responsable 1** : responsable1@test.com / **resp1pass**  
- **Responsable 2** : responsable2@test.com / **resp2pass**  
- **Utilisateur 1** : user1@test.com / **user1pass**  
- **Utilisateur 2** : user2@test.com / **user2pass**  
- **Utilisateur 3** : user3@test.com / **user3pass**  

---

## **Fonctionnalités**

- **Authentification** : inscription, connexion, déconnexion  
- **Rôles** : utilisateur standard, responsable, administrateur  
- **Événements** : liste, filtres, tri alphabétique  
- **Avis** : créer, modifier, valider/refuser (par responsable ou admin)  
- **Dashboard administrateur** : voir tous les utilisateurs, événements et avis  

---

## **Base de données**

- 3 utilisateurs standards  
- 2 responsables  
- 1 administrateur  
- 10 événements  
- 15 avis  

---

## **Notes techniques**

- Architecture **MVC**  
- Validation des données via **Symfony Validator**  
- Messages flash pour retours utilisateurs  
- Code **commenté et structuré**
