<template>
  <el-form :inline="true" :model="formData" size="small" class="demo-form-inline mt15">
    <el-form-item label="课件名称">
      <el-input v-model="formData.title" clearable placeholder="课件名称" />
    </el-form-item>
    <el-form-item label="讲师姓名">
      <el-input v-model="formData.teacher" clearable placeholder="讲师姓名" />
    </el-form-item>
    <!-- <el-form-item label="课件状态">
      <el-select v-model="formData.status" placeholder="请选择">
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
    </el-form-item> -->
    <!-- <el-form-item label="岗位类型">
      <el-select v-model="formData.post" placeholder="请选择">
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
    </el-form-item> -->
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
                title: '',
                // status: '',
                teacher: ''
                // post: ''
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
    methods: {
        onSubmit() {
            this.$emit('search', this.formData)
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
