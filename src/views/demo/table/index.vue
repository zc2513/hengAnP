<template>
  <div style="margin-top:30px;">
    <h4 style="color:#c00;">多功能表格---------------单选</h4>
    <div style="text-align: center;">
      <el-button type="info" size="mini" @click="type = true">有选择框</el-button>
      <el-button type="info" size="mini" @click="type = false">无选择框</el-button>
    </div>
    <div v-if="suninfo">
      <b style="color:00f;font-size:20px;">子组件所传的值展示区域(调试框可看所有值)：</b> {{ suninfo.type || suninfo }}
    </div>
    <tablePug :lists="lists" :titles="titles" :type="type" @sendVal="getVal" />
    <page @pagesend="getPageData" />
  </div>
</template>

<script>
import tablePug from './tablePlugin'
import page from './page'
import datas from './data.js'
export default {
    components: {
        tablePug,
        page
    },
    data() {
        return {
            lists: [],
            type: true,
            suninfo: null,
            titles: [
                { name: '订单编号', data: 'orderCode' },
                { name: '订单状态', data: 'state' },
                { name: '单位编号', data: 'cNumber' },
                { name: '单位名称', data: 'cName' },
                { name: '订单金额', data: 'total' },
                { name: '支付方式', data: 'payMethod' },
                { name: '登记时间', data: 'ctime' }]
        }
    },
    created() {
        this.getPageData(1)
    },
    methods: {
        getVal(val) {
            console.log(val)
            this.suninfo = val
        },
        getPageData(params) {
            console.log('分页', params)
            this.suninfo = params
            this.lists = datas.slice((params - 1) * 8, params * 8)
        }

    }
}

</script>
