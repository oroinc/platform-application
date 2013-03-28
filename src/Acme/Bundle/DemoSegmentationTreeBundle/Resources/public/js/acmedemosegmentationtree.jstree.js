//FIXME: create a JQuery plugin to avoid cluttering the $.fn namespace

$(function () {
    
    $.fn.createJsTree = function(jsTreeId, selectedTreeId) {
        $(jsTreeId).jstree({
            // List of active plugins
            "plugins" : [
                "themes","json_data","ui","crrm","cookies","dnd","search","types","hotkeys","contextmenu"
            ],
            "themes" : {
                "dots" : true,
                "icons" : true,
                "themes" : "bap",
                "url" : assetsPath + "/css/style.css"
            },
            "json_data" : {
                "ajax" : {
                    "url" : "children",
                    "data" : function (n) {
                        // the result is fed to the AJAX request `data` option
                        return {
                            "id" : n.attr ? n.attr("id").replace("node_","") : $(selectedTreeId).attr('title')
                        };
                    }
                }
            },
            "search" : {
                "ajax" : {
                    "url" : "search",
                    "data" : function (str) {
                        return {
                            "tree_root_id": $(selectedTreeId).attr('title'), "search_str" : str
                        };
                    }
                }
            },
            "types" : {
                "max_depth" : -2,
                "max_children" : -2,
                "valid_children" : [ "folder" ],
                "types" : {
                    "default" : {
                        "valid_children" : "folder",
                        "icon" : {
                            "image" : assetsPath + "images/folder.png"
                        }
                    },
                    "folder" : {
                        "icon" : {
                            "image" : assetsPath + "images/folder.png"
                        }
                    }
                }
            }
        })
        .bind("create.jstree", function (e, data) {
            var parentId = null;

            if (data.rslt.parent == -1) {
                parentId = $(selectedTreeId).attr('title');
            } else {
                parentId = data.rslt.parent.attr("id").replace("node_","");
            }
            alert(parentId);
            
            $.post(
                "create-node",
                {
                    "id" : parentId,
                    "position" : data.rslt.position,
                    "title" : data.rslt.name,
                    "type" : data.rslt.obj.attr("rel")
                },
                function (r) {
                    if(r.status) {
                        $(data.rslt.obj).attr("id", "node_" + r.id);
                    }
                    else {
                        $.jstree.rollback(data.rlbk);
                    }
                }
            );
        })
        .bind("remove.jstree", function (e, data) {
            data.rslt.obj.each(function () {
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: "remove-node",
                    data : {
                        "id" : this.id.replace("node_","")
                    },
                    success : function (r) {
                        if(!r.status) {
                            data.inst.refresh();
                        }
                    }
                });
            });
        })
        .bind("rename.jstree", function (e, data) {
            $.post(
                "rename-node",
                {
                    "id" : data.rslt.obj.attr("id").replace("node_",""),
                    "title" : data.rslt.new_name
                },
                function (r) {
                    if(!r.status) {
                        $.jstree.rollback(data.rlbk);
                    }
                }
            );
        })
        .bind("move_node.jstree", function (e, data) {
            data.rslt.o.each(function (i) {
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: "move-node",
                    data : {
                        "id" : $(this).attr("id").replace("node_",""),
                        "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""),
                        "position" : data.rslt.cp + i,
                        "title" : data.rslt.name,
                        "copy" : data.rslt.cy ? 1 : 0
                    },
                    success : function (r) {
                        if(!r.status) {
                            $.jstree.rollback(data.rlbk);
                        }
                        else {
                            $(data.rslt.oc).attr("id", "node_" + r.id);
                            if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
                                data.inst.refresh(data.inst._get_parent(data.rslt.oc));
                            }
                        }
                    }
                });
            });
        })
        .bind("select_node.jstree", function (e, data) {
            var a = $.jstree._focused().get_selected();
            var nodeId = a.attr('id');
            var segmentId = nodeId.replace('node_','');
            renderItemList(segmentId);
        });
    }

    $.fn.removeTree = function(rootSegmentId) {
        $.ajax({
            async : false,
            type: 'POST',
            url: "remove-tree",
            data : {
                "id" : rootSegmentId,
            },
            success: function(data) {
                renderTreeList();
            }
        })
    }

    $.fn.createTree = function (title, treeListId, jsTreeId, selectedTreeId) {
        $.ajax({
            async : false,
            type: 'POST',
            url: "create-tree",
            data : {
                "title" : title,
            },
            success: function(data) {
                $.fn.renderTreeList( treeListId, jsTreeId, selectedTreeId);
            }
        })
    }

    $.fn.renderTreeList = function( treeListId, jsTreeId, selectedTreeId ) {
        $(treeListId).empty();
        $.ajax({
            async : false,
            type: "GET",
            url: "trees",
            success: function(data) {
                $.each(data, function(i,item) {
                    var treeLink = $('<button>', {  
                        id: 'tree_'+item.id,  
                        class: 'btn btn-small btn-primary',
                        href: '',  
                        text: item.title
                    });
                    treeLink.bind('click', function () {
                        $(selectedTreeId).attr('title', item.id);
                        
                        $(jsTreeId).jstree('refresh');
                    });
                    var treeRemoveLink = $('<button>', {  
                        id: 'tree_remove_'.itemId,  
                        class: 'btn btn-small',
                        href: '',  
                        text: '[remove]'
                    });
                    treeRemoveLink.bind('click', function () {
                        if (confirm("Are you sure you want to delete the tree '" + item.title + "'")) {
                            removeTree(item.id);
                        }
                    });
                    $(treeListId).append(treeLink);
                    $(treeListId).append("&nbsp;");
                    $(treeListId).append(treeRemoveLink);
                    $(treeListId).append("<br>");
                })
            }
        })
    }
    


    function renderItemList(segmentId) {
        $.ajax({
            async : false,
            type: "GET",
            url: "list-items",
            data : {
                "segment_id" : segmentId
            },
            success: function(data) {
                var html_table = ['<table border="1">'];

                if (data.length > 0) {
                    html_table.push('<tr>');
                    for (var attribute in data[0]) {
                        html_table.push('<th>');
                        html_table.push(attribute);
                        html_table.push('</th>');
                    }
                    html_table.push('</tr>');
                    $.each(data, function(i,item) {
                        html_table.push('<tr>');
                        for (var attribute in item) {
                            html_table.push('<td>' + item[attribute] + '</td>');
                        }
                        html_table.push('</tr>');
                    });
                }
                html_table.push("</table>");
                var s = html_table.join('');
                $('#list').html(s);
            }
        });
    }

    $.fn.addItem = function(segmentId, itemId) {
        $.ajax({
            async : false,
            type: 'POST',
            url: "add-item",
            data : {
                "segment_id" : segmentId,
                "item_id" : itemId
            },
            success : function (r) {
                renderItemList(segmentId);
            }
        });
    }

    $.fn.removeItem = function(segmentId, itemId) {
        $.ajax({
            async : false,
            type: 'POST',
            url: "remove-item",
            data : {
                "segment_id" : segmentId,
                "item_id" : itemId
            },
            success : function (r) {
                renderItemList(segmentId);
            }
        });
    }

});
