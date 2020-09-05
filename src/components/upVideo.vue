<template>
  <div class="upload-box">
    <div class="upload" @click="upfileVideo">
      <div v-if="videoShow" style="text-align: center;">
        <template v-if="percentage == 0">
          <i :class="btnIcon" />
          <div class="el-upload__text">{{ btnText }}</div>
        </template>
        <el-progress v-else type="circle" :percentage="percentage" />
      </div>
      <div v-else class="video-box">
        <video v-if="percentage == 100" controls :src="videoSrc" class="video" width="100%" />
        <div class="delete">
          <el-button type="danger" icon="el-icon-delete" size="mini" plain @click.stop="deleteVideo" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
// eslint-disable-next-line no-unused-vars
import { videoTranscoding, getVideoUrl } from '@/api/video'
export default {
    data() {
        return {
            configOptions: { // 视频配置项
                timeout: 60000,
                partSize: 1048576,
                parallel: 5,
                retryCount: 3,
                retryDuration: 2,
                region: 'cn-shanghai',
                userId: '1734297840212696'
                // userId: '1303984639806000'
            },
            uploader: null,
            percentage: 0, // 进度
            videoSrc: '', // video Src
            videoShow: true,
            btnText: '点击上传视频...',
            btnIcon: 'el-icon-upload'
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
            this.resetUpfile()
        },

        upfileVideo() {
            const ipt = document.createElement('input')
            ipt.type = 'file'
            ipt.accept = 'video/*'
            // ipt.style.width = '0px'; ipt.style.height = '0px'; ipt.style.display = 'none'; dom.appendChild(ipt);// 当前行 纯粹是为了解决win7 ie不触发问题
            ipt.addEventListener('change', () => {
                this.resetUpfile()
                this.btnText = '正在添加文件...'
                this.btnIcon = 'el-icon-loading'
                this.fileVideoChange(ipt)
            })
            ipt.click()
        },
        fileVideoChange(ipt) {
            const file = ipt.files[0]
            if (!file) return this.$message.error('请先选择需要上传的文件!')
            if (!file.type.startsWith('video/')) {
                this.$message.error('文件格式不正确')
                this.resetUpfile()
                return
            }
            if (this.uploader) {
                this.uploader.stopUpload()
            }
            this.uploader = this.createUploader()
            this.uploader.addFile(file, null, null, null, '{"Vod":{}}')
        },
        // 开始上传
        onUploadstarted() {
            if (this.uploader !== null) {
                this.uploader.startUpload()
            }
        },
        // 创建上传对象
        createUploader() {
            // eslint-disable-next-line no-undef
            const uploader = new AliyunUpload.Vod({
                ...this.configOptions,
                // 添加文件成功
                addFileSuccess: (uploadInfo) => {
                    this.btnText = '已添加到上传列表，等待上传...'
                    this.btnIcon = 'el-icon-upload'
                    this.onUploadstarted()
                },
                // 开始上传
                onUploadstarted: (uploadInfo) => {
                    const stsUrl = 'http://w.safetymf.com/index.php/Wechat/Upload/index?Videoname=测试数据啊&CoverURL=https://dss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=2534506313,1688529724&fm=26&gp=0.jpg&Description=我是描述信息'
                    axios.get(stsUrl).then((res) => { // 阿里云签名
                        const { UploadAuth, UploadAddress, VideoId } = res.data
                        uploader.setUploadAuthAndAddress(uploadInfo, UploadAuth, UploadAddress, VideoId)
                        /*  sts形式
                        const { AccessKeySecret, SecurityToken, AccessKeyId } = res.data.SecurityTokenInfo
                        uploader.setSTSToken(uploadInfo, AccessKeyId, AccessKeySecret, SecurityToken)
                        */
                    })
                },
                // 文件上传成功
                onUploadSucceed: (uploadInfo) => {
                    videoTranscoding(uploadInfo.videoId)
                    this.$emit('input', uploadInfo.videoId)
                    this.videoSrc = window.URL.createObjectURL(uploadInfo.file)
                },
                // 文件上传失败
                onUploadFailed: (uploadInfo, code, message) => {
                    this.$message.error('上传失败!')
                    this.resetUpfile()
                },
                // 上传进度
                onUploadProgress: (uploadInfo, totalSize, progress) => {
                    this.percentage = Math.ceil(progress * 100)
                    if (this.percentage === 100) {
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
        },

        resetUpfile() { // 重置上传参数
            this.percentage = 0
            this.videoSrc = ''
            this.videoShow = true
            this.btnText = '点击上传视频...'
            this.btnIcon = 'el-icon-upload'
            this.$emit('input', '')
        }
    }
}
</script>

<style lang="scss" scoped>
.upload-box {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    .upload {
        width: 100%;
        height: 100%;
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
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
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
            .video{
                width: 100%;
                max-width: 100%;
                max-height: 100%;
            }
        }
    }
}

</style>
