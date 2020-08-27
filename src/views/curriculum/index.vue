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
        <div class="bold">班级列表</div>
        <div class="fontGay cursor">
          <span @click="btnsave($event)">新增课件</span>
        </div>
      </div>
      <tablePug class="mt15" :btns="btn" :lists="lists" :titles="titles" @sendVal="getVal" />
      <page :total="total" :page-size="pageSize" @pagesend="getPageData" />
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
    <!-- :before-close="handleClose" -->
    <el-drawer
      ref="drawer"
      :visible.sync="editDialog"
      :show-close="false"
      :with-header="false"
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
import datas from '@/assets/json/data'
import addFrom from './add'
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
            pageSize: 8,
            total: 0,
            lists: [],
            titles: [
                { name: '序号', data: 'orderCode' },
                { name: '课件名称', data: 'xzqMc' },
                { name: '课时数量', data: 'total' },
                { name: '课件ID', data: 'orderCode' },
                { name: '课件时长', data: 'total' },
                { name: '讲师姓名', data: 'remarks' },
                { name: '证书编号', data: 'orderCode' },
                { name: '课件状态', data: 'state' }
            ],
            btn: {
                title: '操作',
                width: '250',
                btnlist: [
                    { con: '编辑', type: 'info' },
                    { con: '视频', type: 'primary' },
                    { con: '习题', type: 'primary' },
                    { con: '删除', type: 'warning', confirm: true }
                ]
            }

        }
    },
    created() {
        this.getPageData(1)
    },
    methods: {
        btnsave(e) {
            if (e.target.innerText === '新增课件') {
                this.editData = null
                this.editDialog = true
            } else {
                this.$message(e.target.innerText)
            }
        },
        getPageData(params) {
            this.total = datas.length
            this.lists = datas.slice((params - 1) * 8, params * 8)
        },
        getVal(v) {
            if (v.type === '编辑') {
                this.$message(`${v.type}---待处理`)
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
            if (v.type === '视频') {
                if (v.data.video) {
                    this.videoDialog = {
                        type: true,
                        name: v.data.cName,
                        src: v.data.video
                    }
                } else {
                    this.$message('暂无视频')
                }
            }
            if (v.type === '习题') {
                this.$message(`${v.type}---待处理`)
            }
            if (v.type === '删除') {
                this.$confirm('是否在当前班级删除该学员?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$message('删除---待处理逻辑')
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    })
                })
                return
            }
        },
        search(e) {
            console.log('搜索', e)
        },
        handleClose(done) {
            if (this.loading) {
                return
            }
            this.$confirm('确定要提交表单吗？').then(_ => {
                this.loading = true
                this.timer = setTimeout(() => {
                    done()
                    // 动画关闭需要一定的时间
                    setTimeout(() => {
                        this.loading = false
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
