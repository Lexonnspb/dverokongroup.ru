Rvg.window.Video = function(config) {
	config = config || {};
	this.ident = config.ident || Ext.id();
	Ext.applyIf(config, {
		title: config.record['id'] ? (config.record['name'] ||  _('resvideogallery.video.update')) :  _('resvideogallery.video.new'),
		id: this.ident,
		cls: 'resvideogallery ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		width: 700,
		autoHeight: true,
		url: Rvg.config.connectorUrl,
		action: config.record.id ? 'mgr/video/update' : 'mgr/video/add',
		layout: 'form',
		resizable: false,
		maximizable: false,
		minimizable: false,
		fields: [
			{xtype: 'hidden',name: 'id',id: this.ident+'-id'},
			{xtype: 'hidden',name: 'resource_id',id: this.ident+'-resource-id'},
			{xtype: 'hidden',name: 'video_key',id: this.ident+'-video-key'},
			{xtype: 'hidden',name: 'duration',id: this.ident+'-duration'},
			{xtype: 'hidden',name: 'provider',id: this.ident+'-provider'},
			{xtype: 'hidden',name: 'primary_thumb',id: this.ident+'-primary-thumb'},
			{xtype: 'hidden',name: 'choice_thumb',id: this.ident+'-choice-thumb'},
			{
				xtype: 'textfield',
				id: this.ident + '-url',
				fieldLabel: _('resvideogallery.video.url'),
				name: 'url',
				emptyText: 'http://',
				vtype: 'url',
				allowBlank: config.record.id ? true : false,
				disabled: config.record.id ? true : false,
				enableKeyEvents:true,
				listeners: {
					keydown: {fn:function(f,e) {
							if((e.getKey() == 86 && (e.ctrlKey||e.metaKey)) || e.getKey() == e.ENTER) {
								var self = this;
								setTimeout(function () {
									self.scrape();
								},100)

							}
						}, scope:this
					}
				},
				anchor: '100%'
			},{
				xtype: 'panel'
				,id: this.ident + '-panel'
				,layout: 'form'
				,hidden: true
				,items: [{
				layout: 'column',
				border: false,
				anchor: '100%',
				items: [{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border:false,
					items: [{
						xtype: 'displayfield',
						id: this.ident + '-thumb',
						hideLabel: true,
						html: '',
						listeners: {
							render: {fn: function(el) {
								el.getEl().on('click',
									this.setChoiceThumb,
									this,
									{delegate: '.resvideogallery-window-thumb-btn'}
								);
							}, scope:this}
						}
					}]
				},{
					columnWidth: .5,
					layout: 'form',
					defaults: { msgTarget: 'under' },
					border:false,
					items: [
						{xtype: 'textfield',fieldLabel: _('resvideogallery.video.title'),name: 'title',id: this.ident+'-name',anchor: '100%'},
						{xtype: 'textarea',fieldLabel: _('resvideogallery.video.description'),name: 'description',id: this.ident+'-description',anchor: '100%', height: 50},
						{xtype: config.record.id ? 'resvideogallery-combo-tags' : 'textfield',fieldLabel: _('resvideogallery.video.tags'),name: 'tags',id: this.ident+'-tags',anchor: '100%', value: config.record['tags']},
						{xtype: 'xcheckbox',hideLabel: true, boxLabel: _('resvideogallery.video.activate'),name: 'active',id: this.ident+'-active',anchor: '100%', checked: config.record.active , ctCls: 'resvideogallery-cba'}
					]
				}]
			}]
				,border: false
			}
		],
		buttons: [{
			text: config.cancelBtnText || _('cancel')
			,scope: this
			,handler: function() {
				config.closeAction !== 'close' ? this.hide() : this.close(); }
		},{
			text: config.saveBtnText || _('save')
			,id: this.ident + '-btn-save'
			,cls: 'primary-button'
			,scope: this
			,disabled: config.record.id ? false : true
			,handler: this.submit
		}],
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
	});
	Rvg.window.Video.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(Rvg.window.Video, MODx.Window,{
	browser: null
	,setThumb:function(r){
		if(r) {
			var img = '';
			var details = '';
			var thumb = Ext.getCmp(this.ident + '-thumb');
			var fields = {
				'resvideogallery.rank': r.rank,
				'resvideogallery.duration': Rvg.util.formatDuration(r.duration),
				'resvideogallery.createdon': r.createdon,
			};

			if(r.createdby) {
				fields['resvideogallery.username'] = '<a href="?a=security/user/update&id='+r.createdby+'" target="_blank">' + r.username +'</a>';
			}

			if (r['thumb']) {
				img =  r.thumb;
			} else if (r.primary_thumb || r.browser_thumb) {
                img +=  r.browser_thumb ?  r.browser_thumb : r.primary_thumb;
				/*img = Rvg.config.connectorUrl + '?action=phpthumb/phpthumb&src=';
				img +=  r.browser_thumb ?  r.browser_thumb : r.primary_thumb;
				img += '&h=198&w=333&zc=1&far=C&q=90&f=jpg&ctx=mgr&HTTP_MODAUTH=' + MODx.siteId;*/
			}

			for (var i in fields) {
				if (!fields.hasOwnProperty(i)) {
					continue;
				}
				if (fields[i]) {
					details += '<tr><th>' + _(i) + ':</th><td>' + fields[i] + '</td></tr>';
				}
			}
			thumb.setValue('\
					<div class="resvideogallery-window-thumb">\
					    <div class="resvideogallery-window-thumb-btn"><i class="' + (MODx.modx23 ? 'icon icon-' : 'fa fa-') + 'pencil" ></i></div>\
						<a href="' + r['url'] + '" target="_blank">\
							<img src="' + img + '" class="resvideogallery-window-thumb-img" />\
						</a>\
					</div>\
						<table class="resvideogallery-window-details">' + details + '</table>');
		}
	}
	,setChoiceThumb:function(e){
		if(this.browser === null) {
			this.browser = MODx.load({
				xtype: 'modx-browser'
				, id: this.ident + '-browser'
				, multiple: false
				, source: this.config.source || MODx.config.default_media_source
				, allowedFileTypes: this.config.allowedFileTypes || 'jpg,jpeg,png'
				, listeners: {
					'select': {
						fn: function (data) {
							var r = this.fp.getForm().getValues();
							r.choice_thumb = data.pathname;
							r.browser_thumb = '/' + data.pathRelative;
							this.setThumb(r);
							Ext.getCmp(this.ident + '-choice-thumb').setValue(data.pathname);
						}, scope: this
					}
				}
			});
		}
		this.browser.show(e);
	}
	,setValues: function(r,expand) {
		if (r === null) { return false; }
		this.fp.getForm().setValues(r);
		this.setThumb(r);
		if(expand) Ext.getCmp(this.ident + '-panel').show();
	}
	,scrape:function(){
		var url =  Ext.getCmp(this.ident + '-url').getValue();
		if(url) {
			this.getEl().mask(_('loading'),'x-mask-loading');
			MODx.Ajax.request({
				url: Rvg.config.connectorUrl
				,params: {
					action: 'mgr/video/scrape'
					,url: url
				}
				,listeners: {
					success: {fn: function(r) {
						this.getEl().unmask();
						this.setValues(r.object,true);
						Ext.getCmp(this.ident + '-btn-save').setDisabled(false);
					}, scope:this}
					,failure: {fn: function() {this.getEl().unmask();}, scope:this}
				}
			});
		}
	}
});
Ext.reg('resvideogallery-gallery-video', Rvg.window.Video);


Rvg.window.Tags = function(config) {
	config = config || {};
	this.ident = config.ident || Ext.id();

	Ext.applyIf(config, {
		title: _('resvideogallery.video.edit_tags'),
		id: this.ident,
		cls: 'resvideogallery ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		width: 500,
		autoHeight: true,
		url: Rvg.config.connectorUrl,
		action: 'mgr/video/savetags',
		layout: 'form',
		resizable: false,
		maximizable: false,
		minimizable: false,
		fields: [{
			xtype: 'hidden',
			name: 'ids',
			id: this.ident + '-ids',
			value: config.ids,
		},{
			xtype: 'resvideogallery-combo-tags',
			fieldLabel: _('resvideogallery.video.tags'),
			name: 'tags',
			id: this.ident + '-tags',
			anchor: '100%',
			value: config.tags.length > 0
				? config.tags
				: '',
		},{
			xtype: 'displayfield',
			html: '<em>' + _('resvideogallery.video.edit_tags_intro') + '</em>',
			id: this.ident + '-desc',
			hideLabel: true,
		}],
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: this.submit,scope: this}]
	});
	Rvg.window.Video.superclass.constructor.call(this,config);
};
Ext.extend(Rvg.window.Tags, MODx.Window);
Ext.reg('resvideogallery-gallery-tags', Rvg.window.Tags);