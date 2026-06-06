<template>
  <router-view />
  <Alert />
</template>

<script>
import Alert from '@/components/Alert.vue' 
export default {
  components: {
    Alert,
  },
}
</script>
