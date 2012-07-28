
$.fn.room13GeoLocationWidget = function(){



    var $this     = $(this),
        sourceUrl = $this.data('source'),
        input     = $this.find('input[type=text]'),
        output    = $this.find('input[type=hidden]'),
        loader    = $this.find('.Loader');


    function inputFocused()
    {
        this.select();
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


$('.Room13GeoLocationWidget').each(function(){
    $(this).room13GeoLocationWidget();
});
