<template>
  <div class="upload-box">
    <div class="upload" @click="upfileVideo">
      <div v-if="videoShow" style="text-align: center;">
        <template v-if="percentage == 0">
          <i class="el-icon-upload" />
          <div class="el-upload__text">{{ isUp ? "正在添加文件..." : '点击上传视频' }}</div>
        </template>
        <el-progress v-else type="circle" :percentage="percentage" />
      </div>
      <div v-else class="video-box">
        <video v-if="percentage == 100" controls autoplay :src="videoSrc" width="300" height="200" />
        <div class="delete">
          <el-button type="danger" icon="el-icon-delete" size="mini" plain @click.stop="deleteVideo" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            signData: { // 阿里云签名
                AccessKeySecret: '',
                SecurityToken: '',
                AccessKeyId: ''
            },
            configOptions: { // 视频配置项
                timeout: 60000,
                partSize: 1048576,
                parallel: 5,
                retryCount: 3,
                retryDuration: 2,
                region: 'cn-shanghai',
                userId: '1303984639806000'
            },
            file: null,
            uploader: null,
            beginDisabled: true,
            percentage: 0, // 进度
            videoSrc: '', // video Src
            videoShow: true,
            isUp: false
        }
    },
    watch: {
        percentage: {
            handler(v) {
                if (v === 100) {
                    this.videoShow = false
                }
            },
            immediate: true
        }
    },
    methods: {
        // 删除
        deleteVideo() {
            this.percentage = 0
            this.videoSrc = ''
            this.videoShow = true
            this.isUp = false
        },

        upfileVideo() {
            const ipt = document.createElement('input')
            ipt.type = 'file'
            // ipt.style.width = '0px'; ipt.style.height = '0px'; ipt.style.display = 'none'; dom.appendChild(ipt);// 当前行 纯粹是为了解决win7 ie不触发问题
            ipt.addEventListener('change', () => {
                this.isUp = true
                this.fileVideoChange(ipt)
            })
            ipt.click()
        },
        fileVideoChange(ipt) {
            this.file = ipt.files[0]
            if (!this.file) {
                alert('请先选择需要上传的文件!')
                return
            }
            var userData = '{"Vod":{}}'
            if (this.uploader) {
                this.uploader.stopUpload()
            }
            this.uploader = this.createUploader()
            this.uploader.addFile(this.file, null, null, null, userData)
            this.beginDisabled = false
        },
        // 开始上传
        onUploadstarted() {
            if (this.uploader !== null) {
                this.uploader.startUpload()
                this.beginDisabled = true
            }
        },
        // 创建上传对象
        createUploader() {
            const self = this
            // eslint-disable-next-line no-undef
            const uploader = new AliyunUpload.Vod({
                timeout: self.configOptions.timeout,
                partSize: self.configOptions.partSize,
                parallel: self.configOptions.parallel,
                retryCount: self.configOptions.retryCount,
                retryDuration: self.configOptions.retryDuration,
                region: self.configOptions.region,
                userId: self.configOptions.userId,
                // 添加文件成功
                addFileSuccess: function(uploadInfo) {
                    self.beginUpload = false
                    self.onUploadstarted()
                },
                // 开始上传
                onUploadstarted: function(uploadInfo) {
                    const stsUrl = 'http://demo-vod.cn-shanghai.aliyuncs.com/voddemo/CreateSecurityToken?BusinessType=vodai&TerminalType=pc&DeviceModel=iPhone9,2&UUID=67999yyuuuy&AppVersion=1.0.0'
                    axios.get(stsUrl).then((res) => {
                        const { AccessKeySecret, SecurityToken, AccessKeyId } = res.data.SecurityTokenInfo
                        self.signData = {
                            AccessKeySecret: AccessKeySecret,
                            SecurityToken: SecurityToken,
                            AccessKeyId: AccessKeyId
                        }
                        uploader.setSTSToken(uploadInfo, AccessKeyId, AccessKeySecret, SecurityToken)
                    })
                },
                // 文件上传成功
                onUploadSucceed: function(uploadInfo) {
                    self.videoSrc = window.URL.createObjectURL(uploadInfo.file)
                },
                // 文件上传失败
                onUploadFailed: function(uploadInfo, code, message) {
                    self.$message({
                        type: 'error',
                        message: '上传失败!',
                        duration: 800,
                        onClose: () => {
                            self.beginDisabled = false
                            self.videoSrc = ''
                            self.videoShow = true
                        }
                    })
                },
                // 上传进度
                onUploadProgress: function(uploadInfo, totalSize, progress) {
                    self.percentage = Math.ceil(progress * 100)
                    if (self.percentage === 100) {
                        this.videoShow = false
                    }
                },
                // 全部文件上传结束
                onUploadEnd: function(uploadInfo) {
                    // console.log('文件上传完毕')
                    // self.statusText = '文件上传完毕'
                }
            })
            return uploader
        }
    }
}
</script>

<style lang="scss" scoped>
.upload-box {
    width: 100%;
    height: 100%;
    .upload {
        width: 300px;
        height: 200px;
        border: 1px dashed #999;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        /deep/.el-icon-upload{
            font-size: 40px;
            color: #409eff;
        }
        /deep/.el-upload__text {
            font-size: 14px;
            color: #666;
        }
        .video-box {
            position: relative;
            &:hover{
                .delete {
                    display: inline-block;
                }
            }
            .delete {
                display: none;
                position: absolute;
                top: 0;
                right: 0;
                transform: translate(-50%,0);
                width: 30px;
                height: 30px;
                /deep/.el-button--danger {
                    border-color: transparent;
                }
            }
        }
    }
}

</style>
