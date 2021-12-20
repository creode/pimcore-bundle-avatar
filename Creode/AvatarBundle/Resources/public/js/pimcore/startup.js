pimcore.registerNS("pimcore.plugin.AvatarBundle");

pimcore.plugin.AvatarBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.AvatarBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("AvatarBundle ready!");
    }
});

var AvatarBundlePlugin = new pimcore.plugin.AvatarBundle();
