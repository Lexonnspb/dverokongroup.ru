var Rvg = function(config) {
    config = config || {};
    Rvg.superclass.constructor.call(this,config);
};
Ext.extend(Rvg,Ext.Component,{
    page:{},
    window:{},
    grid:{},
    tree:{},
    panel:{},
    combo:{},
    config: {},
    view: {},
    extra: {},
    util:{
        formatDuration:function(t){
            var d = Math.floor(t/86400),
                h = ('0'+Math.floor(t/3600) % 24).slice(-2),
                m = ('0'+Math.floor(t/60)%60).slice(-2),
                s = ('0' + t % 60).slice(-2);
            return d = (d>0?d+'d ':'')+(h>0?h+':':'')+(m>0?m+':':'')+(t>60?s:s+'s');
        }
        ,formatDate:function(t){
            if (t && t != '0000-00-00 00:00:00' && t != 0) {
                var date = /^[0-9]+$/.test(t)
                    ? new Date(t * 1000)
                    : new Date(t.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
                return date.strftime(Rvg.config.date_format);
            }
            else {
                return '&nbsp;';
            }
        }
        ,getMenu:function (actions, grid, selected) {
            var menu = [];
            var cls, icon, title, action = '';

            var has_delete = false;
            for (var i in actions) {
                if (!actions.hasOwnProperty(i)) {
                    continue;
                }

                var a = actions[i];
                if (!a['menu']) {
                    if (a == '-') {
                        menu.push('-');
                    }
                    continue;
                }
                else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
                    menu.push('-');
                    has_delete = true;
                }

                if (selected.length > 1) {
                    if (!a['multiple']) {
                        continue;
                    }
                    else if (typeof(a['multiple']) == 'string') {
                        a['title'] = a['multiple'];
                    }
                }

                cls = a['cls'] ? a['cls'] : '';
                icon = a['icon'] ? a['icon'] : '';
                title = a['title'] ? a['title'] : a['title'];
                action = a['action'] ? grid[a['action']] : '';

                menu.push({
                    handler: action,
                    text: String.format(
                        '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                        cls, icon, title
                    ),
                    scope: grid,
                });
            }

            return menu;
        }
    },

});
Ext.reg('Rvg',Rvg);

Rvg = new Rvg();