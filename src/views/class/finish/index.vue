<template>
  <div class="home">
    <div class="content-box">
      <div class="flsb">
        <div class="bold">查询条件</div>
      </div>
      <search @search="search" />
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">班级列表</div>
      </div>
      <tablePug
        v-loading="tableloading"
        element-loading-text="数据加载中"
        class="mt15"
        :btns="btn"
        :lists="lists"
        :titles="titles"
        @sendVal="getBtn"
      />
      <page
        :total="total"
        :page-size="searchData.size"
        @pagesend="getPageData"
        @pagesizes="pagesizes"
      />
    </div>
    <!-- <print v-if="printId" /> -->
  </div>
</template>

<script>
import classMixin from './../class'
// import print from '@/components/print'
export default {
    name: 'Home',
    // components: { print },
    mixins: [classMixin],
    data() {
        return {
            // printId: false,
            btn: {
                title: '操作',
                width: '160',
                btnlist: [
                    { con: '详情', type: 'primary' },
                    { con: '打印课时', type: 'success', plain: true }
                ]
            }
        }
    },
    methods: {
        btnsave(e) {
            this.$message(e.target.innerText)
        },
        getBtn(v) {
            if (v.type === '详情') {
                this.$router.push('/class/recruitStudent/info?type=finish')
            } else {
                const routeData = this.$router.resolve({ path: '/dayin', query: { id: 1 }})
                window.open(routeData.href, '_blank')
                // this.printId = 1
                this.$message(v.type)
            }
        }
    }
}
</script>
