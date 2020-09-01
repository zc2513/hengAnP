<template>
  <div class="app-container">
    <h4>element-ui 带搜索 插件</h4>

    <el-input v-model="filterText" placeholder="过滤名称" style="margin-bottom:30px;" />

    <el-tree
      ref="tree2"
      :data="data2"
      :props="defaultProps"
      :filter-node-method="filterNode"
      class="filter-tree"
      default-expand-all
    />
    <div>

      <div><input type="button" value="打印" @click="Print()"></div>
      <!--startprint-->
      <div class="print_box">
        <div v-for="i in 6" :key="i" style="page-break-after:always">
          <table class="ss">
            <tbody>
              <tr>
                <th class="da" colspan="3">学时证明</th>
              </tr>
              <tr>
                <th>姓名</th>
                <th>2</th>
                <th class="zp" rowspan="6">3</th>
              </tr>
              <tr>
                <th class="left">身份证件类型</th>
                <th>2</th>
              </tr>
              <tr>
                <th class="left">身份证件号码</th>
                <th>2</th>
              </tr>
              <tr>
                <th class="left">人员资格类型</th>
                <th>2</th>
              </tr>
              <tr>
                <th class="left">安全培训机构</th>
                <th>2</th>
              </tr>
              <tr>
                <th class="left">安全培训日期</th>
                <th>2</th>
              </tr>
              <tr>
                <th colspan="3">
                  <table class="bottom" border="1">
                    <tbody>
                      <tr>
                        <th>培训方式</th>
                        <th>应修学时</th>
                        <th>实修学时</th>
                        <th>测试成绩</th>
                      </tr>
                      <tr>
                        <th>网络</th>
                        <th>2</th>
                        <th>0</th>
                        <th>97</th>
                      </tr>
                    </tbody>
                  </table>
                  <div class="qm fl">
                    <div class="zhanwei" />
                    <div>
                      <div class="gz">单位：(盖章)</div>
                      <div class="gz">日期</div>
                    </div>
                  </div>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!--endprint-->
    </div>
  </div>
</template>

<script>
export default {

    data() {
        return {
            filterText: '',
            data2: [{
                id: 1,
                label: 'Level one 1',
                children: [{
                    id: 4,
                    label: 'Level two 1-1',
                    children: [{
                        id: 9,
                        label: 'Level three 1-1-1'
                    }, {
                        id: 10,
                        label: 'Level three 1-1-2'
                    }]
                }]
            }, {
                id: 2,
                label: 'Level one 2',
                children: [{
                    id: 5,
                    label: 'Level two 2-1'
                }, {
                    id: 6,
                    label: 'Level two 2-2'
                }]
            }, {
                id: 3,
                label: 'Level one 3',
                children: [{
                    id: 7,
                    label: 'Level two 3-1'
                }, {
                    id: 8,
                    label: 'Level two 3-2'
                }]
            }],
            defaultProps: {
                children: 'children',
                label: 'label'
            }
        }
    },
    watch: {
        filterText(val) {
            this.$refs.tree2.filter(val)
        }
    },

    methods: {
        filterNode(value, data) {
            if (!value) return true
            return data.label.indexOf(value) !== -1
        }, Print() {
            const newstr = document.getElementsByClassName('print_box')[0].innerHTML
            window.document.body.innerHTML = newstr
            const oldstr = window.document.body.innerHTML
            window.print()
            window.location.reload() // 解决打印之后按钮失效的问题
            window.document.body.innerHTML = oldstr
            return false
        }
    }
}
</script>
<style lang="scss" scoped>
.ss{
    width: 90%;
    margin: 300px auto;
    th{
        height: 150px;
        border: 0.5px solid;
        font-size: 40px;
        font-weight: normal;
    }
    .da{
        height: 250px;
        font-size: 48px;
        font-weight: 700;
    }
    .zp{
        width: 25%;
    }
    .left{
        width: 400px;
    }
    .bottom{
        width: 80%;
        margin: 50px auto;
    }
    .qm{
        height: 400px;

        .zhanwei{
            width: 60%;
        }
        .gz{
            display: flex;
            align-items: center;
            height: 50%;
            width: 100%;
        }
    }
}
</style>
