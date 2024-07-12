layui.config({
    base: "/statics/layui/lay/modules/extendplus/"
}).extend({
    navbar: "navbar/navbar",
    tab: "navbar/tab",
    icheck: "icheck/icheck"
});
layui.use(["layer", "element", "util", "common"], function() {
    var a = layui.jquery,
        f = layui.layer,
        d = layui.device();
    var common=layui.common;
    layui.element();
    d.ie && 8 > d.ie && f.alert("最低支持ie8，您当前使用的是古老的 IE" + d.ie + "！");
    var d = a(".site-tree-mobile"),
        k = a(".site-mobile-shade");
    d.on("click", function() {
        a("body").addClass("site-mobile")
    });
    k.on("click", function() {
        a("body").removeClass("site-mobile")
    });
    a("#pay").on("click", function() {
        f.open({
            type: 1,
            title: !1,
            area: ["562px", "450px"],
            content: a(".shang_box")
        })
    });
    a("#git,#weibo,#weixin").on("click", function() {
        f.tips("暂时没有哦!", this)
    });
    var g = {
            doAdd: function() {
                var b = a(this).data("href");
                b ? window.location.href = b : common.layerAlertE("链接错误！", "提示")
            },
            doEdit: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id");
                b ? window.location.href = b + "?id=" + c : common.layerAlertE("链接错误！", "提示")
            },
            doDelete: function() {
                var b = a(this).data("href");
                if (b) {
                    if (1 > a(".layuitable tbody input:checked").size()) return common.layerAlertE("对不起，请选中您要操作的记录！", "提示");
                    for (var c = "", d = a(".layuitable tbody input:checked"), e = 0; e < d.length; e++) d[e].checked && "disabled" != a(d[e]).attr("disabled") && (c += a(d[e]).attr("ids") + ",");
                    common.layerDel("确认删除这些信息？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
                        ids: c
                    })
                } else common.layerAlertE("链接错误！", "提示")
            },
            doState: function() {
                var b = a(this).data("href");
                if (b) {
                    if (1 > a(".layuitable .article_list input:checked").size()) return common.layerAlertE("对不起，请选中您要操作的记录！", "提示");
                    for (var c = "", d = a(".layuitable .article_list input:checked"), e = 0; e < d.length; e++) d[e].checked && "disabled" != a(d[e]).attr("disabled") && (c += a(d[e]).attr("ids") + ",");
                    common.layerState("确认更新这些信息？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
                        ids: c
                    })
                } else common.layerAlertE("链接错误！", "提示")
            },
            doDelOne: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id"),
                    x = a(this).data("index");                    
                b ? layer.confirm('确认要删除吗？',function(){
              		//发异步删除数据
		            $.ajax({
		                url: b,
		                type: "post",
		                dataType: "json",
		                data: c,
		                success: function (data, startic) {
		                	console.log(data);
		                    if (data.code == 1) {
		                    	layer.msg('已删除!',{icon:1,time:1000});
		                    	setTimeout(function(){
//		                    		window.location.href ='/index.php/admin/'+x+'/index';
									location.reload()
//		                    		console.log(window.location.href)
		                    	},1000)		                        
		                    }
		                    else {
		                        obj.layerAlertE(data.msg, '提示');
		                    }
		                },
		                error: function () {
		                	console.log('错误')
		                }
		            });              
//            		layer.msg('已删除!',{icon:1,time:1000});
          		}) : common.layerAlertE("链接错误！", "提示")
            },
            doDelOneblock: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id"),
                    x = a(this).data("index");
                    console.log(b);
                b ? layer.confirm('确认要删除吗？',function(){
              		//发异步删除数据
		            $.ajax({
		                url: b,
		                type: "post",
		                dataType: "json",
		                data: c,
		                success: function (data, startic) {
		                	console.log(data);
		                    if (data.code == 1) {
		                    	layer.msg('已删除!',{icon:1,time:1000});
		                    	setTimeout(function(){
//		                    		window.location.href = '/index.php/admin/'+x+'/category.html';
		                    		location.reload()
		                    	},1000)		                        
		                    }
		                    else {
		                        obj.layerAlertE(data.msg, '提示');
		                    }
		                },
		                error: function () {
		                	console.log('错误')
		                }
		            });              
//            		layer.msg('已删除!',{icon:1,time:1000});
          		}) : common.layerAlertE("链接错误！", "提示")
            },            
//          common.layerDel("确认删除该条记录吗？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
//              ids: c
//          })  
			//管理日志
            doDelOnex: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id"),
                    x = a(this).data("index");
                    console.log(c);
                b ? 
		            common.layerDellog("确认删除该条记录吗？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
		                ids: c
		            })                  
                : common.layerAlertE("链接错误！", "提示")
            },
			//自定义块
            doDelOneblk: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id"),
                    x = a(this).data("index");
                    console.log(c);
                b ? 
		            common.layerDelblock("确认删除该条记录吗？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
		                ids: c
		            })                  
                : common.layerAlertE("链接错误！", "提示")
            },            
            doDeletelog: function() {
                var b = a(this).data("href");
                if (b) {
                    if (1 > a(".layuitable tbody input:checked").size()) return common.layerAlertE("对不起，请选中您要操作的记录！", "提示");
                    for (var c = "", d = a(".layuitable tbody input:checked"), e = 0; e < d.length; e++) d[e].checked && "disabled" != a(d[e]).attr("disabled") && (c += a(d[e]).attr("ids") + ",");
                    common.layerDellog("确认删除这些信息？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
                        ids: c
                    })
                } else common.layerAlertE("链接错误！", "提示")
            },            
            //内容管理
            doDelOnecon: function() {
                var b = a(this).data("href"),
                    c = a(this).data("id"),
                    x = a(this).data("index");
                    console.log(c);
                b ? 
		            common.layerDelcon("确认删除该条记录吗？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
		                ids: c
		            })                  
                : common.layerAlertE("链接错误！", "提示")
            },            
            doBatchDelete: function() {
                var b = a(this).data("href");
                b ? common.layerDel("确认删除这些信息？", "此操作不可逆，请再次确认是否要操作。", b, "post", "json", {
                    ids: ""
                }) : common.layerAlertE("链接错误！", "提示")
            },
            doDbBak: function() {
                var b = a(this).data("href");
                b ? common.ajax(b, "post", "json", "") : common.layerAlertE("链接错误！", "提示")
            }
        },
        h = {
            doRefresh: function() {
                var b = a(this).data("href");
                location.href = b ? b : location.href
            },
            doGoTop: function() {
                a(this).click(function() {
                    a("body,html").animate({
                        scrollTop: 0
                    }, 500);
                    return !1
                })
            },
            doGoBack: function() {
                history.go(-1)
            }
        };
    a(document).on("click", ".do-action", function(b) {
        var c = a(this).data("type");
        h[c] ? h[c].call(this) : "";
        g[c] ? g[c].call(this) : "";
        layui.stope(b)
    })
});