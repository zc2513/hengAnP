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
      <tablePug class="mt15" :btns="btn" :lists="lists" :titles="titles" @sendVal="getBtn" />
      <page :total="total" :page-size="pageSize" @pagesend="getPageData" />
    </div>
  </div>
</template>

<script>
import classMixin from './../class'
export default {
    name: 'Home',
    mixins: [classMixin],
    data() {
        return {
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
                this.$message(v.type)
            }
        }
    }
}
</script>
