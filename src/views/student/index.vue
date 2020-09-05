<template>
  <!-- 学员管理 -->
  <div class="home">
    <div class="content-box">
      <div class="flsb">
        <div class="bold">查询条件</div>
      </div>
      <search @search="search" />
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">学员列表</div>
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
      top="10vh"
      title="详情"
      :visible.sync="dialogLook"
    >
      <!-- destroy-on-close -->
      <studentInfo :student-id="studentId" />
    </el-dialog>
  </div>
</template>

<script>
import tablePug from '@/components/table'
import page from '@/components/table/page'
import studentInfo from '@/components/studentInfo'
import search from './search'

import { getStudents } from '@/api/class'
export default {
    name: 'Student',
    components: { tablePug, search, page, studentInfo },
    data() {
        return {
            total: 0,
            lists: [],
            titles: [
                { name: '班级ID', data: 'class_id' },
                { name: '学员姓名', data: 'name' },
                { name: '工种', data: 'types' },
                { name: '身份证号', data: 'idcard' },
                { name: '手机号码', data: 'phonenum' },
                { name: '培训日期', data: 'active_time' },
                { name: '完成学时', data: 'period' }
            ],
            btn: {
                title: '操作',
                btnlist: [
                    { con: '详情', type: 'primary' }
                ]
            },
            dialogLook: false,
            looks: {
                title: '',
                datas: {}
            },
            searchData: {// 搜索条件
                manager_id: this.$store.getters.token,
                size: 8,
                page: 1
            },
            queryData: {}, // 查询条件
            tableloading: false, // 表格加载
            studentId: 0// 学员详情id
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.tableloading = true
            const data = { ...this.searchData, ...this.queryData }
            getStudents(data).then(res => {
                this.total = Number(res.data.count)
                for (const item of res.data.list) {
                    item.active_time = this.$parseTime(item.active_time, '{y}-{m}-{d}')
                }
                this.lists = res.data.list
                this.tableloading = false
            }).catch(() => { this.tableloading = false })
        },
        btnsave(e) {
            this.$message(e.target.innerText)
        },
        getVal({ data, type }) {
            if (type === '详情') {
                this.studentId = data.id || 90075
                this.dialogLook = true
            }
        },
        search(e) {
            this.queryData = e
            this.init()
        },
        getPageData(page) { // 页数
            this.searchData.page = page
            this.init()
        },
        pagesizes(num) { // 每页多少个并重置page为1
            this.searchData.size = num
            this.searchData.page = 1
            this.init()
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
