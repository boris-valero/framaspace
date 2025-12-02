# Application Frama Space

## Problème résolu par l'application
Nextcloud est une plateforme qui propose beaucoup d'applications par défaut. Pour des utilisateurs débutants ou des structures qui ont des besoins simples, cette richesse peut devenir un handicap et occasionner de la confusion, voire un abandon de la plateforme de collaboration Nextcloud par les utilisateurs les plus éloignés du numérique.

Nous avons donc développé une application Nextcloud permettant aux administrateurs de Nextcloud de masquer les icônes des applications dont ils ne se servent pas ou dont leur organisation ne se sert pas et ainsi de personnaliser l'interface selon leurs besoins spécifiques ou ceux de leur organisation.

## Architecture technique de l'application

Notre application utilise l'architecture MVC (Modèle-Vue-Contrôleur) :

-  ### **Contrôleur**
Il gère les interactions utilisateur et orchestre les opérations. Quand l'administrateur clique sur "Sauvegarder", le contrôleur récupère les données du formulaire, les valide, appelle le modèle pour les stocker, puis renvoie une réponse au format JSON.

- ### **Modèle** 
Il gère l'accès aux données. Il empêche de masquer les applications essentielles ("Fichiers" et "Activité"), valide les identifiants d'applications et stocke la configuration JSON dans la table `oc_appconfig` de la base de données Nextcloud via `IConfig`.

- ### **Vue**
Elle gère l'affichage de l'interface d'administration avec le tableau et les cases à cocher mais ne gère pas le masquage effectif des applications.

Le masquage effectif des applications est géré par un système séparé via un Event Listener qui s'active sur BeforeTemplateRenderedEvent : Il lit la configuration, génère du CSS dynamique, et l'injecte automatiquement sur toutes les pages pour masquer les applications sélectionnées.

## Prérequis pour exécuter l'application

- Nextcloud version 31 minimum — https://download.nextcloud.com/server/releases/
- Node.js version 20 minimum — https://nodejs.org/download/release/latest-v20.x/
- PHP version 8.1 minimum
- Serveur web : Apache ou Nginx
- Base de données : MySQL, PostgreSQL ou SQLite

## Installation et déploiement de l'application

1. Ajuster les permissions grâce aux listes de contrôle d'accès (ACL)
```bash
sudo setfacl -Rm u:$USER:rwx /var/www/nextcloud/apps/
sudo setfacl -Rm d:u:$USER:rwx /var/www/nextcloud/apps/
```

2. Récupérer le projet grâce à la commande git clone
```bash
cd /var/www/nextcloud/apps/
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
sudo -u www-data php /var/www/nextcloud/occ maintenance:mode --on && sudo -u www-data php /var/www/nextcloud/occ app:enable framaspace && sudo -u www-data php /var/www/nextcloud/occ maintenance:mode --off
```

## Utilisation de l'application

1. Aller dans le menu Paramètres d'Administration → Administration → FramaSpace
2. Cocher les applications à masquer
3. Cliquer sur "Sauvegarder"
4. Rechargez la page
