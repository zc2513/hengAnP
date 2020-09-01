<template>
    <div>
    上传          
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            //参数
            videoFlag: false,
            //是否显示进度条
            videoUploadPercent: "",
            //进度条的进度，
            isShowUploadVideo: false,
            //显示上传按钮
            videoForm: {
                showVideoPath: ''
            },
            signData:{ // 签名
                AccessKeySecret:'',
                SecurityToken:'',
                AccessKeyId:''
            }
        }
    },
    methods: {
        // 上传文件之前的钩子
        beforeUpload(file){
            console.log(file,777)
            let stsUrl = 'http://demo-vod.cn-shanghai.aliyuncs.com/voddemo/CreateSecurityToken?BusinessType=vodai&TerminalType=pc&DeviceModel=iPhone9,2&UUID=67999yyuuuy&AppVersion=1.0.0'
            axios.get(stsUrl).then((res) => {
                const { AccessKeySecret,SecurityToken,AccessKeyId } = res.data.SecurityTokenInfo 
                this.signData = {
                    AccessKeySecret: AccessKeySecret,
                    SecurityToken: SecurityToken,
                    AccessKeyId: AccessKeyId
                }
            })
            // 验证文件大小和格式

        },
        // 成功
        handleVideoSuccess(response, file, fileList) {
             console.log(response, file, fileList,'成功')
        },
        // 进度
        uploadVideoProcess(event, file, fileList){
            console.log(event, file, fileList,'进度')
        }
    }
}
</script>

<style lang="scss" scoped>

</style>