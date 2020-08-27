<template>
  <el-form :inline="true" :model="formData" size="small" class="demo-form-inline mt15">
    <el-form-item label="学员姓名">
      <el-input v-model="formData.name" clearable placeholder="学员姓名" />
    </el-form-item>
    <el-form-item label="身份证号">
      <el-input v-model="formData.cardId" clearable placeholder="身份证号" />
    </el-form-item>
    <el-form-item label="手机号码">
      <el-input v-model="formData.phone" clearable placeholder="手机号码" />
    </el-form-item>
    <el-form-item>
      <el-button type="primary" style="width:100px;" round @click="onSubmit">搜索</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                name: '',
                cardId: '',
                phone: ''
            }
        }
    },
    methods: {
        onSubmit() {
            this.$emit('search', this.formData)
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
