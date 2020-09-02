<template>
  <!-- 密码修改 -->
  <div :class="{'password-box':passType}">
    <el-form
      ref="userForm"
      label-position="right"
      :model="user"
      label-width="100px"
      :rules="rules"
    >
      <el-form-item label="用户姓名" prop="name">
        <el-input v-model="user.name" readonly />
      </el-form-item>
      <el-form-item label="原始密码" prop="password">
        <el-input v-model="user.password" placeholder="请输入原始密码" />
      </el-form-item>
      <el-form-item label="新密码" prop="newpassword">
        <el-input v-model="user.newpassword" placeholder="请输入新密码" />
      </el-form-item>
      <el-form-item label="确认新密码" prop="checkPass">
        <el-input v-model="user.checkPass" placeholder="请再次输入新密码" />
      </el-form-item>
      <el-form-item label="">
        <div class="t-c">
          <el-button :loading="loading" type="primary" style="width:50%;" @click.native.prevent="subPassword">提交</el-button>
        </div>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
import { amendPassword } from '@/api/user'
export default {
    props: {
        passType: {
            type: Boolean,
            default: true
        }
    },
    data() {
        const validatePass = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入新密码'))
            } else {
                if (this.user.checkPass !== '') {
                    this.$refs.userForm.validateField('checkPass')
                }
                callback()
            }
        }
        const validatePass2 = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请再次确认新密码'))
            } else if (value !== this.user.newpassword) {
                callback(new Error('两次输入密码不一致!'))
            } else {
                callback()
            }
        }
        return {
            loading: false,
            user: {// 新增/编辑科技
                manager_id: this.$store.getters.classId,
                name: this.$store.getters.name,
                password: '',
                newpassword: '',
                checkPass: ''
            },
            rules: {
                name: [
                    { required: true, message: '姓名不能为空', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '原始密码不能为空', trigger: 'blur' }
                ],
                newpassword: [
                    { min: 6, message: '密码长度不能低于6个字符', trigger: 'blur' },
                    { required: true, validator: validatePass, trigger: 'blur' }
                ],
                checkPass: [
                    { min: 6, message: '密码长度不能低于6个字符', trigger: 'blur' },
                    { required: true, validator: validatePass2, trigger: 'blur' }
                ]
            }
        }
    },
    methods: {
        subPassword() {
            this.$refs.userForm.validate((valid) => {
                if (valid) {
                    this.loading = true
                    amendPassword(this.user).then(res => {
                        this.$message.success(res.msg)
                        this.$refs.userForm.resetFields()
                        this.loading = false
                    }).catch(() => { this.loading = false })
                } else {
                    return false
                }
            })
        }
    }

}
</script>

<style lang="scss" scoped>
 .password-box{
     width: 50%;
     min-width: 400px;
     margin: 120px auto;
 }
</style>
