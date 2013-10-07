
function ajaxUpdateListItems(url, data, successCallback){
    $.ajax({
          url: url,
          data: data,
          context: document.body,
          type: "POST",
          success: function(response) {
            successCallback(response);
          },
          error: function(response) {
            alert("Il semble s'êre produit une erreur");
          }
    });

}

$(document).on('click', '.remove-widget-list-item', function(e) {
    e.preventDefault();
    id     = $(this).parent().data('id');
    entityName   = $(this).parent().data('entity-name');
    name   = $(this).parent().data('entity');
    option = '<option value="' + id + '">' + entityName + '</option>';
    $('select.add_' + name + '_link').append(option);

    $(this).parent('li').remove();
});

function sortWidgetListItems(list_id){
    count = $('ul#' + list_id).children('li').size();

    $('ul#' + list_id).each(function(){

        pos = 0;
        $(this).children().each(function(){
            $(this).children('input.position-field').val(++pos);
        });

        $(this).sortable({
            revert: true,
            items: "li",
            update: function( event, ui ) {
                pos = 0;
                $(this).children().each(function(){
                    $(this).children('input.position-field').val(++pos);
                });
            },
            create: function( event, ui ) {
                pos = 0;
                $(this).children().each(function(){
                    $(this).children('input.position-field').val(++pos);
                });
            }

        });
    });
}
