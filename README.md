# FramaSpace

## 📌 C'est quoi ?

**FramaSpace** est une application pour Nextcloud qui permet aux administrateurs d'une instance Nexcloud de masquer les applications non utilisées dans le but de simplifier l'expérience pour tous les utilisateurs de cette instance. FramaSpace permet de personnaliser l'interface selon vos besoins réels.

En effet, Nextcloud propose de nombreuses applications par défaut (mail, calendrier, photos, notes, etc.). Pour des utilisateurs débutants ou des structures avec des besoins simples, cette richesse peut devenir un problème :

- 😕 **Confusion** : trop d'icônes créent des difficultés à trouver ce dont on a besoin
- 😢 **Abandon** : certains utilisateurs renoncent car Nextcloud c'est "trop compliqué"

Par exemple, votre association utilise uniquement les applications Fichiers et Activités dans Nextcloud, mais l'interface affiche aussi Photos, Deck, Forms, Contacts, Talk, etc. Avec l'application FramaSpace, vous pouvez masquer toutes les applications dont vous n'avez pas besoin.

## 🚀 Installation

### Pour les utilisateurs standard

**Cette application est en cours de développement et pas encore disponible dans le Store Nextcloud.**  

Elle sera bientôt installable depuis l'App Store de Nextcloud et instalée et activée par défaut dans tous les espaces Framaspace, le cloud convivial pour collectifs solidaires proposé par Framasoft.

### Pour les utilisateurs avancés

Si vous souhaitez tester ou installer manuellement cette application :

1. **Cloner le dépôt**
   ```bash
   git clone https://framagit.org/framasoft/framaspace/custom-apps/framaspace.git
   ```

2. **Installer les dépendances et compiler les fichiers**
   ```bash
   cd framaspace
   npm install
   npm run build
   ```

3. **Déplacer l'application dans le dossier apps de Nextcloud et modifier les permissions**
   ```bash
   sudo mv framaspace /var/www/nextcloud/apps/
   sudo chown -R www-data:www-data /var/www/nextcloud/apps/framaspace
   ```

4. **Activer l'application**
   ```bash
   sudo -u www-data php /var/www/nextcloud/occ app:enable framaspace
   ```

## ✨ Comment ça marche ?

1. Vous êtes administrateur de votre instance Nextcloud
2. Vous allez dans Paramètres (icône roue crantée en haut à droite)
3. Dans le menu de gauche, section Administration, cliquez sur "FramaSpace"
4. Vous voyez un tableau avec toutes vos applications
5. Cochez les applications que vous souhaitez masquer
6. Cliquez sur "Sauvegarder"
7. Rechargez la page
8. C'est fait ! Les applications masquées n'apparaissent plus dans le menu. Vous pouvez à tout moment masquer d'autres applications en les cochant et les réafficher en les décochant.

**Important** : Les applications Fichiers et Activité ne peuvent pas être masquées (elles sont jugées essentielles au fonctionnement de Nextcloud).