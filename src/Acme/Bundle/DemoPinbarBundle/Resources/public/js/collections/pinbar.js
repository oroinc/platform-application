var pinbar = pinbar || {};

$(function ($) {
    'use strict';

    var ItemsList = Backbone.Collection.extend({
        model: pinbar.Item
    })

    pinbar.Items = new ItemsList();
});