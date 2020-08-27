<template>
  <div class="login-container">
    <div class="login-header flc-y">
      <img class="ml15" src="@/assets/login/logoIndex.png" width="350" height="56">
    </div>
    <div class="main flcc">
      <div class="main-box flc-y flsb">
        <img src="@/assets/login/slogon.png" class="leftImg">
        <div class="login-from">
          <div class="title mb20"> 欢迎使用本系统 </div>
          <el-form
            ref="loginForm"
            size="mini"
            :model="loginForm"
            :rules="loginRules"
            class="login-form"
            auto-complete="on"
            label-position="right"
            hide-required-asterisk
          >
            <el-form-item prop="username">
              <span class="svg-container">
                <svg-icon icon-class="user" />
              </span>
              <el-input
                ref="username"
                v-model="loginForm.username"
                placeholder="请输入用户名"
                name="username"
                type="text"
                tabindex="1"
                auto-complete="on"
              />
            </el-form-item>

            <el-form-item prop="password">
              <span class="svg-container">
                <svg-icon icon-class="password" />
              </span>
              <el-input
                :key="passwordType"
                ref="password"
                v-model="loginForm.password"
                :type="passwordType"
                placeholder="请输入密码"
                name="password"
                tabindex="2"
                auto-complete="on"
                @keyup.enter.native="handleLogin"
              />
              <span class="show-pwd" @click="showPwd">
                <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
              </span>
            </el-form-item>
            <el-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;" @click.native.prevent="handleLogin">登录</el-button>
          </el-form>
        </div>
      </div>
    </div>
    <div class="floor flcc">
      <div class="flc-y">
        <img src="@/assets/login/qrcode.png" width="75" height="75">
        <p class="pl15"> 陕ICP备18001791号-1      版权所有©西安华数恒安信息技术服务有限责任公司      All Rights Reserved</p>
      </div>
    </div>
  </div>
</template>

<script>

export default {
    name: 'Login',
    data() {
        const validatePassword = (rule, value, callback) => {
            if (value.length < 6) {
                callback(new Error('密码不能少于6位'))
            } else {
                callback()
            }
        }
        return {
            loginForm: {
                username: '',
                password: ''
            },
            loginRules: {
                username: [{ required: true, trigger: 'blur', message: '请输入用户名称' }],
                password: [{ required: true, trigger: 'blur', validator: validatePassword }]
            },
            loading: false,
            passwordType: 'password',
            redirect: undefined
        }
    },
    watch: {
        $route: {
            handler: function(route) {
                this.redirect = route.query && route.query.redirect
            },
            immediate: true
        }
    },
    methods: {
        showPwd() {
            if (this.passwordType === 'password') {
                this.passwordType = ''
            } else {
                this.passwordType = 'password'
            }
            this.$nextTick(() => {
                this.$refs.password.focus()
            })
        },
        handleLogin() {
            this.$refs.loginForm.validate(valid => {
                if (valid) {
                    this.loading = true
                    this.$store.dispatch('user/login', this.loginForm).then(() => {
                        this.$router.push({ path: this.redirect || '/' })
                        this.loading = false
                    }).catch(() => {
                        this.loading = false
                    })
                } else {
                    console.log('error submit!!')
                    return false
                }
            })
        }
    }
}
</script>

<style lang="scss">
/*
@supports 修复光标使用Chrome记录的账号之后变黑色;
完善记住密码之后颜色和背景不一致;
*/

$bg:#e5e5e5; //输入框使用记住密码的背景颜色
$light_gray:#333;//输入框直接输入的字体颜色
$cursor: #333;////输入框使用记住密码的字体颜色

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {//不用IE我们大家都是好朋友
  .login-container .el-input input {
    color: $cursor;
  }
}

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 100%;
    width: 85%;
    display: flex;
    align-items: center;
    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 30px;
      caret-color: $cursor;
      &:-webkit-autofill {
        box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: $cursor !important;
      }
    }
  }

  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
  .el-form-item__content {
      height: 36px;
      position: relative;
      font-size: 14px;
      display: flex;
      align-items: center;
  }
}
</style>

<style lang="scss" scoped>
$dark_gray:#afc8d3;
$light_gray:#eee;
.login-container {
  width: 100%;
  height: 100vh;
  background-color: #fff;
  overflow: hidden;
  .login-header{
      height: 10vh;
  }
  .main{
        height: 80vh;
        background-image: url('../../assets/login/bg.png');
        background-repeat: no-repeat;
        background-size: 100% 100%;
        .main-box{
            width: 70%;
            max-width: 1200px;
            height: 100%;
            .leftImg{
                width: 40%;
                max-width: 400px;
                min-width: 300px;
            }
        }

        .login-from{
            background-color: #fff;
            border-radius: 20px;
            width: 380px;
            min-width: 350px;
            height: 280px;
            padding: 30px;
        }
        .title{
            color: rgba(0,102,255,1);
            font-size: 24px;
        }
  }
  .floor{
      >div{
          width: 70%;
          max-width: 1200px;
      }
    height: 10vh;
    font-size: 12px;
    color: gray;
  }

  .login-form {
    position: relative;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
  }

  .svg-container {
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
}
</style>
