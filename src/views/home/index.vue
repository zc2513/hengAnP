<template>
  <div class="home">
    <div class="top">
      <ul class="flsb">
        <li class="flsb">
          <div class="left" style=" background: linear-gradient(0deg,rgba(11,186,251,1),rgba(66,133,236,1));" />
          <div class="right first flc-y">
            <div class="count flsb">
              <div class="fl-y-sa">
                <div class="f30 bold">{{ counts.count }}</div>
                <div class="f18 fontGay">学员总数<span class="f12">(人)</span></div>
              </div>
              <div class="fl-y-sa">
                <div class="f30 bold">{{ counts.count }}</div>
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
                <div class="f30 bold"> {{ counts.classcount }} </div>
                <div class="f18 fontGay">总计班级(个)</div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#ff9933;">{{ counts.xcount }}</div>
                  <div class="fontGay f14 mt10">进行中</div>
                </div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#ff3300;">{{ counts.kcount }}</div>
                  <div class="fontGay f14 mt10">招生中</div>
                </div>
              </el-col>
              <el-col :span="5" class="flc-y hfull">
                <div class="wfull mt15 t-c">
                  <div style="color:#009966;">{{ counts.jcount }}</div>
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
              <div v-for="item of warningClass" :key="item.id" class="mt10 ">{{ item.classname }} </div>
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
import { getHome } from '@/api/home'
import { selectclass, updateclass, delclass } from '@/api/class'
export default {
    name: 'Home',
    components: { tablePug },
    data() {
        return {
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
            btn: {
                title: '操作',
                width: '200',
                btnlist: [
                    { con: '结业', type: 'success' },
                    { con: '详情', type: 'primary' },
                    { con: '解散', type: 'warning' }
                ]
            },
            warningClass: [], // 预警班级
            counts: {}// 统计数量
        }
    },
    created() {
        // this.getPageData(1)
        this.init()
    },
    methods: {
        init() {
            getHome({ manager_id: this.$store.getters.token }).then(res => {
                this.warningClass = res.class.slice(0, 3)
                this.counts = res.count
            })
            const data = {
                manager_id: this.$store.getters.token,
                size: 8,
                page: 1,
                status: 1
            }
            selectclass(data).then(res => {
                this.lists = res.data.list
                for (const item of res.data.list) {
                    item.startclass = this.$parseTime(item.startclass, '{y}-{m}-{d}')
                    item.endclass = this.$parseTime(item.endclass, '{y}-{m}-{d}')
                }
            })
        },
        btnsave(e) {
            this.$message(e.target.innerText)
        },
        getPageData(params) {
            this.lists = datas.slice((params - 1) * 8, params * 8)
        },
        getVal({ type, data }) {
            if (type === '结业') {
                this.$confirm(`当前班级进度为${data.progress || 0}%?,是否确定结业`, '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    updateclass({ status: 2, id: data.id }).then(res => {
                        this.$message.success('操作成功')
                        this.init(this.searchData)
                    })
                }).catch(() => {
                    this.$message.info('取消')
                })
            }
            if (type === '详情') {
                this.$router.push('/class/recruitStudent/info?type=learning')
                return
            }
            if (type === '解散') {
                if (data.classperson > 0) return this.$message.warning(`当前班级尚有${data.classperson}个学员，不可解散`)
                this.$confirm('确认要解散当前班级?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delclass({ id: data.id }).then(res => {
                        this.$message.success(res.msg)
                        this.init(this.searchData)
                    })
                }).catch(() => {
                    this.$message.info('取消')
                })
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
