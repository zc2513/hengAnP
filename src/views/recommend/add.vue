<template>
  <div class="drawer-box">
    <h4 v-if="pageType === 'edit'">编辑课件</h4>
    <h4 v-if="pageType === 'add'">新增课件</h4>
    <div class="drawer-from">
      <!-- label-position="top" -->
      <el-form
        ref="ruleForm"
        :model="formData"
        :rules="rules"
        label-width="80px"
      >
        <el-form-item label="课件名称" prop="title">
          <el-input v-model="formData.title" />
        </el-form-item>
        <el-form-item label="所属章节" prop="chapter_id">
          <el-select v-model="formData.chapter_id" filterable placeholder="请选择">
            <el-option
              v-for="item in chapters"
              :key="item.id"
              :label="item.chapter_title"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="培训讲师" prop="teacher">
          <el-input v-model="formData.teacher" />
        </el-form-item>
        <el-form-item label="课件图片" prop="logo">
          <el-upload
            :action="upfileUrl"
            drag
            :multiple="false"
            :show-file-list="false"
            :on-success="upfileSuccess"
            accept="image/*"
          >
            <img v-if="formData.logo" :src="formData.logo" class="avatar">
            <i v-else class="el-icon-upload" />
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <p class="fontGay" style="height:14px;">建议上传尺寸 640*365</p>
          </el-upload>
        </el-form-item>
        <el-form-item label="课件说明" prop="content">
          <el-input
            v-model="formData.content"
            maxlength="200"
            show-word-limit
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 6 }"
          />
        </el-form-item>
        <el-form-item label="视频上传" prop="video_id">
          <div class="videoBox">
            <upVideo v-if="!formData.url" v-model="formData.video_id" />
            <video v-else controls :src="formData.url" />
          </div>
        </el-form-item>
      </el-form>
    </div>
    <div class="drawer-footer flsb">
      <div>
        <el-button size="mini" type="primary" @click="subData">确定</el-button>
      </div>
      <div>
        <el-button size="mini" @click="$emit('close')">取 消</el-button>
      </div>
    </div>
  </div>
</template>

<script>
// eslint-disable-next-line no-unused-vars
import { getChapter } from '@/api/dic'
// eslint-disable-next-line no-unused-vars
import { addcourse, updatecourse } from '@/api/curriculum'
import upVideo from '@/components/upVideo'
export default {
    components: { upVideo },
    props: {
        editData: {
            type: Object,
            default: _ => {}
        }
    },
    data() {
        return {
            upfileUrl: `${process.env.VUE_APP_BASE_API}/index.php/Wechat/ImgUpload/imgUpload`,
            pageType: '',
            fileList: [],
            formData: {// 新增/编辑科技
                title: '',
                chapter_id: '',
                teacher: '',
                logo: '',
                content: '',
                video_id: '',
                isrecommend: '1'
            },
            rules: {
                title: [
                    { required: true, message: '课件名称不能为空', trigger: 'blur' }
                ],
                chapter_id: [
                    { required: true, message: '请选当前课件所属的章', trigger: 'change' }
                ],
                teacher: [
                    { required: true, message: '讲师不能为空', trigger: 'blur' }
                ],
                logo: [
                    { required: true, message: '请上传封面图片', trigger: 'change' }
                ],
                content: [
                    { required: true, message: '请填写课件描述信息', trigger: 'blur' }
                ],
                video_id: [
                    { required: true, message: '请上传视频', trigger: ['change', 'blur'] }
                ]
            },
            chapters: []
        }
    },
    watch: {
        editData: {
            handler(v) {
                if (v) {
                    this.pageType = 'edit'
                    this.formData = v
                } else {
                    this.pageType = 'add'
                }
            },
            immediate: true
        }
    },
    created() {
        getChapter().then(res => {
            this.chapters = res.data
        })
    },
    methods: {
        subData() {
            this.$refs.ruleForm.validate((valid) => {
                if (!valid) return
                this.formData['manager_id'] = this.$store.getters.token
                let url = 'addcourse'
                if (this.pageType === 'edit') url = 'updatecourse'
                addcourse(this.formData, url).then(res => {
                    this.$message.success(res.msg)
                    this.$refs.ruleForm.resetFields()
                    this.$emit('success')
                })
            })
        },
        upfileSuccess(res, file) {
            this.formData.logo = `http://henganupload.oss-cn-beijing.aliyuncs.com/${res.data}`
        }
    }

}
</script>

<style lang="scss" scoped>
.drawer-box{
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    h4{
        padding: 20px 20px 0;
    }
    .drawer-from{
        flex: 1;
        padding: 20px;
        /deep/.el-select{
            width: 100%;
        }
    }
    .drawer-footer{
        >div{
            height: 40px;
            width: 50%;
            button{
                width:100%;
                height:100%;
                border-radius:0;
            }
        }
    }
    .videoBox{
        height:200px;
        video{
            max-width: 100%;
            max-height: 100%;
        }
    }
    .el-upload-dragger{
        img{
            max-width: 100%;
            max-height: 100%;
        }
    }

}
</style>
