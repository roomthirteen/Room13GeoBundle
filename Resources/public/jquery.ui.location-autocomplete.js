
$('.Room13GeoLocationAutocomplete').each(function()
{

    var $this    = $(this),
        source   = $this.data('source'),
        input    = $this.find('.Input'),
        output   = $this.find('.Output'),
        loader   = $this.find('.Loader');


    // normalize the input, because the value will be <id>|<name>
    // we need to split it and set the id to the hidden field
    var values = input.val().split('|');
    if(values.length>1)
    {
        input.val(values[1]);
        output.val(values[0]);
    }


    input.focus(function(){
        this.select();
    });

    input.autocomplete({
        source: source,
        minLength: 1,
        select: function(evt,ui){
            output.val(ui.item.id);
        },
        search: function(){
            loader.show();
        },
        open: function(){
            loader.hide();
        }
    });
});