/**
 * Вкладки/поля для окон добавления/редактирования
 *
 * @param config
 * @returns {{object}}
 * @constructor
 */
YandexDiskUploader.fields.Object = function (config) {
    var data = config['record'] ? config.record['object'] : null;

    var r = {
        xtype: 'modx-tabs',
        border: true,
        autoHeight: true,
        // style: {marginTop: '10px'},
        anchor: '100% 100%',
        items: [{
            title: _('yandexdiskuploader_tab_main'),
            layout: 'form',
            cls: 'modx-panel yandexdiskuploader-panel',
            autoHeight: true,
            items: [],
        }],
        listeners: {
            afterrender: function (tabs) {
                // Рендерим вторую вкладку, иначе данные с неё не передаются в процессор
                tabs.setActiveTab(1);
                tabs.setActiveTab(0);
                
                if (config['activeTab']) {
                    tabs.setActiveTab(config['activeTab']);
                }
            },
        },
    };

    r.items[0].items.push({
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: .5,
            layout: 'form',
            style: {marginRight: '5px'},
            items: [{
                xtype: 'yandexdiskuploader-combo-group',
                id: config.id + '-group',
                name: 'group',
                fieldLabel: _('yandexdiskuploader_field_group'),
                anchor: '100%',
            }, {
                xtype: 'textfield',
                id: config.id + '-name',
                name: 'name',
                fieldLabel: _('yandexdiskuploader_field_name'),
                anchor: '100%',
            }],
        }, {
            columnWidth: .5,
            layout: 'form',
            style: {marginLeft: '5px'},
            items: [{
                xtype: 'textarea',
                id: config.id + '-description',
                name: 'description',
                fieldLabel: _('yandexdiskuploader_field_description'),
                height: 102,
                anchor: '100%',
            }],
        }],
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '0px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'yandexdiskuploader-combo-parent',
                id: config.id + '-parent',
                name: 'parent',
                fieldLabel: _('yandexdiskuploader_field_parent'),
                anchor: '100%',
            }],
        }],
    }, {
        layout: 'column',
        border: false,
        style: {marginTop: '-10px'},
        anchor: '100%',
        items: [{
            columnWidth: 1,
            layout: 'form',
            items: [{
                xtype: 'xcheckbox',
                id: config.id + '-active',
                name: 'active',
                boxLabel: _('yandexdiskuploader_field_active'),
            }],
        }],
    });

    if (data) {
        r.items[0].items.push({
            xtype: 'hidden',
            id: config.id + '-id',
            name: 'id',
        });
    }

    return r;
};

/**
 * Окно добавления объекта
 *
 * @param config
 * @constructor
 */
YandexDiskUploader.window.ObjectCreate = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'yandexdiskuploader-window-object-create';
    }
    Ext.applyIf(config, {
        title: _('yandexdiskuploader_window_object_create'),
        baseParams: {
            action: 'mgr/object/create',
        },
        modal: true,
    });
    YandexDiskUploader.window.ObjectCreate.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.window.ObjectCreate, YandexDiskUploader.window.Default, {
    getFields: function (config) {
        return YandexDiskUploader.fields.Object(config);
    },
});
Ext.reg('yandexdiskuploader-window-object-create', YandexDiskUploader.window.ObjectCreate);

/**
 * Окно редактирования объекта
 *
 * @param config
 * @constructor
 */
YandexDiskUploader.window.ObjectUpdate = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'yandexdiskuploader-window-object-update';
    }
    Ext.applyIf(config, {
        title: _('yandexdiskuploader_window_object_update'),
        baseParams: {
            action: 'mgr/object/update',
        },
        modal: true,
    });
    YandexDiskUploader.window.ObjectUpdate.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.window.ObjectUpdate, YandexDiskUploader.window.Default, {
    getFields: function (config) {
        return YandexDiskUploader.fields.Object(config);
    },
});
Ext.reg('yandexdiskuploader-window-object-update', YandexDiskUploader.window.ObjectUpdate);