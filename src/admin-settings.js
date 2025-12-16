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

	function applyHiddenApps(hiddenApps) {
		const existingStyle = document.getElementById('framaspace-hidden-apps')
		if (existingStyle) {
			existingStyle.remove()
		}

		if (hiddenApps.length === 0) return

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

		const checkboxes = form.querySelectorAll('input[name="hidden_apps[]"]:checked')
		const hiddenApps = Array.from(checkboxes).map(cb => cb.value)

		saveStatus.textContent = 'Sauvegarde en cours...'
		saveStatus.className = 'save-status'

		try {
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
				applyHiddenApps(hiddenApps)

				saveStatus.textContent = 'Paramètres sauvegardés avec succès ! Veuillez recharger la page pour que les modifications prennent effet.'
				saveStatus.className = 'save-status success'

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

	const initialHiddenApps = []
	form.querySelectorAll('input[name="hidden_apps[]"]:checked').forEach(cb => {
		initialHiddenApps.push(cb.value)
	})
	if (initialHiddenApps.length > 0) {
		applyHiddenApps(initialHiddenApps)
	}
})
