/**
 * JavaScript moderne pour les paramètres administrateur de FramaSpace
 */

import { generateUrl } from '@nextcloud/router'

document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('hidden-apps-form')
	const saveStatus = document.getElementById('save-status')

	if (!form) {
		return
	}

	// Fonction pour appliquer immédiatement le masquage
	function applyHiddenApps(hiddenApps) {
		// Supprimer les anciens styles
		const existingStyle = document.getElementById('framaspace-hidden-apps')
		if (existingStyle) {
			existingStyle.remove()
		}

		if (hiddenApps.length === 0) return

		// Créer une nouvelle feuille de style
		const style = document.createElement('style')
		style.id = 'framaspace-hidden-apps'

		let cssRules = ''
		hiddenApps.forEach(appId => {
			cssRules += `
                /* Masquer ${appId} dans tous les menus */
                #appmenu li[data-id="${appId}"],
                .header-appsmenu li[data-id="${appId}"],
                .apps-menu .app-entry[data-app="${appId}"],
                .app-grid .app-entry[data-app="${appId}"],
                .app-menu .app-entry[data-app="${appId}"],
                [data-app-id="${appId}"],
                [data-app="${appId}"],
                a[href*="/apps/${appId}/"],
                a[href*="index.php/apps/${appId}"],
                a[href$="apps/${appId}"],
                #header .menutoggle + .menu li[data-id="${appId}"],
                .app-navigation li[data-id="${appId}"] {
                    display: none !important;
                }
            `
		})

		style.textContent = cssRules
		document.head.appendChild(style)
	}

	form.addEventListener('submit', async function(e) {
		e.preventDefault()

		// Récupération des applications cochées
		const checkboxes = form.querySelectorAll('input[name="hidden_apps[]"]:checked')
		const hiddenApps = Array.from(checkboxes).map(cb => cb.value)

		// Affichage du statut de sauvegarde
		saveStatus.textContent = 'Sauvegarde en cours...'
		saveStatus.className = 'save-status'

		try {
			// Envoi de la requête
			const response = await fetch(generateUrl('/apps/framaspace/admin/hidden-apps'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
					requesttoken: OC.requestToken,
				},
				body: `hidden_apps=${encodeURIComponent(JSON.stringify(hiddenApps))}`,
			})

			const data = await response.json()

			if (data.success) {
				// Appliquer immédiatement le masquage
				applyHiddenApps(hiddenApps)

				saveStatus.textContent = 'Paramètres sauvegardés avec succès ! Veuillez recharger la page pour que les modifications prennent effet.'
				saveStatus.className = 'save-status success'

				// Forcer le rechargement de la feuille de style externe après 2 secondes
				setTimeout(() => {
					const link = document.querySelector('link[href*="framaspace/css/hidden-apps"]')
					if (link) {
						const newLink = link.cloneNode()
						newLink.href = link.href + '?t=' + Date.now()
						link.parentNode.insertBefore(newLink, link.nextSibling)
						link.remove()
					}
				}, 2000)
			} else {
				throw new Error(data.error || 'Erreur lors de la sauvegarde')
			}
		} catch (error) {
			saveStatus.textContent = `Erreur lors de la sauvegarde : ${error.message}`
			saveStatus.className = 'save-status error'
		}
	})

	// Appliquer le masquage au chargement initial de la page
	const initialHiddenApps = []
	form.querySelectorAll('input[name="hidden_apps[]"]:checked').forEach(cb => {
		initialHiddenApps.push(cb.value)
	})
	if (initialHiddenApps.length > 0) {
		applyHiddenApps(initialHiddenApps)
	}
})
