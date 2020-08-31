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
                :picker-options="pickerOptions0"
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
              :empty-text="isChapter?&quot;数据加载中...&quot;:&quot;暂无数据&quot;"
              @expand-change="expandChange"
              @selection-change="handleSelectionChange"
            >
              <el-table-column type="selection" width="50" />
              <el-table-column prop="address" class-name="expand-column-cls" width="50" type="expand">
                <template slot-scope="scope">
                  <el-table
                    v-loading="isChildren"
                    :data="scope.row.children"
                    :empty-text="isChildren?&quot;数据加载中...&quot;:&quot;暂无数据&quot;"
                    :show-header="false"
                    align="center"
                    class="child-table"
                  >
                    <el-table-column width="50" />
                    <el-table-column width="50" class-name="expand-column-cls">
                      <template slot-scope="{row}">
                        <el-button size="mini" type="text" @click="previewVideo(row)">预览</el-button>
                      </template>
                    </el-table-column>
                    <el-table-column prop="date" label="章节" width="100">
                      <template slot-scope="{$index}">
                        {{ scope.$index+1 }}.{{ $index+1 }}节
                      </template>
                    </el-table-column>
                    <el-table-column prop="title" label="名称" />
                    <el-table-column prop="hours" label="学时" width="50" />
                    <el-table-column prop="type" label="类型" width="120" />
                    <el-table-column prop="hz" label="初训/复审换证" width="100">
                      <template>
                        初训/复审
                      </template>
                    </el-table-column>
                    <el-table-column prop="teacher" label="讲师" width="100" />
                    <!-- <el-table-column width="50">
                      <template slot-scope="{row}">
                        <el-button size="mini" plain round type="primary" @click="previewVideo(row)">预览</el-button>
                      </template>
                    </el-table-column> -->
                  </el-table>
                </template>
              </el-table-column>
              <el-table-column prop="date" label="章节" width="100">
                <template slot-scope="{$index}">
                  章{{ $index+1 }}
                </template>
              </el-table-column>
              <el-table-column prop="chapter_title" label="名称" />
              <el-table-column prop="hours" label="学时" width="50" />
              <el-table-column prop="type" label="类型" width="120" />
              <el-table-column prop="hz" label="初训/复审换证" width="100">
                <template>
                  初训/复审
                </template>
              </el-table-column>
              <el-table-column prop="teacher" label="讲师" width="100" />
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
          <el-button type="primary" plain round :loading="isSub" @click="onSubmit">{{ isSub ? '数据提交中...' : '确认创建' }}</el-button>
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
import { addclass, chapterlist, getKnotList, detailsclass, updateclass } from '@/api/class'
import { getIndustryType } from '@/api/dic'
export default {
    data() {
        return {
            pickerOptions0: {
                disabledDate(time) {
                    return time.getTime() < Date.now() - 8.64e7// 如果没有后面的-8.64e7就是不可以选择今天的
                }
            },
            isChapter: false, // 获取章
            isChildren: false, // 获取节
            isSub: false, // 是否提交
            dialogTableVisible: false, // 视频弹窗状态
            video: {
                name: '',
                src: ''
            },
            formData: {
                manager_id: this.$store.getters.token,
                classname: '', // 班级名称
                teacher: '', // 代课老师
                way: '网授', // 授课方式
                types: [], // 行业选择
                classipone: '', // 班主任手机号
                classnum: '', // 报名人数
                startclass: '', // 开始时间
                endclass: '', // 结束时间
                course: []// 已选课程
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
            chapters: []// 章节列表
        }
    },
    created() {
        if (this.$route.query.type === 'edit' && this.$route.query.id) {
            this.init(this.$route.query.id)
        }
    },
    mounted() {
        // for (const item of this.tableData) { // 默认全选
        //     this.$refs.refTable.toggleRowSelection(item)
        // }
    },
    methods: {
        init(id) {
            detailsclass({ classid: id }).then(res => {
                console.log('info', res)
            })
        },
        lazyLoad(node, resolve) { // 行业分类 加载项
            const { level } = node
            const url = level + 1
            const params = {}
            if (level) { params['id'] = node.value }
            let nodes = []
            getIndustryType(params, url).then(res => {
                nodes = res.data.map(item => {
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
        onSubmit() { // 数据提交
            this.$refs.formName.validate((valid) => {
                if (valid) {
                    this.isSub = true
                    addclass(this.formData).then(res => {
                        this.$message.success(res.msg)
                        this.isSub = false
                        this.$router.push('/class/recruitStudent')
                    }).catch(() => {
                        this.isSub = false
                    })
                } else {
                    return false
                }
            })
        },
        toogleExpandTable(row) { // 展开 收起功能（暂无使用
            this.$refs.refTable.toggleRowExpansion(row)
        },
        previewVideo(row) { // 视频预览
            if (!row.url) return this.$message('缺少视频路径')
            this.video = {
                name: row.title || '暂无标题',
                src: row.url
            }
            this.dialogTableVisible = true
        },
        handleClose(done) { // 关闭视频弹窗
            this.video.src = ''
            done()
        },

        handleSelectionChange(row) { // 多选，选中数据，处理
            const arr = row.map(e => e.id)
            this.formData.course = arr.length ? arr.join(',') : ''
        },
        changeDate(time) { // 时间选择器
            this.formData.startclass = time ? time[0] : ''
            this.formData.endclass = time ? time[1] : ''
        },
        getChapterList() { // 获取章
            this.chapters = []
            if (this.formData.types.length) {
                this.isChapter = true
                chapterlist({ types: this.formData.types }).then(res => {
                    const { data } = res
                    this.chapters = data
                    this.$nextTick(() => {
                        for (const item of data) { // 默认全选
                            this.$refs.refTable.toggleRowSelection(item)
                        }
                    })
                    this.isChapter = false
                }).catch(() => {
                    this.chapters = []
                    this.isChapter = false
                })
            }
        },
        expandChange(row, expandedRows) { // 获取节
            if (expandedRows.length) {
                const { id } = row
                this.isChildren = true
                for (const item of this.chapters) {
                    if (item.id === id) {
                        getKnotList({ id }).then(res => {
                            console.log(res, 444444)
                            item['children'] = res.data
                            this.isChildren = false
                        }).catch(() => {
                            this.isChildren = false
                        })
                    }
                }
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
                    max-width: 550px;
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
