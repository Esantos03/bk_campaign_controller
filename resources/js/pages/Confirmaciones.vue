<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
        <p class="mt-4 text-gray-600">Cargando actividades...</p>
      </div>

      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <div v-else>
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <h1 class="text-3xl font-bold text-gray-900">
            Hola {{ cliente.nombre }} {{ cliente.apellido }}
          </h1>
          <p class="mt-2 text-gray-600">
            Tienes {{ actividades.length }} actividad(es) pendiente(s) de confirmar
          </p>
        </div>

        <div class="space-y-4">
          <div
            v-for="actividad in actividades"
            :key="actividad.id"
            class="bg-white rounded-lg shadow-sm p-6 border border-gray-200"
          >
            <div class="flex justify-between items-start mb-4">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ actividad.titulo }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ actividad.campana }}</p>
              </div>
              <span
                v-if="actividad.confirmado !== null"
                :class="[
                  'px-3 py-1 rounded-full text-sm font-medium',
                  actividad.confirmado
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ actividad.confirmado ? 'Confirmado' : 'Rechazado' }}
              </span>
            </div>

            <p v-if="actividad.descripcion" class="text-gray-700 mb-4">
              {{ actividad.descripcion }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(actividad.fecha_actividad) }}
              </div>
              <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ actividad.hora_actividad }}
              </div>
              <div class="flex items-center text-gray-600 md:col-span-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ actividad.lugar }}
              </div>
              <div v-if="actividad.cupo_maximo" class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Cupo máximo: {{ actividad.cupo_maximo }}
              </div>
            </div>

            <div v-if="actividad.confirmado === null" class="flex gap-3">
              <button
                @click="confirmarActividad(actividad, true)"
                :disabled="actividad.procesando"
                class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-lg transition-colors"
              >
                {{ actividad.procesando ? 'Procesando...' : 'Confirmar Asistencia' }}
              </button>
              <button
                @click="confirmarActividad(actividad, false)"
                :disabled="actividad.procesando"
                class="flex-1 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-lg transition-colors"
              >
                {{ actividad.procesando ? 'Procesando...' : 'Rechazar' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps<{
  hash: string
}>()

const loading = ref(true)
const error = ref<string | null>(null)
const cliente = ref({ nombre: '', apellido: '' })
const actividades = ref<any[]>([])

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const cargarActividades = async () => {
  try {
    loading.value = true
    
    // Aquí necesitarías un endpoint que convierta el hash al teléfono
    // Por ahora usaremos el hash directamente como teléfono para la demo
    const response = await axios.get(`/api/v1/cliente/${props.hash}/actividades-pendientes`)
    
    if (response.data.success) {
      cliente.value = response.data.cliente
      actividades.value = response.data.actividades.map((act: any) => ({
        ...act,
        confirmado: null,
        procesando: false
      }))
    } else {
      error.value = response.data.message
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Error al cargar las actividades'
  } finally {
    loading.value = false
  }
}

const confirmarActividad = async (actividad: any, confirmar: boolean) => {
  try {
    actividad.procesando = true
    
    const response = await axios.post('/api/v1/actividad/confirmar', {
      token: actividad.token,
      accion: confirmar ? 'confirmar' : 'rechazar'
    })
    
    if (response.data.success) {
      actividad.confirmado = confirmar
      actividad.procesando = false
    } else {
      alert(response.data.message)
      actividad.procesando = false
    }
  } catch (err: any) {
    alert(err.response?.data?.message || 'Error al procesar la confirmación')
    actividad.procesando = false
  }
}

onMounted(() => {
  cargarActividades()
})
</script>
