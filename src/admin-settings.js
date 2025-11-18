/**
 * JavaScript moderne pour les paramètres administrateur de FramaSpace
 */

import { generateUrl } from '@nextcloud/router'

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('hidden-apps-form');
    const saveStatus = document.getElementById('save-status');
    
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Récupération des applications cochées
        const checkboxes = form.querySelectorAll('input[name="hidden_apps[]"]:checked');
        const hiddenApps = Array.from(checkboxes).map(cb => cb.value);
        
        // Affichage du statut de sauvegarde
        saveStatus.textContent = 'Sauvegarde en cours...';
        saveStatus.className = 'save-status';
        
        try {
            // Envoi de la requête
            const response = await fetch(generateUrl('/apps/framaspace/admin/hidden-apps'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'requesttoken': OC.requestToken
                },
                body: `hidden_apps=${encodeURIComponent(JSON.stringify(hiddenApps))}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                saveStatus.textContent = 'Paramètres sauvegardés avec succès ! Rechargement...';
                saveStatus.className = 'save-status success';
                
                // Rafraîchissement automatique après 1 seconde pour laisser le temps de lire le message
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.error || 'Erreur lors de la sauvegarde');
            }
        } catch (error) {
            console.error('Erreur:', error);
            saveStatus.textContent = `Erreur lors de la sauvegarde : ${error.message}`;
            saveStatus.className = 'save-status error';
        }
    });
});