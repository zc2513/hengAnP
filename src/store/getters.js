const getters = {
    sidebar: state => state.app.sidebar,
    device: state => state.app.device,
    token: state => state.user.token,
    avatar: state => state.user.avatar,
    name: state => state.user.name,
    jgname: state => state.user.jgname,
    logo: state => state.user.logo,
    classId: state => state.user.classId,
    roles: state => state.user.roles,
    visitedViews: state => state.tagsView.visitedViews,
    cachedViews: state => state.tagsView.cachedViews
}
export default getters
