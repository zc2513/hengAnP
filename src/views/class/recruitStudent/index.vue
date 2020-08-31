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
      <page
        :total="total"
        :page-size="searchData.size"
        @pagesend="getPageData"
        @pagesizes="pagesizes"
      />
      <el-dialog
        :modal-append-to-bod="false"
        :show-close="false"
        custom-class="qrCode-box"
        destroy-on-close
        top="30vh"
        :visible.sync="qrCodesType"
        :before-close="handleCloseImg"
      >
        <div v-loading="imgLoading" element-loading-text="加载中..." class="qrCodeImg">
          <i slot="title" class="el-icon-error cursor" @click="qrCodesType = false;qrCodeSrc=''" />
          <img :src="qrCodeSrc" @load="imgLoading=false">
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import classMixin from './../class'
// eslint-disable-next-line no-unused-vars
import { selectclass, detailscalss, Qrcode } from '@/api/class'
export default {
    name: 'RecruitStudent',
    mixins: [classMixin],
    data() {
        return {
            qrCodesType: false,
            imgLoading: true,
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
            const { type, data } = v
            if (type === '编辑') {
                const query = {
                    type: 'edit',
                    id: data.id
                }
                this.$router.push({
                    path: '/class/createClass',
                    query
                })
                return
            }
            if (type === '详情') {
                return this.$router.push('/class/recruitStudent/info?type=recruitStudent')
            }
            if (type === '解散') {
                return this.$message(type)
            }
            if (type === '二维码') {
                this.qrCodesType = true
                this.qrCodeSrc = `${process.env.VUE_APP_BASE_API}/index.php/Master/Qrcode/index?classid=${data.classid}&id=${this.$store.getters.token}`
            }
        },
        handleCloseImg(done) {
            this.qrCodeSrc = ''
            this.imgLoading = true
            done()
        }
    }
}
</script>
<style lang="scss" scoped>
.recruitStudentIndex{
    @import '@/styles/qrCode.scss';
}
</style>
