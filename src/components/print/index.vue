<template>
  <div>
    <!--startprint-->
    <div v-if="list.length" class="print_box">
      <div v-for="item in list" :key="item.id">
        <div style="page-break-after:always">
          <table class="ss">
            <tbody>
              <tr>
                <th class="da" colspan="3">学时证明</th>
              </tr>
              <tr>
                <th>姓名</th>
                <th>{{ item.name }}</th>
                <th class="zp" rowspan="6"> 照片 </th>
              </tr>
              <tr>
                <th class="left">身份证件类型</th>
                <th>居民身份证</th>
              </tr>
              <tr>
                <th class="left">身份证件号码</th>
                <th>{{ item.idcard }}</th>
              </tr>
              <tr>
                <th class="left">人员资格类型</th>
                <th>{{ item.type_name || '暂无' }}</th>
              </tr>
              <tr>
                <th class="left">安全培训机构</th>
                <th>{{ item.jg_name || '暂无' }}</th>
              </tr>
              <tr>
                <th class="left">安全培训日期</th>
                <th>{{ item.jg_name || '暂无' }}</th>
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
                        <th>网授</th>
                        <th>{{ item.chapter_hours }}</th>
                        <th>{{ item.chapter_record_hours }}</th>
                        <th>{{ item.max_score }}</th>
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
        <div v-for="key in 3" :key="key" style="page-break-after:always">
          <table class="ss" style="margin:30px auto;">
            <tbody>
              <tr>
                <th class="da" style="height:100px;" colspan="16">学习详情</th>
              </tr>
              <tr>
                <th colspan="3">姓名</th>
                <th colspan="5">{{ item.name }}</th>
                <th colspan="3">身份证号码</th>
                <th colspan="5">{{ item.idcard }}</th>
              </tr>
              <tr>
                <th colspan="3">人员资格类型</th>
                <th colspan="9">{{ item.type_name }}</th>
                <th colspan="2">培训类型</th>
                <th colspan="2">网授</th>
              </tr>
              <tr>
                <th colspan="10">章节名称</th>
                <th colspan="2">讲师</th>
                <th colspan="2">应修学时</th>
                <th colspan="2">获得学时</th>
              </tr>
            </tbody>
            <tbody>
              <tr>
                <th style="text-align:left;" colspan="10">第一章 绪论 放得开酸辣粉加两大飞机看电视了</th>
                <th colspan="2" />
                <th colspan="2">8</th>
                <th colspan="2">8</th>
              </tr>
              <tr>
                <th style="text-align:left;" colspan="10">基础知识</th>
                <th colspan="2">张三</th>
                <th colspan="2" />
                <th colspan="2" />
              </tr>
              <tr>
                <th colspan="4">应修总学</th>
                <th colspan="4">16</th>
                <th colspan="4">实学总学时</th>
                <th colspan="4">2</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!--endprint-->
  </div>
</template>
<script>
import { getPrint } from '@/api/class'
export default {
    props: {
        // eslint-disable-next-line vue/require-default-prop
        id: String
    },
    data() {
        return {
            list: []
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            getPrint(this.$route.query).then(res => {
                console.log(res, 6666)
                this.list = res.data
            })

            // this.Print()
        },
        // Print() {
        //     const newstr = document.getElementsByClassName('print_box')[0].innerHTML
        //     window.document.body.innerHTML = newstr
        //     const oldstr = window.document.body.innerHTML
        //     window.print()
        //     window.location.reload() // 解决打印之后按钮失效的问题
        //     window.document.body.innerHTML = oldstr
        //     return false
        // }
        Print() { // 新页面
            // const newstr = document.getElementsByClassName('print_box')[0].innerHTML
            // window.document.body.innerHTML = newstr
            // // const oldstr = window.document.body.innerHTML
            // window.print()
            // // window.location.reload() // 解决打印之后按钮失效的问题
            // // window.document.body.innerHTML = oldstr
            // return false
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
