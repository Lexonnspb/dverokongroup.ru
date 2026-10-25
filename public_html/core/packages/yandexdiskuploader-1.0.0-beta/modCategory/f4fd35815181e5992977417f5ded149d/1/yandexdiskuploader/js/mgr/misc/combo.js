/**
 *
 * @param config
 * @constructor
 */
YandexDiskUploader.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    YandexDiskUploader.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
        this.positionEl.setStyle('margin-right', '1px');
    });
    this.addEvents('clear', 'search');
};
Ext.extend(YandexDiskUploader.combo.Search, Ext.form.TwinTriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },
    _triggerSearch: function () {
        this.fireEvent('search', this);
    },
    _triggerClear: function () {
        this.fireEvent('clear', this);
    },
});
Ext.reg('yandexdiskuploader-combo-search', YandexDiskUploader.combo.Search);
Ext.reg('yandexdiskuploader-field-search', YandexDiskUploader.combo.Search);


/**
 *
 * @param config
 * @constructor
 */
YandexDiskUploader.combo.Parent = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'parent',
        fieldLabel: config['name'] || 'parent',
        hiddenName: config['name'] || 'parent',
        displayField: 'pagetitle',
        valueField: 'id',
        fields: ['id', 'pagetitle', 'parents'],
        url: YandexDiskUploader.config['connector_url'],
        baseParams: {
            action: 'mgr/combo/getresources',
            context_key: config['context_key'] || 'web',
            isfolder: 1,
            parents: 1,
        },
        pageSize: 20,
        typeAhead: false,
        editable: true,
        minChars: 1,
        anchor: '100%',
        listEmptyText: '<div style="padding: 7px;">' + _('yandexdiskuploader_combo_list_empty') + '</div>',
        tpl: new Ext.XTemplate('\
            <tpl for="."><div class="x-combo-list-item yandexdiskuploader-combo__list-item">\
                <tpl if="parents">\
                    <div class="parents">\
                        <tpl for="parents">\
                            <nobr><small>{pagetitle} / </small></nobr>\
                        </tpl>\
                    </div>\
                </tpl>\
                <span>\
                    <small>({id})</small> <b>{pagetitle}</b>\
                </span>\
            </div></tpl>',
            {compiled: true}
        ),
    });
    YandexDiskUploader.combo.Parent.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.combo.Parent, MODx.combo.ComboBox);
Ext.reg('yandexdiskuploader-combo-parent', YandexDiskUploader.combo.Parent);


/**
 *
 * @param config
 * @constructor
 */
YandexDiskUploader.combo.Group = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'group',
        fieldLabel: config['name'] || 'group',
        hiddenName: config['name'] || 'group',
        displayField: 'display',
        valueField: 'value',
        fields: ['value', 'display'],
        url: YandexDiskUploader.config['connector_url'],
        baseParams: {
            action: 'mgr/combo/getgroups',
            filter: config['filter'] || 0,
            notempty: config['notempty'] || 1,
        },
        pageSize: 20,
        typeAhead: false,
        editable: true,
        anchor: '100%',
        listEmptyText: '<div style="padding: 7px;">' + _('yandexdiskuploader_combo_list_empty') + '</div>',
        tpl: new Ext.XTemplate('\
            <tpl for="."><div class="x-combo-list-item yandexdiskuploader-combo__list-item">\
                <span>\
                    {display}\
                </span>\
            </div></tpl>',
            {compiled: true}
        ),
    });
    YandexDiskUploader.combo.Group.superclass.constructor.call(this, config);
};
Ext.extend(YandexDiskUploader.combo.Group, MODx.combo.ComboBox);
Ext.reg('yandexdiskuploader-combo-group', YandexDiskUploader.combo.Group);