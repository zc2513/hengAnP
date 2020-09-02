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
        :current-page="searchData.page"
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
import { selectclass, detailscalss, Qrcode, delclass } from '@/api/class'
export default {
    name: 'RecruitStudent',
    mixins: [classMixin],
    data() {
        return {
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
            }
            if (type === '详情') {
                this.$router.push({
                    path: '/class/recruitStudent/info',
                    query: {
                        type: 'recruitStudent',
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
        },
        handleCloseImg(done) { // 关闭二维码预览弹窗
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
