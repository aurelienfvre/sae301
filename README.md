#### sae301

#  pour la connexion et inscription
#### il faut créer un dossier data dans var
#### puis il faut créer un fichier users.json ou il se créer tout seul si la fonction fonctionne
#### puis il faut faire chmod 644 le_chemin_du_clone/sae301/var/data/users.json
#### puis vous faites un chmod 755 le_chemin_du_clone/sae301/sae301/var/data/
#### puis chown www-data:www-data le_chemin_du_clone/sae301/var/data/users.json
#### et service apache2 restart
#  pour la Modification du profil et l'ajout d'une photo
#### dans public il faut créer un dossier uploads puis dedans un dossier photos si cela c'est pas déjà fait
#### puis il faut faire chmod 775 le_chemin_du_clone/sae301/public/uploads/photos
#### puis chown -R www-data:www-data le_chemin_du_clone/sae301/public/uploads/photos
#### et service apache2 restart
#  pour l'ajout des cartes
#### puis il faut créer un fichier cards.json
#### puis il faut faire chmod 644 le_chemin_du_clone/sae301/var/data/cards.json
#### puis chown www-data:www-data le_chemin_du_clone/sae301/var/data/cards.json
#### puis chmod 755 le_chemin_du_clone/sae301/var/data si c'est pas déjà fait
#### et service apache2 restart


# MCD (Modèle Conceptuel de Données)

1. **Utilisateur**
   - Prénom
   - Nom
   - Email
   - Mot de passe
   - Semestre
   - Groupe
   - Photo de profil (optionnel)

2. **Carte**
   - Titre de la carte (Nom de la matière)
   - Détails de la carte
   - Liste des rendus
   - Lieu de rendu (par exemple, Moodle)
   - Date
   - Semestre
   - Groupe
   - Statut (pour la to-do list)

3. **Événement**
   - Date
   - Titre
   - Détails (liés aux cartes)

### Relations

- **Utilisateur <-> Carte** : Un utilisateur peut ajouter/modifier des cartes (relation optionnelle).
- **Utilisateur <-> Événement** : Les événements dans le calendrier sont liés aux cartes ajoutées par les utilisateurs (relation optionnelle).

# MLD (Modèle Logique de Données)

1. **Utilisateur**
   - **ID** INT (Clé Primaire)
   - Prénom VARCHAR
   - Nom VARCHAR
   - Email VARCHAR (Unique)
   - MotDePasse VARCHAR
   - Semestre VARCHAR
   - Groupe VARCHAR
   - PhotoDeProfil VARCHAR (optionnel)

2. **Carte**
   - **ID** INT (Clé Primaire)
   - Titre VARCHAR
   - Details VARCHAR
   - ListeRendus VARCHAR
   - LieuRendu VARCHAR
   - Date DATE
   - Semestre VARCHAR
   - Groupe VARCHAR
   - Statut BOOLEAN
   - **UtilisateurID** INT (Clé Étrangère, optionnel)

3. **Evenement**
   - **ID** INT (Clé Primaire)
   - Date DATE
   - Titre VARCHAR
   - Details VARCHAR
   - **UtilisateurID** INT (Clé Étrangère, optionnel)

