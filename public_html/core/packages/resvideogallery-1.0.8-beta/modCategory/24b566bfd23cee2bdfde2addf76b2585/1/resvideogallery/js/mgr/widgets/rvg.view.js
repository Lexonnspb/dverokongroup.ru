Rvg.panel.Videos = function(config) {
	config = config || {};

	this.view = MODx.load({
		xtype: 'resvideogallery-videos-view',
		id: 'resvideogallery-videos-view',
		cls: 'resvideogallery-videos',
		containerScroll: true,
		pageSize: parseInt(config.pageSize) || MODx.config.default_per_page,
		resource_id: config.resource_id,
		emptyText:_('resvideogallery.msg.empty')
	});

	Ext.applyIf(config,{
		id: 'resvideogallery-videos',
		cls: 'browser-view',
		border: false,
		items: [this.view],
		tbar: this.getTopBar(config),
		bbar: this.getBottomBar(config),
	});
	Rvg.panel.Videos.superclass.constructor.call(this,config);

	var dv = this.view;
	dv.on('render', function() {
		dv.dragZone = new Rvg.DragZone(dv);
		dv.dropZone = new Rvg.DropZone(dv);
	});

};
Ext.extend(Rvg.panel.Videos,MODx.Panel, {

	Tags: function(tf) {
		var s = this.view.getStore();
		s.baseParams.tags = tf.getValue();
		this.getBottomToolbar().changePage(1);
	},

	clearTags: function() {
		var s = this.view.getStore();
		s.baseParams.tags = '';
		this.getBottomToolbar().changePage(1);
	},

	Search: function(tf) {
		this.view.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
	},

	clearSearch: function() {
		this.view.getStore().baseParams.query = '';
		this.getBottomToolbar().changePage(1);
	},

	getTopBar: function(config) {
		return new Ext.Toolbar({
			items: [{
				xtype: 'resvideogallery-combo-tags',
				id: 'resvideogallery-combo-tags-filter',
				width: 300,
				emptyText: _('resvideogallery.video.tags'),
				allowAddNewData: false,
				addNewDataOnBlur: false,
				supressClearValueRemoveEvents: true,
				pageSize: 10,
				listeners: {
					clear: {fn:function(tf) {this.clearTags();}, scope:this},
					additem: {fn:function(tf) {this.Tags(tf);}, scope:this},
					removeitem: {fn:function(tf) {this.Tags(tf);}, scope:this},
				},
			}, '->', {
				xtype: 'resvideogallery-field-search',
				width: 300,
				listeners: {
					search: {fn: function(field) {
						this.Search(field);
					}, scope: this},
					clear: {fn: function(field) {
						field.setValue('');
						this.clearSearch();
					}, scope: this},
				},
			}]
		})
	},

	getBottomBar: function(config) {
		return new Ext.PagingToolbar({
			pageSize: parseInt(config.pageSize) || MODx.config.default_per_page,
			store: this.view.store,
			displayInfo: true,
			autoLoad: true,
			items: ['-',
				_('per_page') + ':',
				{
					xtype: 'textfield',
					value: parseInt(config.pageSize) || MODx.config.default_per_page,
					width: 50,
					listeners: {
						change: {fn:function(tf,nv,ov) {
							if (Ext.isEmpty(nv)) {return;}
							nv = parseInt(nv);
							this.getBottomToolbar().pageSize = nv;
							this.view.getStore().load({params:{start:0, limit: nv}});
						}, scope:this},
						render: {fn: function(cmp) {
							new Ext.KeyMap(cmp.getEl(), {
								key: Ext.EventObject.ENTER,
								fn: function() {this.fireEvent('change',this.getValue());this.blur();return true;},
								scope: cmp
							});
						}, scope:this}
					}
				}
			]
		});
	},

});
Ext.reg('resvideogallery-videos-panel',Rvg.panel.Videos);


Rvg.view.Videos = function(config) {
	config = config || {};

	this._initTemplates();

	Ext.applyIf(config,{
		url: Rvg.config.connectorUrl,
		fields: [
			'id','resource_id','title','description','video_key','thumb','duration','url','provider','createdon','createdby',
			'active','rank','active','pub_date','unpub_date','properties','tags','actions','username'
		],
		id: 'resvideogallery-videos-view',
		baseParams: {
			action: 'mgr/video/getlist',
			resource_id: config.resource_id,
			limit: config.pageSize || MODx.config.default_per_page
		},
		enableDD: true,
		multiSelect: true,
		tpl: this.templates.thumb,
		itemSelector: 'div.modx-browser-thumb-wrap',
		listeners: {},
		prepareData: this.formatData.createDelegate(this)
	});
	Rvg.view.Videos.superclass.constructor.call(this,config);

	this.addEvents('sort','select');
	this.on('sort',this.onSort,this);
	this.on('dblclick',this.onDblClick,this);

	var widget = this;
	this.getStore().on('beforeload', function() {
		widget.getEl().mask(_('loading'),'x-mask-loading');
	});
	this.getStore().on('load', function() {
		widget.getEl().unmask();
	});
};
Ext.extend(Rvg.view.Videos,MODx.DataView,{

	templates: {},
	windows: {},

	onSort: function(o) {
		var el = this.getEl();
		el.mask(_('loading'),'x-mask-loading');
		MODx.Ajax.request({
			url: Rvg.config.connectorUrl,
			params: {
				action: 'mgr/video/sort',
				resource_id: this.config.resource_id,
				source: o.source.id,
				target: o.target.id
			},
			listeners: {
				success: {fn: function() {
					el.unmask();
					this.store.reload();
				}, scope: this}
			}
		});
	},

	onDblClick: function(e) {
		var node = this.getSelectedNodes()[0];
		if (!node) {return;}

		this.cm.activeNode = node;
		this.updateVideo(node,e);
	},
	updateVideo: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}

		var w = MODx.load({
			xtype: 'resvideogallery-gallery-video',
			record: data,
			listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
		w.setValues(data,true);
		w.show(e.target);
	},

	editTags: function(btn,e) {
		var ids = this._getSelectedIds();
		var arr1 = [];
		for (var id in ids) {
			if (!ids.hasOwnProperty(id)) {
				continue;
			}
			var data = this.lookup['resvideogallery-resource-video-' + ids[id]];
			if (data) {
				var arr2 = [];
				for (var tag in data.tags) {
					if (data.tags.hasOwnProperty(tag)) {
						if (id == 0) {
							arr1.push(data.tags[tag]['tag']);
						}
						else {
							arr2.push(data.tags[tag]['tag']);
						}
					}
				}
				if (id > 0) {
					arr1 = this._array_intersect(arr1, arr2);
				}
			}
		}

		var tags = [];
		if (arr1.length > 0) {
			for (var i in arr1) {
				if (arr1.hasOwnProperty(i)) {
					tags.push({tag: arr1[i]});
				}
			}
		}

		var w = MODx.load({
			xtype: 'resvideogallery-gallery-tags',
			ids: Ext.util.JSON.encode(ids),
			tags: tags,
			listeners: {
				success: {fn:function() {this.store.reload()},scope: this}
			}
		});
		w.show(e.target);
	},
	showVideo: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}
		window.open(data.url);
	},
	userInfo: function(btn,e) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}
		window.open('?a=security/user/update&id=' + data.createdby);

	},
	reloadThumb: function(method) {
		var node = this.cm.activeNode;
		var data = this.lookup[node.id];
		if (!data) {return;}
		this.getEl().mask(_('loading'),'x-mask-loading');

		MODx.Ajax.request({
			url: Rvg.config.connectorUrl,
			params: {
				action: 'mgr/video/reloadthumb',
				id: data.id,
			},
			listeners: {
				success: {
					fn: function () {
						this.store.reload();
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
	videoAction: function(method) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		this.getEl().mask(_('loading'),'x-mask-loading');
		MODx.Ajax.request({
			url: Rvg.config.connectorUrl,
			params: {
				action: 'mgr/video/multiple',
				method: method,
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.store.reload();
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
	removeVideos: function() {
		var ids = this._getSelectedIds();
		var title = ids.length > 1
			? 'resvideogallery.video.delete_multiple'
			: 'resvideogallery.video.delete';
		var message = ids.length > 1
			? 'resvideogallery.video.delete_multiple_confirm'
			: 'resvideogallery.video.delete_confirm';
		Ext.MessageBox.confirm(
			_(title),
			_(message),
			function(val) {
				if (val == 'yes') {
					this.videoAction('remove');
				}
			},
			this
		);
	},
	activateVideos: function() {
		this.videoAction('activate');
	},
	inActivateVideos: function() {
		this.videoAction('inactivate');
	},
	run: function(p) {
		p = p || {};
		var v = {};
		Ext.apply(v,this.store.baseParams);
		Ext.apply(v,p);
		this.changePage(1);
		this.store.baseParams = v;
		this.store.load();
	},
	formatData: function(data) {
		data.shortName = Ext.util.Format.ellipsis(data.title, 60);
		data.createdon = Rvg.util.formatDate(data.createdon);
		data.formatDuration = Rvg.util.formatDuration(data.duration);
		this.lookup['resvideogallery-resource-video-'+data.id] = data;
		return data;
	},
	_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate(
			'<tpl for=".">\
				<div class="modx-browser-thumb-wrap modx-pb-thumb-wrap resvideogallery-thumb-wrap {class}" id="resvideogallery-resource-video-{id}">\
					<div class="modx-browser-thumb modx-pb-thumb resvideogallery-thumb">\
						<img src="{thumb}" title="{title}" />\
						<div class="resvideogallery-thumb-time">{formatDuration}</div>\
					</div>\
					<small><b>'+_('resvideogallery.rank')+':</b> {rank}</small>\
					<div><small><b>'+_('resvideogallery.username')+':</b> {username}</small></div>\
					<small><span class="resvideogallery-thumb-title" title="{title}">{shortName}</span></small>\
				</div>\
			</tpl>'
		);
		this.templates.thumb.compile();
	},
	_showContextMenu: function(v, i, n, e) {
		e.preventDefault();
		var data = this.lookup[n.id];
		var m = this.cm;
		m.removeAll();

		var menu = Rvg.util.getMenu(data.actions, this, this._getSelectedIds());
		for (var item in menu) {
			if (!menu.hasOwnProperty(item)) {
				continue;
			}
			m.add(menu[item]);
		}

		m.show(n, 'tl-c?');
		m.activeNode = n;
	},
	_getSelectedIds: function() {
		var ids = [];
		var selected = this.getSelectedRecords();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},
	_array_intersect: function(arr1, arr2) {
		var results = [];

		for (var i = 0; i < arr1.length; i++) {
			if (arr2.indexOf(arr1[i]) !== -1) {
				results.push(arr1[i]);
			}
		}

		return results;
	}

});
Ext.reg('resvideogallery-videos-view', Rvg.view.Videos);