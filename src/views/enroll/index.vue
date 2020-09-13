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
        <div class="bold">报名列表</div>
        <div>
          <el-button type="warning" size="mini" round class="cursor" @click="delAll">批量删除</el-button>
          <el-button type="info" size="mini" round class="cursor" @click="btnsave">导出学员</el-button>
        </div>
      </div>
      <tablePug
        v-loading="tableloading"
        type
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
  </div>
</template>

<script>
import tablePug from '@/components/table'
import page from '@/components/table/page'
import search from './search'

import { getStudents } from '@/api/student'
export default {
    name: 'Student',
    components: { tablePug, search, page },
    data() {
        return {
            total: 0,
            lists: [],
            titles: [
                { name: '学员姓名', data: 'name' },
                { name: '身份证号', data: 'idcard' },
                { name: '手机号码', data: 'phonenum' },
                { name: '报名日期', data: 'active_time' },
                { name: '工种', data: 'types' }
            ],
            btn: {
                title: '操作',
                width: 140,
                btnlist: [
                    { con: '删除', type: 'warning' },
                    { con: '下载附件', type: 'info' }
                ]
            },
            searchData: {// 搜索条件
                manager_id: this.$store.getters.token,
                size: 8,
                page: 1
            },
            queryData: {}, // 查询条件
            tableloading: false, // 表格加载
            delArr: []
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
            console.log(data, type)
            if (type === '全选') {
                this.delArr = data
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
        },
        delAll() {
            if (this.delArr.length) {
                console.log(this.delArr)
                // axios
            } else {
                this.$message.warning('请选择数据后删除')
            }
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
