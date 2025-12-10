# Application Frama Space

Nextcloud est une plateforme qui propose beaucoup d'applications par défaut. Cette richesse peut, pour des utilisateurs débutants ou n'ayant besoin que d'une ou deux applications occasionner de la confusion, voire un abandon de Nextcloud car jugé trop complexe.

Nous avons donc développé une application permettant aux administrateurs d'une instance Nextcloud de masquer les icônes des applications dont ils ne se servent pas ou dont leur organisation ne se sert pas et ainsi de personnaliser l'interface selon leurs besoins spécifiques ou ceux de leur organisation.

## Prérequis pour exécuter l'application

- Nextcloud version 31 minimum 
- Node.js version 20 minimum 

## Installation et déploiement de l'application

1. Récupérer le projet grâce à la commande git clone
```bash
git clone https://framagit.org/framasoft/framaspace/custom-apps/framaspace.git
```

3. Installer les dépendances
```bash
npm install && composer install
```
4. Construire le Front-End
```bash
npm run dev
```

5. Activer l'application
```bash
sudo -u www-data php /var/www/nextcloud/occ app:enable framaspace
```

## Utilisation de l'application

1. Aller dans le menu Paramètres d'Administration → Administration → FramaSpace
2. Cocher les applications à masquer
3. Cliquer sur "Sauvegarder"
4. Rechargez la page