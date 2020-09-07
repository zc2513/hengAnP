<template>
  <div>
    <div id="J_prismPlayer" class="prism-player" />
  </div>
</template>

<script>
export default {
    mounted() {
        this.init()
    },
    methods: {
        init() {
            // eslint-disable-next-line no-undef
            new Aliplayer({
                id: 'J_prismPlayer',
                width: '100%',
                autoplay: true,
                source: 'http://video.safetymf.com/cf23e5b8f81f4f5ab9721440017eacf7/d10385e34782415c3e773d841c71592a-ld-nbv1.m3u8'
            }, function(player) {
                console.log('播放器创建好了。')
            })
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
