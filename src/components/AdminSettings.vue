<template>
  <form @submit.prevent="save">
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
            <input type="checkbox" v-model="app.hidden" :disabled="app.protected" :title="app.protected ? t('framaspace', 'This application cannot be hidden') : ''">
          </td>
        </tr>
      </tbody>
    </table>
    <button type="submit">{{ t('framaspace', 'Save') }}</button>
    <span class="save-status" :class="statusClass">{{ status }}</span>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { translate as t } from '@nextcloud/l10n'

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
    statusClass.value = "error"
  }
})

const save = async () => {
  status.value = t('framaspace', 'Saving…')
  statusClass.value = ""
  try {
    const response = await fetch('/apps/framaspace/api/admin/hidden', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'requesttoken': (window.OCA && OCA.App && OCA.App.requestToken) ? OCA.App.requestToken : ""
      },
      body: JSON.stringify({
        hidden: apps.value.filter(a => a.hidden).map(a => a.id)
      })
    })
    if (!response.ok) throw new Error('Erreur serveur')
    status.value = t('framaspace', 'Saved!')
    statusClass.value = "success"
  } catch (e) {
    status.value = t('framaspace', 'Save error')
    statusClass.value = "error"
  }
}
</script>

<style scoped>
.save-status.success {
  color: green;
}

.save-status.error {
  color: red;
}
</style>
