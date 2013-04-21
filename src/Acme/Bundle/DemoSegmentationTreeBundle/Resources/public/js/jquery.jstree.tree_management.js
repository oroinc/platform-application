/* File: jstree.tree_management.js
 * Allow to select on which tree to work and manage creation and deletion of trees
 */

/* Group: jstree tree_management plugin */
(function ($) {
    $.jstree.plugin("tree_management", {
        __init : function () {
            this.get_container()
                // Create the tree toolbar and load trees in tree selector
                .bind("init.jstree", $.proxy(function () {
                    var settings = this._get_settings().tree_management;
                    this.data.tree_management.ajax_trees = settings.ajax_trees;

                    var tree_toolbar = $('<div>', {
                        id: 'tree_toolbar',
                        class: 'jstree-tree-toolbar',
                    });
                    var tree_selector = $('<select>', {
                        id: 'tree_selector',
                        class: 'jstree-tree-selector',
                    });
                    var this_jstree = this;
                    tree_selector.bind('change', function() {
                        this_jstree.switch_tree();
                    });
                    tree_toolbar.html(tree_selector);
                    this.get_container_ul().before(tree_toolbar);

                    this.load_trees();
                }, this))
                // Rewrite the root node to link it to the selected tree
                .bind("loaded.jstree", $.proxy(function () {
                    this.switch_tree();

                }, this))
        },
        defaults : {
            ajax_trees : false,
            tree_management_buttons : false,
        },
        _fn : {
            switch_tree : function () {
                root_node = this.get_container_ul().find('li')[0];
                var selected_tree = this.get_tree_selector().find(':selected');
                root_node.id = $(selected_tree).attr('id');
                this.set_text(root_node,selected_tree.text());
                $(root_node).children('ul').remove();
                this.close_node(root_node);
                this.clean_node(root_node);
                $(root_node).removeClass('jstree-leaf');
                $(root_node).addClass('jstree-close');
                $(root_node).addClass('jstree-closed');
                this.open_node(root_node);
            },
            get_tree_selector : function () {
                return $("#tree_selector");
            },
            load_trees: function () {
                var trees_url = this.data.tree_management.ajax_trees.url;

                $.ajax({
                    url: trees_url,
                    async: false,
                    dataType: 'json'
                }).done( function(options_data) {
                    $.each(options_data, function (index, option_data) {
                        var option = $('<option>', {
                            id: option_data.id,
                            text: option_data.title
                        });
                        $('#tree_selector').append(option);
                    });
                });
            },
            refresh_trees: function() {
                $('#tree_selector').empty();
                this.load_trees();
            }
        }
    });
    // include the tree_management plugin by default on available plugins list
    $.jstree.defaults.plugins.push("tree_management");
})(jQuery);
