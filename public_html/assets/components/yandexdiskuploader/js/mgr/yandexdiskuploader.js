var YandexDiskUploader = function (config) {
    config = config || {};
    YandexDiskUploader.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader, Ext.Component, {
    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    config: {},
    view: {},
    utils: {},
    ux: {},
    fields: {},
});
Ext.reg('yandexdiskuploader', YandexDiskUploader);

YandexDiskUploader = new YandexDiskUploader();