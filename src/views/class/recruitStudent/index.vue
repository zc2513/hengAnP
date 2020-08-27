<template>
  <!-- 招收中 -->
  <div class="recruitStudentIndex">
    <router-view />
    <div class="content-box">
      <div class="flsb">
        <div class="bold">查询条件</div>
      </div>
      <search @search="search" />
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">班级列表</div>
        <div>
          <el-button type="primary" round class="cursor" @click="$router.push('/class/createClass')">创建班级</el-button>
        </div>
      </div>
      <tablePug class="mt15" :btns="btn" :lists="lists" :titles="titles" @sendVal="getBtn" />
      <page :total="total" :page-size="pageSize" @pagesend="getPageData" />
      <el-dialog
        :modal-append-to-bod="false"
        :show-close="false"
        custom-class="qrCode-box"
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
  </div>
</template>

<script>
import classMixin from './../class'
export default {
    name: 'RecruitStudent',
    mixins: [classMixin],
    data() {
        return {
            qrCodesType: false,
            qrCodeSrc: '',
            btn: {
                title: '操作',
                width: '270',
                btnlist: [
                    { con: '编辑', type: 'warning' },
                    { con: '详情', type: 'primary' },
                    { con: '解散', type: 'warning' },
                    { con: '二维码', type: 'primary', plain: true }
                ]
            }
        }
    },
    created() {
        this.titles = this.titles.filter(e => e.name !== '进度')
    },
    methods: {
        getBtn(v) {
            if (v.type === '编辑') {
                return this.$router.push('/class/createClass?type=edit')
            }
            if (v.type === '详情') {
                return this.$router.push('/class/recruitStudent/info?type=recruitStudent')
            }
            if (v.type === '解散') {
                return this.$message(v.type)
            }
            if (v.type === '二维码') {
                this.qrCodesType = true
                this.qrCodeSrc = require('@/assets/qrcode.png')
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.recruitStudentIndex{
    @import '@/styles/qrCode.scss';
}
</style>
