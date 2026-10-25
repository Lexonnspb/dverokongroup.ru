Rvg.panel.ResVideoGallery = function(config) {
	config = config || {};

	Ext.apply(config, {
		border: false,
		id: 'resvideogallery-page',
		baseCls: 'x-panel ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		items: [{
			border: false,
			style: {padding: '10px 5px'},
			xtype: 'resvideogallery-page-toolbar',
			id: 'resvideogallery-page-toolbar',
			record: config.record,
		},{
			border: false,
			style: {padding: '5px'},
			layout: 'anchor',
			items: [{
				border: false,
				xtype: 'resvideogallery-videos-panel',
				id: 'resvideogallery-videos-panel',
				cls: 'modx-pb-view-ct',
				resource_id: config.record.id,
				pageSize: config.pageSize || Rvg.config.pageSize
			}]
		}]
	});
	Rvg.panel.ResVideoGallery.superclass.constructor.call(this, config);
};
Ext.extend(Rvg.panel.ResVideoGallery, MODx.Panel, {});
Ext.reg('resvideogallery-page', Rvg.panel.ResVideoGallery);