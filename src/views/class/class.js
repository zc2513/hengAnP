// 班级minxin
import tablePug from '@/components/table'
import page from '@/components/table/page'
import search from './search'
import { selectclass, delclass } from '@/api/class'
export default {
    components: { tablePug, search, page },
    data() {
        return {
            total: 0, // 分页总数量
            lists: [], // 展示数据
            titles: [
                { name: '序号', data: 'classid' },
                { name: '班级名称', data: 'classname' },
                { name: '开班时间', data: 'startclass' },
                { name: '截止日期', data: 'endclass' },
                { name: '应修学时', data: 'classhour' },
                { name: '学员数量', data: 'classnum' },
                { name: '班主任', data: 'teacher' },
                { name: '进度', data: 'consPhone' }
            ],
            searchData: {// 搜索条件
                manager_id: this.$store.getters.token,
                size: 8,
                page: 1,
                status: this.$route.path === '/class/recruitStudent' ? 0 : (this.$route.path === '/class/learn' ? 1 : 2)
            },
            tableloading: false, // 表格加载
            qrCodesType: false, // 二维码弹窗
            imgLoading: true, // 照片加载
            qrCodeSrc: ''
        }
    },
    watch: {
        searchData: {
            handler(v) {
                if (this.$route.path !== '/class/recruitStudent/info') {
                    this.init(v)
                }
            },
            deep: true
        }
    },
    created() {
        if (this.$route.path !== '/class/recruitStudent/info') { // 详情不处理
            this.init(this.searchData)
        }
    },
    methods: {
        getPageData(params) { // 页
            this.searchData.page = params
        },
        pagesizes(num) { // 每页多少个并重置page为1
            this.searchData.size = num
            this.searchData.page = 1
        },
        search(data) { // 搜索条件
            this.searchData = { ...this.searchData, ...data }
        },
        init(params) { // 数据初始化
            this.tableloading = true
            selectclass(params).then(res => {
                console.log(res, 4444)
                const { data } = res
                this.total = Number(data.count)
                for (const item of data.list) {
                    item.startclass = this.$parseTime(item.startclass, '{y}-{m}-{d}')
                    item.endclass = this.$parseTime(item.endclass, '{y}-{m}-{d}')
                }
                this.lists = data.list
                this.tableloading = false
            }).catch(() => { this.tableloading = false })
        },

        dissolve(data) { // 解散
            if (data.classperson > 0) return this.$message.warning(`当前班级尚有${data.classperson}个学员，不可解散`)
            this.$confirm('确认要解散当前班级?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                delclass({ id: data.id }).then(res => {
                    this.$message.success('删除成功!')
                    this.init(this.searchData)
                })
            }).catch(() => {
                this.$message.info('取消')
            })
        },

        showQrCode(data) { // 二维码展示
            this.qrCodesType = true
            this.qrCodeSrc = `${process.env.VUE_APP_BASE_API}/index.php/Master/Qrcode/index?classid=${data.classid}&id=${this.$store.getters.token}`
        }

    }
}
