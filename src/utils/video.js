export const player = function({ source, cover }, id) {
    const config = {
        'id': id, // 播放器外层容器的dom元素id。
        'cover': cover || '', // 播放器默认封面图片，请填写正确的图片url地址。需要autoplay为’false’时，才生效。
        'source': source, // 视频播放地址 支持多清晰度
        'width': '100%', // 播放器宽度
        'autoplay': false, // 播放器是否自动播放，在移动端autoplay属性会失效。Safari11不会自动开启自动播放
        'isLive': false, // 播放内容是否为直播，直播时会禁止用户拖动进度条。
        'rePlay': false, // 播放器自动循环播放。
        'playsinline': true, // H5是否内置播放，有的Android浏览器不起作用。
        'preload': true, // 播放器自动加载，目前仅h5可用。
        'useH5Prism': true, // 指定使用H5播放器。
        'useFlashPrism': false, // 指定使用Flash播放器
        'showBuffer': true, // 显示播放时缓冲图标，默认true
        'showBarTime': 2000, // 控制栏自动隐藏时间（ms）
        // 'skinRes': '', // Url 皮肤图片，不建议随意修改该字段，如要修改，请参照皮肤定制。
        'skinLayout': [// Array | Boolean 功能组件布局配置，不传该字段使用默认布局。传false隐藏所有功能组件 请参照皮肤定制。
            { 'name': 'bigPlayButton', 'align': 'blabs', 'x': 30, 'y': 80 },
            { 'name': 'H5Loading', 'align': 'cc' },
            { 'name': 'errorDisplay', 'align': 'tlabs', 'x': 0, 'y': 0 },
            { 'name': 'infoDisplay' },
            { 'name': 'tooltip', 'align': 'blabs', 'x': 0, 'y': 56 },
            { 'name': 'thumbnail' },
            { 'name': 'controlBar', 'align': 'blabs', 'x': 0, 'y': 0,
                'children': [
                    { 'name': 'playButton', 'align': 'tl', 'x': 15, 'y': 12 }, // 播放/暂停
                    { 'name': 'timeDisplay', 'align': 'tl', 'x': 10, 'y': 7 }, // 时间
                    { 'name': 'fullScreenButton', 'align': 'tr', 'x': 10, 'y': 12 }, // 全屏按钮
                    // {"name": "subtitle","align": "tr","x": 15,"y": 12},//字幕按钮
                    // {"name": "setting","align": "tr","x": 15,"y": 12},//设置按钮
                    { 'name': 'volume', 'align': 'tr', 'x': 5, 'y': 10 }// 音量按钮
                ]
            }
        ],
        'controlBarVisibility': 'hover', // 控制面板的实现 可选的值为：‘click’、‘hover’、‘always’
        //   "format":"",//指定播放地址格式 只有使用vid的播放方式时支持 目前未使用vid
        components: [{
            name: 'RateComponent',
            // eslint-disable-next-line no-undef
            type: AliPlayerComponent.RateComponent
        }],
        // eslint-disable-next-line no-dupe-keys
        'useH5Prism': true
    }
    // eslint-disable-next-line no-undef
    return new Aliplayer(config)
}
