YandexDiskUploader.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'yandexdiskuploader-panel-home',
            renderTo: 'yandexdiskuploader-panel-home-div'
        }]
    });
    YandexDiskUploader.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.page.Home, MODx.Component);
Ext.reg('yandexdiskuploader-page-home', YandexDiskUploader.page.Home);