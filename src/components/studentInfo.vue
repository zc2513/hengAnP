<template>
  <div v-loading="infoStatus" element-loading-text="页面数据加载中">
    <div class="lookTable">
      <div class="lookItem fl">
        <div>学员姓名</div>
        <div>{{ user.name }}</div>
        <div>身份证号</div>
        <div>{{ user.idcard }}</div>
      </div>
      <div class="lookItem fl">
        <div>手机号码</div>
        <div>{{ user.phonenum }}</div>
        <div>模考成绩</div>
        <div>{{ user.mkcj }}</div>
      </div>
      <div class="lookItem fl">
        <div>顺序联系进度</div>
        <div>{{ user.sxlx }}</div>
        <div>完成课时</div>
        <div>{{ user.ks }}</div>
      </div>
      <div class="lookItem fl">
        <div>模拟考试次数</div>
        <div>{{ user.count }}</div>
        <div>最高分数</div>
        <div>{{ user.max_score }}</div>
      </div>
    <!-- <div class="lookItem fl">
          <div>学习进度</div>
          <div class="progress">70%</div>
        </div> -->
    </div>
    <el-tabs v-if="$route.query.type !== 'recruitStudent'" v-model="activeName" class="mt20">
      <el-tab-pane label="模考记录" name="first">
        <el-table :data="examlist" stripe max-height="270" border>
          <el-table-column prop="id" label="序号" />
          <el-table-column prop="score" label="考试分数" />
          <el-table-column prop="use_time" label="考试用时（分：秒）" />
          <el-table-column label="考试时间">
            <template slot-scope="{row}">
              {{ $parseTime(row.shijian) }}
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="视频学习" name="second">
        <el-table :data="vediolist" stripe max-height="270" border>
          <el-table-column prop="id" label="序号" />
          <el-table-column prop="title" label="课程" />
          <el-table-column prop="jindu" label="课件学习时长" />
          <el-table-column prop="imgs" label="学习图片">
            <template slot-scope="{row}">
              <el-image style="width: 50px; height: 50px" :src="row.imgs[0]" :preview-src-list="srcList" @click="srcList = row.imgs" />
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { userdetails } from '@/api/student'
export default {
    props: {
        studentId: {
            type: [String, Number],
            default: ''
        }
    },
    data() {
        return {
            infoStatus: false,
            activeName: 'first',
            user: {},
            examlist: [], // 模考记录
            vediolist: [], // 视频记录
            srcList: []// 展示列表
        }
    },
    watch: {
        studentId(v) {
            this.init()
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            if (!this.studentId) return
            this.infoStatus = true
            userdetails({ id: this.studentId }).then(res => {
                const { user, examlist, vediolist } = res
                this.user = user
                this.examlist = examlist
                for (const item of vediolist) {
                    item.imgs = item.imgs.split(',')
                }
                this.vediolist = vediolist
                this.infoStatus = false
            }).catch(() => { this.infoStatus = false })
        }
    }
}
</script>

<style lang="scss" scoped>
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
</style>
