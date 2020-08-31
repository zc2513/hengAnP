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
      >
        <div v-loading="imgLoading" element-loading-text="加载中..." class="qrCodeImg">
          <i slot="title" class="el-icon-error cursor" @click="qrCodesType = false;qrCodeSrc=''" />
          <img :src="qrCodeSrc">
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
        // init(params) {
        //     selectclass(params).then(res => {
        //         console.log(res, 4444)
        //         // const { data } = res
        //         // this.total = data.count
        //         // for (const item of data.data) {
        //         //     item.startclass = this.$parseTime(item.startclass)
        //         //     item.endclass = this.$parseTime(item.endclass)
        //         // }
        //         // this.lists = data.data
        //     })
        // },
        getBtn(v) {
            const { type, data } = v
            if (type === '编辑') {
                const query = {
                    type: 'edit',
                    id: data.classid
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
                Qrcode({ manager_id: this.$store.getters.token, classid: data.classid }).then(res => {
                    this.imgLoading = false
                    this.qrCodeSrc = window.URL.createObjectURL(res.data)
                }).catch(() => {
                    this.qrCodesType = false
                })
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
