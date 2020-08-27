<template>
  <!-- 学习中 -->
  <div class="learnIng-page">
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
    <el-dialog
      :modal-append-to-bod="false"
      :show-close="false"
      destroy-on-close
      top="30vh"
      :visible.sync="qrCodesType"
    >
      <div class="qrCodeImg">
        <i slot="title" class="el-icon-error cursor" @click="qrCodesType = false;qrCodeSrc=''" />
        <img :src="qrCodeSrc">
      </div>
    </el-dialog>
  </div>
</template>

<script>
import classMixin from './../class'
export default {
    name: 'LearnIng',
    mixins: [classMixin],
    data() {
        return {
            qrCodesType: false,
            qrCodeSrc: '',
            btn: {
                title: '操作',
                width: '270',
                btnlist: [
                    { con: '结业', type: 'success' },
                    { con: '详情', type: 'primary' },
                    { con: '解散', type: 'warning' },
                    { con: '二维码', type: 'primary', plain: true }
                ]
            }
        }
    },
    methods: {
        btnsave(e) {
            this.$message(e.target.innerText)
        },
        getBtn(v) {
            console.log(v)
            if (v.type === '详情') {
                return this.$router.push('/class/recruitStudent/info?type=learning')
            }
            if (v.type === '二维码') {
                this.qrCodesType = true
                this.qrCodeSrc = require('@/assets/qrcode.png')
                return
            }
            if (v.type === '解散') {
                return this.$message(v.type)
            }
            if (v.type === '结业') {
                return this.$message(v.type)
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.learnIng-page{
    @import '@/styles/qrCode.scss';
}
</style>

