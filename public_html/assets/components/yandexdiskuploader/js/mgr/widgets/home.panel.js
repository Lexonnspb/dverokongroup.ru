YandexDiskUploader.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('yandexdiskuploader') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('yandexdiskuploader_tab_objects'),
                layout: 'anchor',
                items: [{
                    xtype: 'yandexdiskuploader-grid-objects',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    YandexDiskUploader.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.panel.Home, MODx.Panel);
Ext.reg('yandexdiskuploader-panel-home', YandexDiskUploader.panel.Home);
