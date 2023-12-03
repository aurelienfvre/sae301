#### sae301

#  pour la connexion et inscription
#### il faut créer un dossier data dans var
#### puis il faut créer un fichier users.json ou il se créer tout seul si la fonction fonctionne
#### puis il faut faire chmod 775 sae301/var/data
#### puis chown www-data:www-data sae301/var/data
#### et service apache2 restart
#  pour la Modification du profil et l'ajout d'une photo
#### dans public il faut créer un dossier uploads puis dedans un dossier photos si cela c'est pas déjà fait
#### puis il faut faire chmod 775 sae301/public/uploads/photos
#### puis chown -R www-data:www-data sae301/public/uploads/photos
#### et service apache2 restart
#  pour l'ajout des cartes
#### puis il faut créer un fichier cards.json
#### puis il faut faire chmod 644 sae301/var/data/cards.json
#### puis chown www-data:www-data sae301/var/data/cards.json
#### puis chmod 755 sae301/var/data si c'est pas déjà fait
#### et service apache2 restart
