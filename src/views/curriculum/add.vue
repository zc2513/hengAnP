<template>
  <div class="drawer-box">
    <h4 v-if="pageType === 'edit'">编辑课件</h4>
    <h4 v-if="pageType === 'add'">新增课件</h4>
    <div class="drawer-from">
      <el-form
        ref="ruleForm"
        label-position="top"
        :model="formData"
        :rules="rules"
      >
        <el-form-item label="课件名称" prop="name">
          <el-input v-model="formData.name" />
        </el-form-item>
        <el-form-item label="培训类型" prop="type">
          <el-select v-model="formData.type" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="培训讲师" prop="teacher">
          <el-input v-model="formData.teacher" />
        </el-form-item>
        <el-form-item label="课件图片" prop="img">
          <el-upload
            action=""
            drag
            :multiple="false"
            :show-file-list="false"
            :on-change="kjChange"
          >
            <img v-if="formData.img" :src="formData.img" class="avatar" width="360" height="180">
            <i class="el-icon-upload" />
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <p class="fontGay" style="height:14px;">建议上传尺寸 640*365</p>
          </el-upload>
        </el-form-item>
        <el-form-item label="课件说明" prop="des">
          <el-input
            v-model="formData.des"
            maxlength="200"
            show-word-limit
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 6 }"
          />
        </el-form-item>
      </el-form>
    </div>
    <div class="drawer-footer flsb">
      <div>
        <el-button size="mini" type="primary" @click="subData">确定</el-button>
      </div>
      <div>
        <el-button size="mini">取 消</el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: {
        editData: {
            type: Object,
            default: _ => {}
        }
    },
    data() {
        return {
            pageType: '',
            fileList: [],
            formData: {// 新增/编辑科技
                name: '',
                type: '',
                teacher: '',
                img: '',
                des: ''
            },
            rules: {
                name: [
                    { required: true, message: '课件名称不能为空', trigger: 'blur' }
                ],
                type: [
                    { required: true, message: '请选培训类型', trigger: 'change' }
                ],
                teacher: [
                    { required: true, message: '讲师不能为空', trigger: 'blur' }
                ],
                img: [
                    { required: true, message: '请上传封面图片', trigger: 'change' }
                ],
                des: [
                    { required: true, message: '请填写课件描述信息', trigger: 'blur' }
                ]
            },
            options: [{
                value: '选项1',
                label: '黄金糕'
            }, {
                value: '选项2',
                label: '双皮奶'
            }]
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
    methods: {
        kjChange(file) {
            this.formData.img = URL.createObjectURL(file.raw)
        },
        subData() {
            this.$refs.ruleForm.validate((valid) => {
                console.log(valid, 111)
                if (!valid) return
            })
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

}
</style>
