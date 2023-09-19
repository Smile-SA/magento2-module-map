define(['ko', 'uiClass'], function(ko, Class) {
    "use strict";

    return Class.extend({

        initialize: function () {
            this._super()
                .initObservable();

            return this;
        },

        initObservable: function () {
            this.items = ko.observableArray(this.items);
            return this;
        },

        getList: function() {
            return this.items();
        }
    });
});
