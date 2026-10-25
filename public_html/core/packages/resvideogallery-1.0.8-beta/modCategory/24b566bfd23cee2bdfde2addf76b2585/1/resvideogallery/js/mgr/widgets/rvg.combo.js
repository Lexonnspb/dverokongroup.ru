Rvg.combo.Tags = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur : true,
        pinList: false,
        resizable: true,
      //  name: 'tags',
        anchor: '100%',
        minChars: 2,
        //pageSize: 10,
        store:new Ext.data.JsonStore({
            root: 'results',
            autoLoad: false,
            autoSave: false,
            totalProperty: 'total',
            fields: ['tag'],
            url: Rvg.config.connectorUrl,
            baseParams: {
                action: 'mgr/video/gettags'
            }
        }),
        mode: 'remote',
        displayField: 'tag',
        valueField: 'tag',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls:'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        listeners: {
            newitem: function(bs, v) {
                bs.addNewItem({tag: v});
            }
        }
    });
    config.name += '[]';
    Rvg.combo.Tags.superclass.constructor.call(this,config);
};
Ext.extend(Rvg.combo.Tags,Ext.ux.form.SuperBoxSelect);
Ext.reg('resvideogallery-combo-tags',Rvg.combo.Tags);


Rvg.combo.Search = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('resvideogallery.video.search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    Rvg.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function() {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(Rvg.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function() {
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

    _triggerSearch: function() {
        this.fireEvent('search', this);
    },

    _triggerClear: function() {
        this.fireEvent('clear', this);
    },

});
Ext.reg('resvideogallery-field-search', Rvg.combo.Search);