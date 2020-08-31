<template>
  <!-- 创建班级 -->
  <div class="create-box">
    <div class="content-box">
      <div class="flsb">
        <div class="bold">完善您的班级信息</div>
      </div>
      <el-form
        ref="formName"
        :inline="true"
        :model="formData"
        label-width="80px"
        label-position="right"
        size="small"
        class="demo-form-inline mt15"
        :rules="rules"
      >
        <el-row>
          <el-col class="mb10" :span="12">
            <el-form-item label="班级名称" prop="classname">
              <el-input v-model="formData.classname" clearable placeholder="请输入班级名称：工种类型+取证/复审/换证+期号" />
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="代课老师" prop="teacher">
              <el-input v-model="formData.teacher" clearable placeholder="请输入老师姓名" />
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="授课方式" prop="way">
              <el-select v-model="formData.way" placeholder="请选择">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="行业" prop="types">
              <el-cascader
                v-model="formData.types"
                placeholder="请选择"
                :props="{lazy:true,lazyLoad}"
                @change="getChapterList"
              />
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="手机号码" prop="classipone">
              <el-input v-model="formData.classipone" maxlength="11" show-word-limit clearable placeholder="请输入联系电话" />
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="报名人数" prop="classnum">
              <el-input v-model="formData.classnum" maxlength="3" clearable placeholder="请输入报名人数" />
            </el-form-item>
          </el-col>
          <el-col class="mb10" :span="12">
            <el-form-item label="开学日期" prop="startclass">
              <el-date-picker
                v-model="times"
                type="daterange"
                value-format="timestamp"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                @change="changeDate"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <div class="mt20">
          <div class="bold">已选课时（{{ chapters.length }}）</div>
          <div class="table-list">
            <el-table
              ref="refTable"
              :data="chapters"
              align="center"
              @expand-change="expandChange"
              @selection-change="handleSelectionChange"
            >
              <el-table-column type="selection" width="50" />
              <el-table-column prop="address" class-name="expand-column-cls" width="50" abel-class-name="列明" type="expand">
                <template slot-scope="scope">
                  <el-table
                    :data="scope.row.children"
                    align="center"
                    :show-header="false"
                    class="child-table"
                  >
                    <el-table-column width="50" />
                    <el-table-column width="50" class-name="expand-column-cls">
                      <template slot-scope="{row}">
                        <el-button size="mini" type="text" @click="previewVideo(row)">预览</el-button>
                      </template>
                    </el-table-column>
                    <el-table-column prop="date" label="章节" />
                    <el-table-column prop="name" label="名称" />
                    <el-table-column prop="time" label="学时" />
                    <el-table-column prop="type" label="类型" />
                    <el-table-column prop="hz" label="初训/复审换证" />
                    <el-table-column prop="teacher" label="讲师" />
                    <!-- <el-table-column width="50">
                      <template slot-scope="{row}">
                        <el-button size="mini" plain round type="primary" @click="previewVideo(row)">预览</el-button>
                      </template>
                    </el-table-column> -->
                  </el-table>
                </template>
              </el-table-column>
              <el-table-column prop="date" label="章节">
                <template slot-scope="{$index}">
                  章{{ $index+1 }}
                </template>
              </el-table-column>
              <el-table-column prop="chapter_title" label="名称" />
              <el-table-column prop="hours" label="学时" />
              <el-table-column prop="type" label="类型" />
              <el-table-column prop="hz" label="初训/复审换证">
                <template>
                  初训/复审
                </template>
              </el-table-column>
              <el-table-column prop="teacher" label="讲师" />
              <!-- <el-table-column label="操作" width="50">
                <template slot-scope="{row}">
                  <div @click="toogleExpandTable(row)">
                    <el-button size="mini" type="text">展开</el-button>
                  </div>
                </template>
              </el-table-column> -->
            </el-table>

          </div>
        </div>
        <el-form-item class="t-c wfull mt20">
          <el-button type="primary" plain round @click="onSubmit">确认创建</el-button>
        </el-form-item>
      </el-form>
      <el-dialog :modal-append-to-body="false" :visible.sync="dialogTableVisible" :before-close="handleClose">
        <template #title>{{ video.name }} </template>
        <video autoplay controlsList="nodownload" controls :src="video.src" />
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { phoneValidate } from '@/utils/validate'
// eslint-disable-next-line no-unused-vars
import { addclass, chapterlist } from '@/api/class'
import { getIndustryType } from '@/api/dic'
export default {
    data() {
        return {
            dialogTableVisible: false,
            video: {
                name: '',
                src: ''
            },
            formData: {
                classname: '', // 班级名称
                teacher: '', // 代课老师
                way: '网授', // 授课方式
                types: [], // 行业选择
                classipone: '', // 班主任手机号
                classnum: '', // 报名人数
                startclass: '', // 开始时间
                endclass: '', // 结束时间
                course: '', // 已选课程
                selectionTable: []// 已选课程
            },
            rules: {
                classname: [
                    { required: true, message: '请输入班级名称', trigger: 'blur' }
                ],
                teacher: [
                    { required: true, message: '请输入代课老师名称', trigger: 'blur' },
                    { min: 2, max: 8, message: '姓名长度在 2 到 8 个字符', trigger: 'blur' }
                ],
                way: [
                    { required: true, message: '请选择报名方式', trigger: ['blur', 'change'] }
                ],
                types: [
                    { required: true, message: '请输选择行业类别', trigger: ['blur', 'change'] }
                ],
                classipone: [
                    { required: true, message: '联系电话不能为空', trigger: 'blur' },
                    { min: 11, max: 11, message: '电话长度应为11个字符', trigger: 'blur' },
                    { validator: phoneValidate, trigger: 'blur' }
                ],
                classnum: [
                    { required: true, message: '请输入报名人数', trigger: 'blur' },
                    { min: 1, max: 3, message: '长度在 1 到 3 个字符', trigger: 'blur' }
                ],
                startclass: [
                    { required: true, message: '请选择开始时间与结束时间', trigger: ['change', 'blur'] }
                ]
            },
            times: '',
            options: [
                {
                    value: '网授',
                    label: '网授'
                }, {
                    value: '面授',
                    label: '面授'
                }
            ],
            tableData: [
                {
                    id: 1,
                    date: '章1',
                    name: '关于高级线路对接',
                    address: '上海市普陀区金沙江路 1518 弄',
                    time: 203,
                    type: '电弧焊',
                    hz: '初训/复审'
                }, {
                    id: 2,
                    date: '章2',
                    name: '关于高级线路对接',
                    address: '上海市普陀区金沙江路 1517 弄',
                    time: 203,
                    type: '电弧焊',
                    hz: '初训/复审'
                }, {
                    id: 3,
                    date: '章3',
                    name: '关于高级线路对接',
                    address: '上海市普陀区金沙江路 1519 弄',
                    time: 203,
                    type: '电弧焊',
                    hz: '初训/复审',
                    children: [
                        {
                            id: 31,
                            date: '节3.1',
                            name: '视频测试',
                            address: '上海市普陀区金沙江路 1519 弄',
                            time: 203,
                            type: '电弧焊',
                            hz: '初训/复审',
                            teacher: '张三',
                            video: require('@/assets/mda-kgsziujvmhk8ak2h.mp4')
                        },
                        {
                            id: 32,
                            date: '节3.2',
                            name: '写真',
                            address: '上海市普陀区金沙江路 1519 弄',
                            time: 203,
                            type: '电弧焊',
                            hz: '初训/复审',
                            teacher: '李四',
                            video: require('@/assets/mda-kg8j3tuk9k2ba6ma.mp4')
                        },
                        {
                            id: 32,
                            date: '节3.3',
                            name: '一休小和尚',
                            address: '上海市普陀区金沙江路 1519 弄',
                            time: 203,
                            type: '电弧焊',
                            hz: '初训/复审',
                            teacher: '王麻子',
                            video: require('@/assets/mda-kdcnbux7did3cqti.mp4')
                        }
                    ]
                }, {
                    id: 4,
                    date: '章4',
                    name: '关于高级线路对接',
                    address: '上海市普陀区金沙江路 1516 弄',
                    time: 203,
                    type: '电弧焊',
                    hz: '初训/复审'
                }
            ],
            chapters: []// 章节列表
        }
    },
    created() {

    },
    mounted() {
        for (const item of this.tableData) { // 默认全选
            this.$refs.refTable.toggleRowSelection(item)
        }
    },
    methods: {
        init() {

        },
        lazyLoad(node, resolve) {
            const { level } = node
            const url = level + 1
            const params = {}
            if (level) { params['id'] = node.value }
            let nodes = []
            getIndustryType(params, url).then(res => {
                console.log(res, 4444)
                nodes = res.map(item => {
                    return {
                        value: item.id,
                        label: item.name,
                        leaf: !item.status
                    }
                })
                resolve(nodes)
            }).catch(() => {
                resolve(nodes)
            })
        },
        onSubmit() {
            console.log(this.formData, 111)
            this.$refs.formName.validate((valid) => {
                if (valid) {
                    alert('submit!')
                } else {
                    console.log('error submit!!')
                    return false
                }
            })
            this.$emit('search', this.formData)
        },
        toogleExpandTable(row) {
            this.$refs.refTable.toggleRowExpansion(row)
        },
        previewVideo(row) {
            console.log('视频预览', row)
            if (!row.video) return this.$message('缺少视频路径')
            this.video = {
                name: row.date + '/' + row.name,
                src: row.video
            }
            this.dialogTableVisible = true
        },
        handleClose(done) {
            this.video.src = ''
            done()
        },
        expandChange(row, expandedRows) {
            console.log(row, 111)
            console.log(expandedRows, 2222)
        },
        handleSelectionChange(row) {
            this.formData.selectionTable = row
        },
        changeDate(time) {
            this.formData.startclass = time ? time[0] : ''
            this.formData.endclass = time ? time[1] : ''
        },
        getChapterList() {
            if (this.formData.types.length) {
                chapterlist({ types: this.formData.types }).then(res => {
                    console.log('章', res)
                    this.chapters = res
                    this.$nextTick(() => {
                        for (const item of res) { // 默认全选
                            this.$refs.refTable.toggleRowSelection(item)
                        }
                    })
                }).catch(() => {
                    this.chapters = []
                })
            } else {
                this.chapters = []
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    .create-box{
        /deep/.el-row{
            .el-form-item{
                display: flex;
                .el-form-item__content{
                    width: calc(100% - 120px);
                    max-width: 380px;
                }
                label{
                    color: rgba(153,153,153,1);
                }
            }
            >div:nth-child(odd){
                .el-form-item{
                    justify-content: flex-end;
                    padding-right: 40px;
                }
            }
            >div:nth-child(even){
                .el-form-item{
                    padding-left: 40px;
                }
            }
            .el-select,.el-cascader{
                width: 100%;
            }
        }
        /deep/.el-date-editor{
            width: 100%;
        }
        /deep/.el-table__expanded-cell[class*=cell]{
            padding: 0;
        }
        /deep/.el-dialog__body{
            padding: 0 !important;
        }
        /deep/.expand-column-cls{//展开列
            // display: none;
        }
        video{
            width: 100%;
            max-height: 400px;
        }
    }
</style>
