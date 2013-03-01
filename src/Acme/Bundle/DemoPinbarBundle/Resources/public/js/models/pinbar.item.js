var pinbar = pinbar || {};

pinbar.Item = Backbone.Model.extend({
    defaults: {
        title: '',
        uri: null,
        position: null,
        type: null
    }
});
