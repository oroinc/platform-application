var pinbar = pinbar || {};

pinbar.MainView = Backbone.View.extend({

    options: {
        maxPinbarItems: 3,
        el: '.pin-bar',
        listBar: '.list-bar',
        tabsTab: '#tabs-tab',
        tabsContent: '#tabs-content'
    },

    requireCleanup: true,
    massAdd: false,

    emptyTemplate: _.template($("#template-no-pins-message").html()),

    initialize: function() {
        this.$listBar = this.getBackboneElement(this.options.listBar);
        this.$tabs = this.getBackboneElement(this.options.tabsTab);
        this.$tabsBar = this.getBackboneElement(this.options.tabsContent);

        this.listenTo(pinbar.Items, 'add', function(item) {this.setItemPosition(item)}.bind(this));
        this.listenTo(pinbar.Items, 'remove', this.reorder);
        this.listenTo(pinbar.Items, 'positionChange', this.renderItem);
        this.listenTo(pinbar.Items, 'reset', this.addAll);
        this.listenTo(pinbar.Items, 'all', this.render);
    },

    getBackboneElement: function(el) {
        return el instanceof Backbone.$ ? el : this.$(el);
    },

    addAll: function() {
        this.massAdd = true;
        pinbar.Items.each(this.setItemPosition, this);
        this.massAdd = false;
    },

    setItemPosition: function(item, position) {
        if (_.isUndefined(position)) {
            this.reorder();
        } else {
            item.set({position: position});
        }
    },

    reorder: function() {
        pinbar.Items.each(function(item, position) {
            item.set({position: position});
        });
    },

    renderItem: function(item) {
        var type = 'list';
        var container = this.$listBar;
        var position = item.get('position');
        if (position >= this.options.maxPinbarItems) {
            type = 'tab';
            container = this.$tabsBar;
        }

        if (item.get('type') != type) {
            this.cleanup();
            item.set('type', type);
            var view = new pinbar.ItemView({
                type: type,
                model: item
            });
            var rowEl = view.render().el;
            if (this.massAdd || (position > 0 && type == 'list')) {
                container.append(rowEl);
            } else {
                container.prepend(rowEl);
            }
        }
    },

    cleanup: function()
    {
        if (this.requireCleanup) {
            this.$listBar.empty();
            this.$tabsBar.empty();
            this.requireCleanup = false;
        }
    },

    render: function() {
        if (!this.massAdd) {
            if (pinbar.Items.length == 0) {
                this.requireCleanup = true;
                this.$listBar.html(this.emptyTemplate());
            }
            if (pinbar.Items.length > this.options.maxPinbarItems) {
                this.$tabs.show();
            } else {
                this.$tabs.hide();
            }
        }
    }
});