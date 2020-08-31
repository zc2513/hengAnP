// 班级minxin
import tablePug from '@/components/table'
import page from '@/components/table/page'
import search from './search'
import { selectclass } from '@/api/class'
export default {
    components: { tablePug, search, page },
    data() {
        return {
            pageSize: 8,
            pageNum: 1,
            total: 0,
            lists: [],
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
            }
        }
    },
    watch: {
        searchData: {
            handler(v) {
                console.log(v, '查询数据')
                this.init(v)
            },
            deep: true
        }
    },
    created() {
        this.init(this.searchData)
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
        init(params) {
            selectclass(params).then(res => {
                console.log(res, 4444)
                const { data } = res
                this.total = Number(data.count)
                for (const item of data.list) {
                    item.startclass = this.$parseTime(item.startclass, '{y}-{m}-{d}')
                    item.endclass = this.$parseTime(item.endclass, '{y}-{m}-{d}')
                }
                this.lists = data.list
            })
        }
    }
}
