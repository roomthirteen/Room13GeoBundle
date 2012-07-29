// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, undefined ) {

    // Create the defaults once
    var pluginName = 'room13GeoLocationWidget',
        document = window.document,
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {


        var target     = $(this.element),
            input     = target.find('input[type=text]'),
            output    = target.find('input[type=hidden]'),
            loader    = target.find('.Loader');


        function inputFocused()
        {
            target.select();
        }

        function dataLoaded(data,response)
        {
            // publish autocomplete data
            response(data);

            // hide the loader
            loader.hide();

            // clean value if no results where found
            if(data.length==0)
            {
                output.val('');
            }
        }

        function locationSelected(evt,ui)
        {
            // write back id to hidden field
            // the input field will be populated automaticly by the autocomplete widget
            output.val(ui.item.id);
        }

        function searchStarted()
        {
            // show the load indicator
            loader.show();
        }

        input.focus(inputFocused);

        input.autocomplete({
            minLength: 1,
            source: function (request, response) {

                sourceUrl = target.data('source');
                console.log(sourceUrl);

                $.ajax({
                    url: sourceUrl,
                    dataType: "json",
                    data: request,
                    success: function(data){
                        dataLoaded(data,response)
                    }
                });
            },

            select: locationSelected,
            search: searchStarted
        });

    }





    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
            }
        });
    }

}(jQuery, window));





$(function(){
    $('.Room13GeoLocationWidget').room13GeoLocationWidget();
});
