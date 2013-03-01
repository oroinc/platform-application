var pinbar = pinbar || {};

pinbar.MainView = Backbone.View.extend({

    options: {
        maxPinbarItems: 3,
        el: '.pin-bar',
        listBar: '.list-bar',
        tabsTab: '#tabs-tab',
        pinIcon: '.pin-btn'
    },

    requireCleanup: true,
    massAdd: false,

    templates: {
        noItemsMessage: _.template($("#template-no-pins-message").html())
    },

    initialize: function() {
        this.$listBar = this.getBackboneElement(this.options.listBar);
        this.$tabs = this.getBackboneElement(this.options.tabsTab);
        this.$tabsBar = this.getBackboneElement(this.options.tabsContent);
        this.$pinIcon = Backbone.$(this.options.pinIcon);

        this.listenTo(pinbar.Items, 'add', function(item) {this.setItemPosition(item)}.bind(this));
        this.listenTo(pinbar.Items, 'remove', this.reorder);
        this.listenTo(pinbar.Items, 'positionChange', this.renderItem);
        this.listenTo(pinbar.Items, 'reset', this.addAll);
        this.listenTo(pinbar.Items, 'all', this.render);
        this.$pinIcon.click(this.togglePagePin.bind(this))
    },

    getBackboneElement: function(el) {
        return el instanceof Backbone.$ ? el : this.$(el);
    },

    togglePagePin: function(e) {
        var itemData = this.getCurrentPageItemData()
        var pinnedItem = pinbar.Items.where(itemData);
        if (pinnedItem.length) {
            _.each(pinnedItem, function(item) {item.destroy()});
            this.removePagePinStatus();
        } else {
            var el = Backbone.$(e.currentTarget);
            if (el.data('uri')) {
                itemData['uri'] = el.data('uri');
            }
            itemData['title'] = el.data('title') ? el.data('title') : document.title;
            var currentItem = new pinbar.Item(itemData);
            //currentItem.save();
            pinbar.Items.unshift(currentItem);
            this.addPagePinStatus();
        }
    },

    addPagePinStatus: function() {
        this.$pinIcon.addClass('btn-success');
        this.$pinIcon.find('i').addClass('icon-white');
    },

    removePagePinStatus: function() {
        this.$pinIcon.removeClass('btn-success');
        this.$pinIcon.find('i').removeClass('icon-white');
    },

    getCurrentPageItemData: function() {
        return {uri: window.location.pathname};
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
                this.$listBar.html(this.templates.noItemsMessage());
            }
            if (pinbar.Items.length > this.options.maxPinbarItems) {
                this.$tabs.show();
            } else {
                this.$tabs.hide();
            }
        }
    }
});