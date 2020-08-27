<template>
  <div>
    <div class="lookTable">
      <div class="lookItem fl">
        <div>学员姓名</div>
        <div>滕三锋</div>
        <div>身份证号</div>
        <div>XXXXXXXXXXXXXXXXXX</div>
      </div>
      <div class="lookItem fl">
        <div>手机号码</div>
        <div>13898765432</div>
        <div>模考成绩</div>
        <div>92.00</div>
      </div>
      <div class="lookItem fl">
        <div>顺序联系进度</div>
        <div>309/640</div>
        <div>完成课时</div>
        <div>0/2</div>
      </div>
      <div class="lookItem fl">
        <div>模拟考试次数</div>
        <div>80</div>
        <div>最高分数</div>
        <div>97.00</div>
      </div>
    <!-- <div class="lookItem fl">
          <div>学习进度</div>
          <div class="progress">70%</div>
        </div> -->
    </div>
    <el-tabs v-if="$route.query.type !== 'recruitStudent'" v-model="activeName" class="mt20">
      <el-tab-pane label="模考记录" name="first">
        <el-table :data="tableData" stripe max-height="270" border>
          <el-table-column prop="date" label="序号" />
          <el-table-column prop="name" label="考试分数" />
          <el-table-column prop="address" label="考试用时（分：秒）" />
          <el-table-column prop="date" label="考试时间" />
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="视频学习" name="second">
        <el-table :data="tableData" stripe max-height="270" border>
          <el-table-column prop="date" label="序号" />
          <el-table-column prop="name" label="课程" />
          <el-table-column prop="address" label="课件学习时长" />
          <el-table-column prop="date" label="学习图片">
            <template>
              <el-image style="width: 50px; height: 50px" src="https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg" :preview-src-list="srcList" />
            </template>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
export default {
    data() {
        return {
            activeName: 'first',
            tableData: [
                {
                    date: '2016-05-03',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-02',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-04',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-01',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-08',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-06',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }, {
                    date: '2016-05-07',
                    name: '王小虎',
                    address: '上海市普陀区金沙江路 1518 弄'
                }
            ],
            srcList: [
                'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
                'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
                'https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg'
            ]
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
