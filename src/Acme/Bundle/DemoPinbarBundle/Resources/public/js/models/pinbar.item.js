var pinbar = pinbar || {};

$(function ($) {
    'use strict';

    pinbar.Item = Backbone.Model.extend({
        defaults: {
            title: '',
            route: null,
            routeParams: [],
            uri: null
        }
    })
});
