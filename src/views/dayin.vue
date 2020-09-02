<template>
  <div>
    <print :id="this.$route.query.id" />
  </div>
</template>

<script>
import print from '@/components/print'
export default {
    components: { print }
}
</script>
