Ext.BLANK_IMAGE_URL = '/cms/js/extjs/resources/images/default/s.gif';

var defaultLang = 'ru';
Ext.MessageBox.buttonText.yes = 'Да';
Ext.MessageBox.buttonText.no = 'Нет';
//Ext.MessageBox.buttonText.ok = 'Да';
Ext.MessageBox.buttonText.cancel = 'Отмена';
Ext.WindowMgr.zseed = 50000;

//create namespace

Ext.namespace('crud');
// Desktop
MyDesktop = new Ext.app.App({
	init :function(){
		Ext.get('loader').getUpdater().on({
			'beforeupdate':function(el, obj, params){
				Ext.get('loading').show();
			},
			'update':function(el, action){
				Ext.get('loading').hide();
				if (action && action.responseText && action.responseText[0] == '{'){
					var response = eval('(' + action.responseText + ')');
					//Ext.MessageBox.alert('Ошибка', response.success);
					if (response.success == false){
						var err = '<b>Невозможно получить данные!</b><hr />Возможно на стороне сервера произошла ошибка либо Вы неавторизированы.';
						if (response.errorMessage) err = "<b>Ответ от сервера:</b><hr />" + response.errorMessage;
						else if (response.message) err = '<b>Ответ от сервера:</b><hr />' + response.message;
						Ext.MessageBox.alert('Ошибка', err);
					}
				}
			},
			'failure':function(el, action){
				Ext.get('loading').hide();
				Ext.MessageBox.alert('Ошибка', "<b>Невозможно получить данные!</b><hr />Возможно на стороне сервера произошла ошибка либо сервер недоступен.");
			}
		});
		Ext.QuickTips.init();
	},
	getModules :function(){
			return [
				new Ext.app.Module({
					id:'grid-win-client',
					title:'Пользователи',
					url:'/cms/grid/client',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-acl-modules',
					title:'Модули',
					url:'/cms/grid/acl-modules',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-acl-resources',
					title:'Ресурсы',
					url:'/cms/grid/acl-resources',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-acl-privileges',
					title:'Привелегии',
					url:'/cms/grid/acl-privileges',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-acl-roles',
					title:'Роли',
					url:'/cms/grid/acl-roles',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-acl-users',
					title:'Пользователи',
					url:'/cms/grid/acl-users',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-ip2country',
					title:'Ip и Страны',
					url:'/cms/grid/ip2country',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-site-modules',
					title:'Модули',
					url:'/cms/grid/site-modules',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-site-controllers',
					title:'Контроллеры',
					url:'/cms/grid/site-controllers',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-site-acts',
					title:'Экшины',
					url:'/cms/grid/site-acts',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-seo',
					title:'СЕО тексты',
					url:'/cms/grid/seo',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-shortcuts',
					title:'Ярлыки на рабочем столе',
					url:'/cms/grid/shortcuts',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'sample-win-ckfinder',
					title:'Файловый менеджер',
					url:'/cms/window/ckfinder',
					icon:'comp'
				}),
				new Ext.app.Module({
					id:'grid-win-languages',
					title:'Языки',
					url:'/cms/grid/languages',
					icon:'lang'
				}),
				new Ext.app.Module({
					id:'grid-win-location-regions',
					title:'Регионы',
					url:'/cms/grid/location-regions',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-location-countries',
					title:'Страны',
					url:'/cms/grid/location-countries',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-location-cities',
					title:'Города',
					url:'/cms/grid/location-cities',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-social-organizations',
					title:'Социальные организации',
					url:'/cms/grid/social-organizations',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-organizers',
					title:'Организаторы',
					url:'/cms/grid/organizers',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-expocenters',
					title:'Выставочные центры',
					url:'/cms/grid/expocenters',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-brands-categories',
					title:'Категории',
					url:'/cms/grid/brands-categories',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-brands-subcategories',
					title:'Подкатегории',
					url:'/cms/grid/brands-subcategories',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-brands',
					title:'Бренды',
					url:'/cms/grid/brands',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-periods',
					title:'Периоды проведения выставок',
					url:'/cms/grid/periods',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-events',
					title:'События',
					url:'/cms/grid/events',
					icon:'tabs'
				}),
				new Ext.app.Module({
					id:'grid-win-online-showrooms',
					title:'Залы',
					url:'/cms/grid/online-showrooms',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-online-types',
					title:'Типы стендов',
					url:'/cms/grid/online-types',
					icon:'bogus'
				}),
				new Ext.app.Module({
					id:'grid-win-online-places',
					title:'Размещения стендов',
					url:'/cms/grid/online-places',
					icon:'bogus'
				})
			];
	},
	getMenuConfig :function(){
		return [
				{
					text:'Сайт',
					iconCls:'folder',
					handler:function() {
						return false;
					},
					menu:{
						items:[
							{
								text:'Выставки',
								iconCls:'folder',
								handler:function() {
									return false;
								},
								menu:{
									items:[
										this.getModule('grid-win-expocenters').launcher,
										this.getModule('grid-win-social-organizations').launcher,
										this.getModule('grid-win-organizers').launcher,
										{
											text:'Управление брендами',
											iconCls:'folder',
											handler:function() {
												return false;
											},
											menu:{
												items:[
													this.getModule('grid-win-brands-categories').launcher,
													this.getModule('grid-win-brands-subcategories').launcher,
													this.getModule('grid-win-brands').launcher
												]
											}
										}//,
										//this.getModule('grid-win-events').launcher
									]
								}
							},
							{
								text:'Компании',
								iconCls:'folder',
								handler:function() {
									return false;
								},
								menu:{
									items: [
										{
											text:'Онлайн',
											iconCls:'folder',
											handler:function() {
												return false;
											},
											menu:{
												items: [
													this.getModule('grid-win-online-showrooms').launcher,
													this.getModule('grid-win-online-types').launcher,
													this.getModule('grid-win-online-places').launcher
												]
											}
										}
									]
								}
							}
						]
					}
				},
				{
					text:'Технические данные',
					iconCls:'setup',
					handler:function() {
						return false;
					},
					menu:{
						items:[
							{
								text:'География',
								iconCls:'geo',
								handler:function() {
									return false;
								},
								menu:{
									items:[
										this.getModule('grid-win-location-regions').launcher,
										this.getModule('grid-win-location-countries').launcher,
										this.getModule('grid-win-location-cities').launcher
									]
								}
							},
							this.getModule('grid-win-languages').launcher,
							this.getModule('grid-win-periods').launcher
						]
					}
				},
				{
					text:'СEO',
					iconCls:'folder',
					handler:function() {
						return false;
					},
					menu:{
						items:[
							this.getModule('grid-win-site-modules').launcher,
							this.getModule('grid-win-site-controllers').launcher,
							this.getModule('grid-win-site-acts').launcher,
							this.getModule('grid-win-seo').launcher
						]
					}
				},
				{
					text:'Права доступа',
					iconCls:'folder',
					handler:function() {
						return false;
					},
					menu:{
						items:[
							this.getModule('grid-win-acl-modules').launcher,
							this.getModule('grid-win-acl-resources').launcher,
							this.getModule('grid-win-acl-privileges').launcher,
							this.getModule('grid-win-acl-roles').launcher,
							this.getModule('grid-win-acl-users').launcher
						]
					}
				},
				//this.getModule('grid-win-client').launcher,
				//this.getModule('grid-win-ip2country').launcher,
				this.getModule('grid-win-shortcuts').launcher,
				this.getModule('sample-win-ckfinder').launcher
			]
	},
	getStartConfig :function(){
		return {
			title:'ExpoUa.COM - Menu',
			iconCls:'user',
			toolItems:[{
				text:'На главную',
				iconCls:'site',
				scope:this,
				checked: true,
				handler:function () {
					document.location = '/';
				}
			}, '-', {
				text:'Выход',
				iconCls:'logout',
				scope:this,
				handler:function () {
					document.location = '/cms/login/logout';
				}
			}]
		};
	},
	getContextMenu: function (){
		return {
			items: [{
				text: 'Добавить ярлык на рабочий стол',
				handler: function(b, e){
					var callItem = this.contextMenu.callItem;
					if (callItem){
						if (callItem.scope){
							if (callItem.scope.id){
								Ext.get('loader').load({
									waitMsg:'Загрузка...',
									url:'/cms/form/shortcuts/create',
									scripts:true,
									discardUrl: true,
									nocache: true,
									timeout: 5,
									params: {'id': 0, 'shortcut_module':callItem.scope.id, 'shortcut_text':callItem.text}
								});
							}
						}
					}
				},
				scope: this
			}]
		}
	}
});
