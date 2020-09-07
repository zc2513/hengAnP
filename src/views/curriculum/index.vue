<template>
  <!-- 课程管理 -->
  <div class="curriculum-page">
    <div class="content-box">
      <div class="flsb">
        <div class="bold">查询条件</div>
      </div>
      <search @search="search" />
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">课程列表</div>
        <div class="fontGay cursor">
          <span @click="btnsave($event)">新增课件</span>
        </div>
      </div>
      <tablePug
        v-loading="tableloading"
        element-loading-text="数据加载中"
        class="mt15"
        :btns="btn"
        :lists="lists"
        :titles="titles"
        @sendVal="getVal"
      />
      <page
        :total="total"
        :current-page="searchData.page"
        :page-size="searchData.size"
        @pagesend="getPageData"
        @pagesizes="pagesizes"
      />
    </div>
    <el-dialog
      v-if="videoDialog.type"
      :modal-append-to-body="false"
      custom-class="videoDialog-box"
      :visible.sync="videoDialog.type"
      :before-close="closeVideo"
    >
      <template #title>{{ videoDialog.name }} </template>
      <!-- <video autoplay controlsList="nodownload" controls :src="videoDialog.src" /> -->
      <div id="J_prismPlayer" class="prism-player" />
    </el-dialog>
    <el-drawer
      ref="drawer"
      v-loading="editDialogStatus"
      :visible.sync="editDialog"
      :show-close="false"
      :with-header="false"
      :before-close="handleClose"
      destroy-on-close
      direction="rtl"
      element-loading-text="加载中..."
    >
      <addFrom ref="addFrom" :edit-data="editData" @success="addRefresh" @close="editDialog=false" />
    </el-drawer>

  </div>
</template>

<script>
import tablePug from '@/components/table'
import search from './search'
import page from '@/components/table/page'
import addFrom from './add'
// eslint-disable-next-line no-unused-vars
import { selectcourse, delectecourse, detailscourse } from '@/api/curriculum'
import { player } from '@/utils/video'
export default {
    name: 'Curriculum',
    components: { tablePug, page, search, addFrom },
    data() {
        return {
            editDialog: false,
            editDialogStatus: false, // 回显加载状态
            videoDialog: {
                type: false,
                name: '',
                src: ''
            },
            editData: null,
            total: 0,
            searchData: {// 搜索条件
                manager_id: this.$store.getters.token,
                size: 8,
                page: 1
            },
            queryData: {}, // 查询条件
            lists: [],
            titles: [
                { name: '课件ID', data: 'id' },
                { name: '课件名称', data: 'title' },
                { name: '课件时长', data: 'hours' },
                { name: '讲师姓名', data: 'teacher' }
                // { name: '课件状态', data: 'state' }
            ],
            btn: {
                title: '操作',
                width: '200',
                btnlist: [
                    { con: '编辑', type: 'info' },
                    { con: '视频', type: 'primary' },
                    { con: '删除', type: 'warning', confirm: true }
                ]
            },
            tableloading: false // 表格加载
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            // 初始化页面数据
            this.tableloading = true
            const data = { ...this.searchData, ...this.queryData }
            selectcourse(data).then(res => {
                this.total = Number(res.data.total)
                this.lists = res.data.list
                this.tableloading = false
            }).catch(() => { this.tableloading = false })
        },
        addRefresh() { // 新增刷新
            this.init()
            this.editDialog = false
        },
        btnsave(e) {
            if (e.target.innerText === '新增课件') {
                this.editData = null
                this.editDialog = true
            } else {
                this.$message(e.target.innerText)
            }
        },
        getPageData(page) { // 页数
            this.searchData.page = page
            this.init()
        },
        pagesizes(num) { // 每页多少个并重置page为1
            this.searchData.size = num
            this.searchData.page = 1
            this.init()
        },
        getVal({ data, type }) {
            if (type === '编辑') {
                this.editDialog = true
                this.editDialogStatus = true
                detailscourse({ id: data.id }).then(res => {
                    res.data['id'] = data.id
                    this.editData = res.data
                    this.editDialogStatus = false
                })
            }
            if (type === '视频') {
                if (data.url) {
                    this.videoDialog = {
                        type: true,
                        name: data.title,
                        src: data.url
                    }
                    this.$nextTick(() => {
                        player({ source: data.url, cover: data.logo }, 'J_prismPlayer')
                    })
                } else {
                    this.$message('暂无视频')
                }
            }
            if (type === '删除') {
                this.$confirm('是否删除当前课件?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delectecourse({ id: data.id }).then(res => {
                        this.$message.success(res.msg)
                        this.init()
                    })
                }).catch(() => {
                    this.$message.info('已取消删除')
                })
                return
            }
        },
        search(e) {
            this.queryData = e
            this.init()
        },
        handleClose(done) {
            if (Object.values(this.$refs.addFrom.formData).some(e => !!e)) {
                this.$confirm('表单存在尚未编辑完成的信息确认关闭吗?').then(_ => {
                    done()
                }).catch(_ => {})
            } else {
                done()
            }
        },
        closeVideo(done) {
            this.videoDialog.src = ''
            done()
        },
        cancelForm() {
            this.loading = false
            this.dialog = false
            clearTimeout(this.timer)
        }
    }
}
</script>

<style lang="scss" scoped>
.curriculum-page{
    /deep/.videoDialog-box{
        .el-table__expanded-cell[class*=cell]{
            padding: 0;
        }
        .el-dialog__body{
            padding: 0 !important;
        }
    }
    video{
        width: 100%;
        max-height: 400px;
    }
}
</style>
