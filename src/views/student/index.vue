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
      <tablePug class="mt15" :btns="btn" :lists="lists" :titles="titles" @sendVal="getVal" />
      <page :total="total" :page-size="pageSize" @pagesend="getPageData" />
    </div>
    <el-dialog :modal-append-to-body="false" top="10vh" title="详情" :visible.sync="dialogLook">
      <studentInfo />
    </el-dialog>
  </div>
</template>

<script>
import tablePug from '@/components/table'
import page from '@/components/table/page'
import datas from '@/assets/json/data'
import studentInfo from '@/components/studentInfo'
import search from './search'
export default {
    name: 'Home',
    components: { tablePug, search, page, studentInfo },
    data() {
        return {
            pageSize: 8,
            total: 0,
            lists: [],
            titles: [
                { name: '序号', data: 'orderCode' },
                { name: '学员姓名', data: 'xzqMc' },
                { name: '工种', data: 'total' },
                { name: '身份证号', data: 'orderCode' },
                { name: '手机号码', data: 'markCode' },
                { name: '培训日期', data: 'undertakeTime' },
                { name: '完成学时', data: 'total' }
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
            }
        }
    },
    created() {
        this.getPageData(1)
    },
    methods: {
        btnsave(e) {
            this.$message(e.target.innerText)
        },
        getPageData(params) {
            this.total = datas.length
            this.lists = datas.slice((params - 1) * 8, params * 8)
        },
        getVal(v) {
            console.log(v)
            if (v.type === '详情') {
                this.dialogLook = true
                return
            } else {
                this.$message(v.type)
            }
        },
        search(e) {
            console.log('搜索', e)
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
