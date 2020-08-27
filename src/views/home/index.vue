<template>
  <div class="home">
    <div class="top">
      <ul class="flsb">
        <li class="flsb">
          <div class="left" style=" background: linear-gradient(0deg,rgba(11,186,251,1),rgba(66,133,236,1));" />
          <div class="right first flc-y">
            <div class="count flsb">
              <div class="fl-y-sa">
                <div class="f30 bold">5,000</div>
                <div class="f18 fontGay">学员总数<span class="f12">(人)</span></div>
              </div>
              <div class="fl-y-sa">
                <div class="f30 bold">10,000</div>
                <div class="f18 fontGay">培训次数<span class="f12">(人次)</span></div>
              </div>
            </div>
          </div>
        </li>
        <li class="flsb">
          <div class="left" style="background:linear-gradient(0deg,rgba(47,179,131,1),rgba(24,168,107,1));" />
          <div class="right two flc-y">
            <el-row class="count">
              <el-col :span="9" class="fl-y-sa hfull">
                <div class="f30 bold"> 200 </div>
                <div class="f18 fontGay">总计班级(个)</div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#ff9933;">100</div>
                  <div class="fontGay f14 mt10">进行中</div>
                </div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#ff3300;">50</div>
                  <div class="fontGay f14 mt10">招生中</div>
                </div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#009966;">50</div>
                  <div class="fontGay f14 mt10">已完成</div>
                </div>
              </el-col>
            </el-row>
          </div>
        </li>
        <li class="flsb">
          <div class="left" style="background:linear-gradient(0deg,rgba(242,142,38,1),rgba(253,100,79,1));" />
          <div class="right waring">
            <div class="f30">预警班级</div>
            <div class="mt10 fontGay">剩余1周还有未完成课时的班级</div>
            <div class="classList">
              <div class="mt10 ">1XXXXXXXX班 </div>
              <div class="mt10 ">2XXXXXXXXX班</div>
              <div class="mt10 ">3XXXXXXXXX班</div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="mt20 content-box">
      <div class="flsb">
        <div class="bold">学习中班级信息</div>
        <div class="fontGay cursor" @click="$router.push('/class/learn')">更多</div>
      </div>
      <tablePug class="mt15" :btns="btn" :lists="lists" :titles="titles" @sendVal="getVal" />
    </div>
  </div>
</template>

<script>
import tablePug from '@/components/table'
import datas from '@/assets/json/data'
export default {
    name: 'Home',
    components: { tablePug },
    data() {
        return {
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
            ],
            btn: {
                title: '操作',
                width: '200',
                btnlist: [
                    { con: '结业', type: 'success' },
                    { con: '详情', type: 'primary' },
                    { con: '解散', type: 'warning' }
                ]
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
            this.lists = datas.slice((params - 1) * 8, params * 8)
        },
        getVal(v) {
            console.log(v)

            if (v.type === '结业') {
                this.$message(`${v.type}---待处理`)
                return
            }
            if (v.type === '详情') {
                this.$router.push('/class/recruitStudent/info?type=learning')
                return
            }
            if (v.type === '解散') {
                this.$message(`${v.type}---待处理`)
                return
            }
        }
    }
}
</script>

<style lang="scss" scoped>
.home{
    .top{
        li{
            width: 30%;
            max-width: 490px;
            height: 230px;
            .left{
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
                width: 20px;
                min-width: 20px;
                height: 100%;
            }
            .right{
                width: calc(100% - 20px);
                min-width: 300px;
                height: 100%;
                background-color: #fff;
                border-bottom-right-radius: 20px;
                border-top-right-radius: 20px;
            }
            .count{
                width: 100%;
                height: 90px;
            }
            .first{
                .count{
                    >div{
                        width: 50%;
                        height: 100%;
                    }
                }
            }
            .waring{
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding-left: 20px;
            }
        }
    }
}
</style>
