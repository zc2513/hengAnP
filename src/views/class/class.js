// 班级minxin
import tablePug from '@/components/table'
import page from '@/components/table/page'
import search from './search'
import datas from '@/assets/json/data'
export default {
    components: { tablePug, search, page },
    data() {
        return {
            pageSize: 8,
            total: 0,
            lists: [],
            titles: [
                { name: '序号', data: 'orderCode' },
                { name: '班级名称', data: 'xzqMc' },
                { name: '开班时间', data: 'undertakeTime' },
                { name: '截止日期', data: 'scrapTime' },
                { name: '应修学时', data: 'total' },
                { name: '学员数量', data: 'consAddress' },
                { name: '班主任', data: 'remarks' },
                { name: '进度', data: 'consPhone' }
            ]
        }
    },
    created() {
        this.getPageData(1)
    },
    methods: {
        getPageData(params) {
            this.total = datas.length
            this.lists = datas.slice((params - 1) * this.pageSize, params * this.pageSize)
        },
        search(e) {
            console.log('搜索', e)
        }
    }
}
