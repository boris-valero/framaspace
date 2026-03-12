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
						<NcCheckboxRadioSwitch
							v-model="app.hidden"
							:disabled="app.protected"
							:title="app.protected ? t('framaspace', 'This application cannot be hidden') : ''" />
					</td>
				</tr>
			</tbody>
		</table>
		<div class="form-actions">
			<NcButton type="primary" native-type="submit">
				{{ t('framaspace', 'Save') }}
			</NcButton>
		</div>
	</form>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'
import { NcCheckboxRadioSwitch, NcButton } from '@nextcloud/vue'
import '@nextcloud/dialogs/style.css'

const apps = ref([])

onMounted(async () => {
	try {
		const response = await axios.get('/apps/framaspace/api/admin/apps')
		apps.value = response.data
	} catch (e) {
		showError(t('framaspace', 'Loading error'))
	}
})

const save = async () => {
	try {
		await axios.post('/apps/framaspace/api/admin/hidden', {
			hidden: apps.value.filter(a => a.hidden).map(a => a.id),
		})
		showSuccess(t('framaspace', 'Saved!'))
	} catch (e) {
		showError(t('framaspace', 'Save error'))
	}
}
</script>

<style scoped lang="scss">
#framaspace-admin-settings {
	max-width: 700px;

	table {
		width: 100%;
		border-collapse: collapse;
		margin: calc(var(--default-grid-baseline) * 5) 0;
		table-layout: fixed;
	}

	th,
	td {
		padding: calc(var(--default-grid-baseline) * 4);
		text-align: left;
		border-bottom: var(--border-width-input) solid var(--color-border);
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
		background-color: var(--color-background-hover);
		font-weight: bold;
	}

	.form-actions {
		margin: calc(var(--default-grid-baseline) * 5) 0;
	}

	label {
		cursor: pointer;
		user-select: none;
	}
}
</style>
