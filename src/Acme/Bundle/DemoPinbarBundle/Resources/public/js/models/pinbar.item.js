var pinbar = pinbar || {};

pinbar.Item = Backbone.Model.extend({
    defaults: {
        title: '',
        route: null,
        routeParams: [],
        uri: null,
        position: null,
        type: null
    }
});
