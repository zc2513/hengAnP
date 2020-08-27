<template>
  <el-form
    ref="ruleForm"
    :model="ruleForm"
    :rules="rules"
    size="mini"
    label-width="100px"
    class="demo-ruleForm"
  >
    <el-form-item label="长度验证" prop="name">
      <el-input v-model="ruleForm.name" />
    </el-form-item>
    <el-form-item label="电话验证" prop="tle" placeholder="">
      <el-input v-model.trim="ruleForm.tle" />
    </el-form-item>
    <el-form-item label="下拉验证" prop="region">
      <el-select v-model="ruleForm.region" placeholder="请选择活动区域">
        <el-option label="区域一" value="shanghai" />
        <el-option label="区域二" value="beijing" />
      </el-select>
    </el-form-item>
    <el-form-item label="时间验证" required>
      <el-col :span="11">
        <el-form-item prop="date1">
          <el-date-picker v-model="ruleForm.date1" type="date" placeholder="选择日期" style="width: 100%;" />
        </el-form-item>
      </el-col>
      <el-col class="line" style="text-align:center;" :span="2">-</el-col>
      <el-col :span="11">
        <el-form-item prop="date2">
          <el-time-picker v-model="ruleForm.date2" placeholder="选择时间" style="width: 100%;" />
        </el-form-item>
      </el-col>
    </el-form-item>
    <el-form-item label="滑块验证" prop="delivery">
      <el-switch v-model="ruleForm.delivery" />
    </el-form-item>
    <el-form-item label="多选验证" prop="type">
      <el-checkbox-group v-model="ruleForm.type">
        <el-checkbox label="美食/餐厅线上活动" name="type" />
        <el-checkbox label="地推活动" name="type" />
        <el-checkbox label="线下主题活动" name="type" />
        <el-checkbox label="单纯品牌曝光" name="type" />
      </el-checkbox-group>
    </el-form-item>
    <el-form-item label="单选验证" prop="resource">
      <el-radio-group v-model="ruleForm.resource">
        <el-radio label="线上品牌商赞助" />
        <el-radio label="线下场地免费" />
      </el-radio-group>
    </el-form-item>
    <el-form-item
      label="数字验证"
      prop="number"
      :rules="[
        { required: true, message: '输入框不能为空'},
        { type: 'number', message: '必须为数字值'}
      ]"
    >
      <el-input v-model.number="ruleForm.number" type="text" autocomplete="off" />
    </el-form-item>
    <el-form-item label="文本验证" prop="desc">
      <el-input v-model="ruleForm.desc" type="textarea" />
    </el-form-item>

    <el-form-item label="同内容验证" prop="pass">
      <el-input v-model="ruleForm.pass" type="text" />
    </el-form-item>
    <el-form-item label="同内容验证" prop="checkPass">
      <el-input v-model="ruleForm.checkPass" type="text" />
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submitForm('ruleForm')">立即创建</el-button>
      <el-button @click="resetForm('ruleForm')">重置</el-button>
    </el-form-item>
  </el-form>
  <!--
    解决密码回填问题
    方法一：< autocomplete="new-password">  但不能解决点击后显示选择回填密码
    方法二：思路
        click 事件：输入框被点击时，让当前输入框的type变为text
        input 事件： 当有输入值时，让当前输入框的type变为password，当输入内容的length为0时，再让其type变为text
  -->
</template>
<script>
import { phoneValidate } from '@/utils/formValidate'
export default {
    name: 'Form',
    data() {
        var validatePass = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入密码'))
            } else {
                if (this.ruleForm.checkPass !== '') {
                    this.$refs.ruleForm.validateField('checkPass')
                }
                callback()
            }
        }
        var validatePass2 = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请再次输入密码'))
            } else if (value !== this.ruleForm.pass) {
                callback(new Error('两次输入密码不一致!'))
            } else {
                callback()
            }
        }
        return {
            ruleForm: {
                name: '测长度',
                region: '',
                date1: '',
                date2: '',
                delivery: false,
                type: [],
                resource: '',
                desc: '',
                number: '',
                phone: '',
                pass: '你好啊',
                checkPass: '你好',
                tle: ''
            },
            rules: {
                name: [
                    { required: true, message: '请输入活动名称', trigger: 'blur' },
                    { min: 4, max: 10, message: '长度在 4 到 10 个字符', trigger: 'blur' }
                ],
                region: [
                    { required: true, message: '请选择活动区域', trigger: 'change' }
                ],
                date1: [
                    { type: 'date', required: true, message: '请选择日期', trigger: 'change' }
                ],
                date2: [
                    { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
                ],
                type: [
                    { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
                ],
                resource: [
                    { required: true, message: '请选择活动资源', trigger: 'change' }
                ],
                desc: [
                    { required: true, message: '请填写活动形式', trigger: 'blur' }
                ],
                pass: [
                    { validator: validatePass, trigger: 'blur' }
                ],
                checkPass: [
                    { validator: validatePass2, trigger: 'blur' }
                ],
                tle: [
                    { required: true, message: '请填写联系方式', trigger: 'blur' },
                    { validator: phoneValidate, trigger: 'change' }
                ]
            }
        }
    },
    created() {
        console.log('重新被加载')
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    alert('submit!')
                } else {
                    console.log('error submit!!')
                    return false
                }
            })
        },
        resetForm(formName) {
            this.$refs[formName].resetFields()
        }
    }
}
</script>
