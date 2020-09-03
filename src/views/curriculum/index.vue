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
      :modal-append-to-body="false"
      custom-class="videoDialog-box"
      :visible.sync="videoDialog.type"
      :before-close="closeVideo"
    >
      <template #title>{{ videoDialog.name }} </template>
      <video autoplay controlsList="nodownload" controls :src="videoDialog.src" />
    </el-dialog>
    <el-drawer
      ref="drawer"
      :visible.sync="editDialog"
      :show-close="false"
      :with-header="false"
      :before-close="handleClose"
      destroy-on-close
      direction="rtl"
    >
      <addFrom :edit-data="editData" />
    </el-drawer>

  </div>
</template>

<script>
import tablePug from '@/components/table'
import search from './search'
import page from '@/components/table/page'
import addFrom from './add'
// eslint-disable-next-line no-unused-vars
import { selectcourse, delectecourse } from '@/api/curriculum'
export default {
    name: 'Curriculum',
    components: { tablePug, page, search, addFrom },
    data() {
        return {
            editDialog: false,
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
            console.log(data)
            selectcourse(data).then(res => {
                console.log(res, 4444)
                this.total = res.data.total
                this.lists = res.data.list
                this.tableloading = false
            }).catch(() => { this.tableloading = false })
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
                this.$message(`${type}---待处理`)
                this.editData = {
                    name: '张三看上了王老五',
                    type: '双皮奶',
                    teacher: '麻子',
                    img: require('@/assets/a.jpg'),
                    des: '打飞机可拉伸副科级大风基多拉荆防颗粒大姐夫两块的建安费枯鲁杜鹃阿夫林卡到数据'
                }
                this.editDialog = true
                return
            }
            if (type === '视频') {
                if (data.url) {
                    this.videoDialog = {
                        type: true,
                        name: data.title,
                        src: data.url
                    }
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
                    this.$message('删除---待处理逻辑')
                }).catch(() => {
                    this.$message.info('已取消删除')
                })
                return
            }
        },
        search(e) {
            console.log('搜索', e)
            this.queryData = e
            this.init()
        },
        handleClose(done) {
            console.log(this.editDialog)
            this.$confirm('确定要提交表单吗？').then(_ => {
                this.editDialog = true
                this.timer = setTimeout(() => {
                    done()
                    // 动画关闭需要一定的时间
                    setTimeout(() => {
                        this.editDialog = false
                    }, 400)
                }, 2000)
            }).catch(_ => {})
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
