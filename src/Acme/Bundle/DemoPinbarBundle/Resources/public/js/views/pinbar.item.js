var pinbar = pinbar || {};

$(function ($) {
    'use strict';

    pinbar.ItemView = Backbone.View.extend({

        tagName:  'li',

        template: _.template('<a class="btn-close" href="#">close</a><a href="/"><%= title %></a>'),

        events: {
            'click .btn-close': 'unpin'
        },

        initialize: function() {
            this.listenTo(this.model, 'destroy', this.remove)
        },

        unpin: function()
        {
            this.model.destroy();
        },

        render: function () {
            this.$el.html(
                this.template(this.model.toJSON())
            );
            return this;
        }
    });
});