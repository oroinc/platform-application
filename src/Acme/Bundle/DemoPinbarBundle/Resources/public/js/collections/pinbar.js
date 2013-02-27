var pinbar = pinbar || {};

pinbar.ItemsList = Backbone.Collection.extend({
    model: pinbar.Item,

    initialize: function() {
        this.on('change:position', this.onPositionChange, this);
    },

    onPositionChange: function(item) {
        if (item.get('position') != item.get('oldPosition')) {
            this.trigger('positionChange', item);
        }
    }
})

pinbar.Items = new pinbar.ItemsList();