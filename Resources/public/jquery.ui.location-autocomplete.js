
$('.Room13GeoLocationAutocomplete').each(function()
{

    var $this    = $(this),
        source   = $this.data('source'),
        input    = $this.find('.Input'),
        output   = $this.find('.Output'),
        loader   = $this.find('.Loader');

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