var pinbar = pinbar || {};

$(function ($) {
    'use strict';

    pinbar.MainView = Backbone.View.extend({

        el: $('.pin-bar .list-bar'),

        requireCleanup: true,

        emptyTemplate: _.template('<span>There are no pinned items</span>'),

        initialize: function () {
            this.listenTo(pinbar.Items, 'add', this.addItem);
            this.listenTo(pinbar.Items, 'reset', this.addAll);
            this.listenTo(pinbar.Items, 'all', this.render);

            this.render();
        },

        addItem: function(item) {
            if (this.requireCleanup) {
                this.$el.empty();
                this.requireCleanup = false;
            }
            var view = new pinbar.ItemView({model: item});
            this.$el.prepend(view.render().el);
        },

        addAll: function() {
            pinbar.Items.each(this.addItem, this);
        },

        render: function() {
            if (pinbar.Items.length == 0) {
                this.requireCleanup = true;
                this.$el.html(this.emptyTemplate());
            }
        }
    })
});