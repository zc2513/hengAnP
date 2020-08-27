<template>
  <section class="app-main" style="background:#f2f2f2;">
    <transition name="fade-transform" mode="out-in">
      <keep-alive :include="cachedViews">
        <router-view :key="key" style="padding:10px;" />
      </keep-alive>
    </transition>
    <footer v-if="false" class="flcc">
      <img src="@/assets/login/qrcode.png" width="75" height="75">
      <p class="pl10"> 陕ICP备18001791号-1      版权所有©西安华数恒安信息技术服务有限责任公司      All Rights Reserved</p>
    </footer>
  </section>
</template>

<script>
export default {
    name: 'AppMain',
    computed: {
        cachedViews() {
            console.log('缓存', this.$store.state.tagsView.cachedViews)
            return this.$store.state.tagsView.cachedViews
        },
        key() {
            return this.$route.path
        }
    }
}
</script>

<style lang="scss" scoped>
.app-main {
  /*50 = navbar  */
  min-height: calc(100vh - 50px);
  width: 100%;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  footer{
      color: #999;
      background-color: #e2e2e2;
  }
}
.fixed-header+.app-main {
  padding-top: 50px;
}
.hasTagsView {
  .app-main {
    /* 84 = navbar + tags-view = 50 + 34 */
    min-height: calc(100vh - 84px);
  }
  .fixed-header+.app-main {
    padding-top: 84px;
  }
}
</style>

<style lang="scss">
// 修复el-dialog fix样式
.el-popup-parent--hidden {
  .fixed-header {
    padding-right: 15px;
  }
}
</style>
