<template>
  <form @submit.prevent="save">
    <table>
      <thead>
        <tr>
          <th>Nom de l'application</th>
          <th>Masquer ?</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="app in apps" :key="app.id">
          <td>{{ app.name }}</td>
          <td>
            <input type="checkbox" v-model="app.hidden" :disabled="app.protected">
          </td>
        </tr>
      </tbody>
    </table>
    <button type="submit">Sauvegarder</button>
    <span class="save-status" :class="statusClass">{{ status }}</span>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const apps = ref([])
const status = ref('')
const statusClass = ref('')

onMounted(async () => {
  try {
    const response = await fetch('/apps/framaspace/api/admin/apps')
    if (!response.ok) throw new Error('Erreur serveur')
    apps.value = await response.json()
  } catch (e) {
    status.value = "Erreur chargement"
    statusClass.value = "error"
  }
})

const save = async () => {
  status.value = "Enregistrement…"
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
    status.value = "Sauvegardé !"
    statusClass.value = "success"
  } catch (e) {
    status.value = "Erreur sauvegarde"
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
