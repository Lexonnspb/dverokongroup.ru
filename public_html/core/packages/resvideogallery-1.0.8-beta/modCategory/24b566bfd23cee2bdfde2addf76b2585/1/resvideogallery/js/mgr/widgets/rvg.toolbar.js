Rvg.panel.Toolbar = function(config) {
	config = config || {};
	Ext.apply(config, {
		id: 'resvideogallery-page-toolbar',
		items: [{
			xtype: 'button',
			cls: 'primary-button',
			text: '<i class="' + (MODx.modx23 ? 'icon icon-' : 'fa fa-') + 'plus"></i> ' + _('resvideogallery.btn.add_video'),
			listeners: {
				'click': {fn: this.addVideo, scope: this}
			}
		},{
			xtype: 'button',
			text: '<i class="' + (MODx.modx23 ? 'icon icon-' : 'fa fa-') + 'refresh"></i> ' + _('resvideogallery.btn.reload_thumb_all'),
			listeners: {
				'click': {fn: this.reloadThumbAll, scope: this}
			}
		}]
	});
	Rvg.panel.Toolbar.superclass.constructor.call(this, config);
	this.config = config;
};
Ext.extend(Rvg.panel.Toolbar, Ext.Toolbar, {
	videoReloadTotal: 10
	,videoReloadComplete:0
	,videoReloadIds: []
	,addVideo:function(e){
		var w = MODx.load({
			xtype: 'resvideogallery-gallery-video',
			record: {
				resource_id:this.config.record.id,
				active:1
			},
			listeners: {
				success: {fn:function() {
					Ext.getCmp('resvideogallery-videos-view').getStore().reload();
				},scope: this}
			}
		});
		w.show(e.target);
	},
	reloadThumb:function(){
		MODx.Ajax.request({
			url: Rvg.config.connectorUrl,
			params: {
				action: 'mgr/video/reloadthumb',
				id: this.videoReloadIds[this.videoReloadComplete]
			},
			listeners: {
				success: {
					fn: function (e) {
						this.videoReloadComplete ++;
						if(this.videoReloadTotal > this.videoReloadComplete) {
							Ext.MessageBox.updateProgress((this.videoReloadComplete/this.videoReloadTotal), this.videoReloadTotal + '/' + this.videoReloadComplete);
							this.reloadThumb();
						} else {
							Ext.Msg.hide();
							Ext.getCmp('resvideogallery-videos-view').getStore().reload();
						}

					}, scope: this
				},
				failure: {
					fn: function (response) {
						MODx.msg.alert(_('error'), response.message);
					}, scope: this
				},
			}
		});
	},
	reloadThumbAll: function(e) {
		this.videoReloadTotal = 0;
		this.videoReloadComplete = 0;
		MODx.Ajax.request({
			url: Rvg.config.connectorUrl,
			params: {
				action: 'mgr/video/getids',
				id:this.config.record.id
			},
			listeners: {
				success: {
					fn: function (e) {
						Ext.Msg.show({
							title: _('please_wait')
							,msg: _('resvideogallery.mess.reload_thumb')
							,width: 340
							,progress:true
							,closable:false
						});
						this.videoReloadIds = e.results;
						this.videoReloadTotal = parseInt(e.total);
						Ext.MessageBox.updateProgress((this.videoReloadComplete/this.videoReloadTotal), this.videoReloadTotal + '/' + this.videoReloadComplete);
						this.reloadThumb();
					}, scope: this
				},
				failure: {
					fn: function (response) {
						MODx.msg.alert(_('error'), response.message);
					}, scope: this
				},
			}
		})
	}
});
Ext.reg('resvideogallery-page-toolbar', Rvg.panel.Toolbar);