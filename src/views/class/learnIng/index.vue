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
import { updateclass } from '@/api/class'
export default {
    name: 'LearnIng',
    mixins: [classMixin],
    data() {
        return {
            qrCodesType: false,
            qrCodeSrc: '',
            btn: {
                title: '操作',
                width: '180',
                btnlist: [
                    { con: '结业', type: 'success' },
                    { con: '详情', type: 'primary' },
                    // { con: '解散', type: 'warning' },
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
            const { type, data } = v
            if (type === '结业') {
                updateclass({ status: 2, id: data.id }).then(res => {
                    console.log(res, '结业')
                })
            }
            if (type === '详情') {
                this.$router.push({
                    path: '/class/recruitStudent/info',
                    query: {
                        type: 'learning',
                        id: data.id
                    }
                })
            }
            if (type === '解散') {
                this.dissolve(data)
            }
            if (type === '二维码') {
                this.showQrCode(data)
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

