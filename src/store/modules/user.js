import { login, logout, getInfo } from '@/api/user'
import { getToken, setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'

const state = {
    token: getToken(),
    jgname: '',
    name: '',
    logo: '',
    classId: ''
}

const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token
    },
    SET_NAME: (state, name) => {
        state.name = name
    },
    SET_JGNAME: (state, jgname) => {
        state.jgname = jgname
    },
    SET_LOG: (state, logo) => {
        state.logo = logo
    },
    SET_CLASSID: (state, classId) => {
        state.classId = classId
    }
}

const actions = {
    // 登录
    login({ commit }, userInfo) {
        const { username, password } = userInfo
        return new Promise((resolve, reject) => {
            login({ username: username.trim(), password: password }).then(response => {
                const { data } = response
                commit('SET_TOKEN', data.diy_id)
                commit('SET_CLASSID', data.id)
                setToken(data.diy_id)
                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    // 获取用户信息
    getInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            getInfo(state.token).then(response => {
                const { data } = response
                if (!data) {
                    reject('获取用户信息失败，请重新登录')
                }

                const { companyname, loginname, logo } = data
                if (!companyname) {
                    reject('未找到机构名称，机构名称不能为空!')
                }

                commit('SET_JGNAME', companyname)
                commit('SET_NAME', loginname)
                commit('SET_LOG', logo)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

    // 前端登出
    logout({ commit, state, dispatch }) {
        return new Promise((resolve, reject) => {
            logout(state.token).then(() => {
                commit('SET_JGNAME', '')
                commit('SET_NAME', '')
                commit('SET_LOG', '')
                commit('SET_CLASSID', '')
                removeToken()
                resetRouter()
                // 重置已访问的视图和缓存的视图
                dispatch('tagsView/delAllViews', null, { root: true })
                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    // remove token
    resetToken({ commit }) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '')
            removeToken()
            resolve()
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}

