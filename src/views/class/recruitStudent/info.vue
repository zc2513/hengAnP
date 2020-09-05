
<template>
  <!-- 招生中/详情 -->
  <div class="recruitStudentInfo">
    <div class="content-box">
      <div class="flsb">
        <div class="bold">查询条件</div>
      </div>
      <el-form :inline="true" :model="searchstudent" size="small" class="demo-form-inline mt15">
        <el-form-item label="学员姓名">
          <el-input v-model="searchstudent.name" clearable placeholder="请输入学员姓名" />
        </el-form-item>
        <el-form-item label="手机号">
          <el-input v-model="searchstudent.phone" clearable placeholder="请输入学员手机号" />
        </el-form-item>
        <el-form-item label="身份证号">
          <el-input v-model="searchstudent.card" clearable placeholder="请输入学员身份证号" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" style="width:100px;" round @click="querySearch">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">班级/学员列表</div>
        <div class="fontGay fle">
          <!-- <div v-if="$route.query.type !== 'finish'" class="cursor ml15" @click="addStudent($event)"> 添加学员 </div> -->
          <a :href="uploadFile" class="cursor ml15" download="学员导入模板">模板下载</a>
          <!-- <div v-if="$route.query.type !== 'finish'" class="cursor ml15" @click="btnsave($event)"> 导入学员 </div> -->
          <a :href="exportFile" class="cursor ml15" download="学员信息">导出学员</a>
          <div v-if="$route.query.type !== 'recruitStudent'" class="cursor ml15" @click="morePrint"> 批量打印课时 </div>
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
      <page :total="total" :page-size="searchData.size" @pagesend="getPageData" @pagesizes="pagesizes" />
    </div>
    <el-dialog
      :modal-append-to-body="false"
      :width="looks.title === '添加学员' ? '30%':''"
      :top="looks.title === '添加学员' ? '15vh':'10vh'"
      :title="looks.title"
      :visible.sync="looks.status"
    >
      <div v-if="looks.title === '添加学员'">
        <el-form ref="addForm" :model="student" :rules="rules" size="small">
          <el-form-item label="学员姓名" prop="name">
            <el-input v-model="student.name" clearable placeholder="请输入学员姓名" />
          </el-form-item>
          <el-form-item label="手机号" prop="phone">
            <el-input v-model="student.phone" clearable placeholder="请输入学员手机号" />
          </el-form-item>
          <el-form-item label="身份证号" prop="card">
            <el-input v-model="student.card" minlength="15" maxlength="18" show-word-limit clearable placeholder="请输入学员身份证号" />
          </el-form-item>
          <el-form-item class="t-c">
            <el-button type="primary" style="width:200px;" round @click="onSubmit">提交</el-button>
          </el-form-item>
        </el-form>
      </div>
      <studentInfo v-else :student-id="studentId" />
    </el-dialog>
  </div>
</template>

<script>
import tablePug from '@/components/table'
import page from '@/components/table/page'
import studentInfo from '@/components/studentInfo'
import { phoneValidate, IDcardValidate } from '@/utils/validate'
import { getClasslist, delperson } from '@/api/class'
export default {
    name: 'RecruitStudentInfo',
    components: { tablePug, page, studentInfo },
    data() {
        return {
            uploadFile: `${process.env.VUE_APP_BASE_API}/index.php/Wechat/ImgUpload/imgUpload`, // 模板下载
            exportFile: `${process.env.VUE_APP_BASE_API}/index.php/Wechat/ImgUpload/imgUpload`, // 导出学员
            total: 0, // 分页总数量
            lists: [], // 展示数据
            tableloading: false, // 表格加载
            searchData: {// 搜索条件
                size: 8,
                page: 1
                // status: this.$route.path === '/class/recruitStudent' ? 0 : (this.$route.path === '/class/learn' ? 1 : 2)
            },
            titles: [
                { name: '学员ID', data: 'id' },
                { name: '学员姓名', data: 'name' },
                { name: '工种', data: 'types' },
                { name: '身份证号', data: 'idcard' },
                { name: '手机号码', data: 'phonenum' },
                { name: '培训日期', data: 'active_time' },
                { name: '完成学时', data: 'period' }
            ],
            btn: {
                title: '操作',
                width: '220',
                btnlist: [
                    { con: '打印课时', type: 'warning' },
                    { con: '查看', type: 'primary' },
                    { con: '删除', type: 'warning' }
                ]
            },
            looks: {
                status: false,
                title: '',
                datas: {}
            },
            searchstudent: {// 搜索条件
                name: '',
                phonenum: '',
                idcard: ''
            },
            student: {// 提交条件
                name: '',
                phone: '',
                card: ''
            },
            rules: {
                name: [
                    { required: true, message: '学员姓名不能为空', trigger: 'blur' }
                ],
                phone: [
                    { required: true, message: '联系电话不能为空', trigger: 'blur' },
                    { min: 11, max: 11, message: '电话长度应为11个字符', trigger: 'blur' },
                    { validator: phoneValidate, trigger: 'blur' }
                ],
                card: [
                    { required: true, message: '身份证号码不能为空', trigger: 'blur' },
                    { min: 15, max: 18, message: '身份证号码长度在 15 到 18 个字符', trigger: 'blur' },
                    { validator: IDcardValidate, trigger: 'blur' }
                ]
            },
            studentId: 0// 学员详情id
        }
    },
    created() {
        const pathType = this.$route.query.type
        if (pathType === 'recruitStudent') {
            this.titles = this.titles.filter(e => e.name !== '完成学时')
            this.$set(this.btn, 'btnlist', [
                { con: '查看', type: 'primary' },
                { con: '删除', type: 'warning' }
            ])
            this.$set(this.btn, 'width', 120)
            // this.searchData.status = 0
        } else if (pathType === 'learning') {
            console.log('使用原始配置')
            // this.searchData.status = 1
        } else if (pathType === 'finish') {
            this.$set(this.btn, 'width', 150)
            this.$set(this.btn, 'btnlist', [
                { con: '打印课时', type: 'warning' },
                { con: '查看', type: 'primary' }
            ])
            // this.searchData.status = 2
        } else {
            // this.$router.push('/404')
        }
        this.init()
    },
    methods: {
        init() {
            this.tableloading = true
            const data = { class_id: this.$route.query.id, ...this.searchData, ...this.searchstudent }
            getClasslist(data).then(res => {
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
        getBtn({ type, data }) {
            if (type === '查看') {
                this.studentId = data.id || 90075
                this.looks = {
                    status: true,
                    title: '详情'
                }
                return
            }
            if (type === '删除') {
                this.$confirm('是否在当前班级删除该学员?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delperson({ id: data.id }).then(res => {
                        this.$message.success(res.msg)
                        this.init()
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    })
                })
                return
            }
            if (type === '打印课时') {
                const routeData = this.$router.resolve({ path: '/dayin', query: { id: 1 }})
                window.open(routeData.href, '_blank')
            }
        },
        querySearch() {
            this.init()
        },
        onSubmit() {
            console.log('提交', this.student)
        },
        getPageData(params) { // 页
            this.searchData.page = params
            this.init()
        },
        pagesizes(num) { // 每页多少个并重置page为1
            this.searchData.size = num
            this.searchData.page = 1
            this.init()
        },
        addStudent(e) { // 添加学员
            this.student = {// 重置提交条件
                name: '',
                phone: '',
                card: ''
            }
            this.looks = {
                status: true,
                title: e.target.innerText,
                datas: {}
            }
        },
        morePrint() {
            const routeData = this.$router.resolve({ path: '/dayin', query: { id: 1 }})
            window.open(routeData.href, '_blank')
        }
    }
}
</script>
<style lang="scss" scoped>
.recruitStudentInfo{
    .lookTable{
        border-top: 1px solid #ccc;
        border-right: 1px solid #ccc;
        .lookItem{
            >div{
                border-left: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                width: calc(100% / 4);
                padding: 0 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 50px;
                line-height: 20px;
                overflow: hidden;
                &:nth-child(odd){
                    // justify-content: flex-end;
                    font-weight: 600;
                }
            }
            .progress{
                width: 75%;
            }
        }
    }
}
</style>
