<template>
  <div class="block">
    <el-pagination
      :current-page.sync="currentPage"
      :page-size="pageSize"
      :page-sizes="[8,10, 20,30]"
      layout="total ,sizes,prev, pager, next, jumper"
      :total="total"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
  </div>
</template>

<script>
export default {
    props: {
        total: {
            type: Number,
            default: 0
        },
        pageSize: {
            type: Number,
            default: 10
        },
        currentPage: {
            type: Number,
            default: 1
        }
    },
    methods: {
        handleCurrentChange(val) {
            this.$emit('pagesend', val)
        },
        handleSizeChange(val) {
            this.$emit('pagesizes', val)
        }
    }
}
</script>

<style scoped>
    .block{
        text-align: right;
    }
</style>
