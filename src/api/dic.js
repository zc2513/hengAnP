// 字典表
import request from '@/utils/request'
// import qs from 'qs'

/**
 * @description 行业类型字典
 * @author zc2513
 * @date 2020-08-30
 * @export getIndustryType
 * @param {*} [type=type1]  type1 type2 type3 type4 类型1-6
 * @returns
 */
export function getIndustryType(params, type) {
    return request({
        url: `/index.php/Master/Type/type${type}`,
        method: 'get',
        params
    })
}
