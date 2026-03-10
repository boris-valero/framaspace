<template>
	<form id="framaspace-admin-settings" @submit.prevent="save">
		<table>
			<thead>
				<tr>
					<th>{{ t('framaspace', 'Application name') }}</th>
					<th>{{ t('framaspace', 'Do you want to hide the icon?') }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="app in apps" :key="app.id">
					<td>{{ app.name }}</td>
					<td>
						<input
							v-model="app.hidden"
							type="checkbox"
							:disabled="app.protected"
							:title="app.protected ? t('framaspace', 'This application cannot be hidden') : ''" />
					</td>
				</tr>
			</tbody>
		</table>
		<button type="submit">
			{{ t('framaspace', 'Save') }}
		</button>
		<span class="save-status" :class="statusClass">{{ status }}</span>
	</form>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { t } from '@nextcloud/l10n'

const apps = ref([])
const status = ref('')
const statusClass = ref('')

onMounted(async () => {
	try {
		const response = await fetch('/apps/framaspace/api/admin/apps')
		if (!response.ok) throw new Error('Erreur serveur')
		apps.value = await response.json()
	} catch (e) {
		status.value = t('framaspace', 'Loading error')
		statusClass.value = 'error'
	}
})

const save = async () => {
	status.value = t('framaspace', 'Saving…')
	statusClass.value = ''
	try {
		const response = await fetch('/apps/framaspace/api/admin/hidden', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				requesttoken: (window.OCA && OCA.App && OCA.App.requestToken) ? OCA.App.requestToken : '',
			},
			body: JSON.stringify({
				hidden: apps.value.filter(a => a.hidden).map(a => a.id),
			}),
		})
		if (!response.ok) throw new Error('Erreur serveur')
		status.value = t('framaspace', 'Saved!')
		statusClass.value = 'success'
	} catch (e) {
		status.value = t('framaspace', 'Save error')
		statusClass.value = 'error'
	}
}
</script>

<style scoped lang="scss">
#framaspace-admin-settings {
	max-width: 700px;

	.feature-description {
		background-color: #e3f2fd;
		border: 1px solid #2196f3;
		color: #0d47a1;
		padding: 15px;
		border-radius: 4px;
		margin-bottom: 20px;

		p {
			margin: 0;
			line-height: 1.5;
		}
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin: 20px 0;
		table-layout: fixed;
	}

	th,
	td {
		padding: 15px 15px;
		text-align: left;
		border-bottom: 1px solid #eee;
		vertical-align: middle;
	}

	th:nth-child(1),
	td:nth-child(1) {
		width: 60%;
	}

	th:nth-child(2),
	td:nth-child(2) {
		width: 40%;
		text-align: center;
	}

	th {
		background-color: #f5f5f5;
		font-weight: bold;
	}

	.form-actions {
		margin: 20px 0;
	}

	.save-status {
		margin-left: 10px;
		font-weight: bold;

		&.success {
			color: #28a745;
		}

		&.error {
			color: #dc3545;
		}
	}

	.info-box {
		background-color: #d4edda;
		border: 1px solid #c3e6cb;
		color: #FFFF00;
		padding: 15px;
		border-radius: 4px;
		margin-top: 20px;
	}

	input[type="checkbox"] {
		margin-right: 8px;
	}

	label {
		cursor: pointer;
		user-select: none;
	}
}
</style>
