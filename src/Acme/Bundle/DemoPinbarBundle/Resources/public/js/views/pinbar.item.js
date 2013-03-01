var pinbar = pinbar || {};

pinbar.ItemView = Backbone.View.extend({

    options: {
        type: 'list'
    },

    tagName:  'li',

    templates: {
        list: _.template($("#template-list-pin-item").html()),
        tab: _.template($("#template-tab-pin-item").html())
    },

    events: {
        'click .btn-close': 'unpin',
        'click .close': 'unpin'
    },

    initialize: function() {
        this.listenTo(this.model, 'destroy', this.remove)
        this.listenTo(this.model, 'change:type', this.remove);
    },

    unpin: function()
    {
        this.model.destroy();
    },

    render: function () {
        this.$el.html(
            this.templates[this.options.type](this.model.toJSON())
        );
        return this;
    }
});