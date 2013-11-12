function initListWidgetForm(id){

    // Disable entity select when we submit the form
    $('form#' + id ) .on('submit', function(e){
        $('select.add_' + id + '_link').prop('disabled', true);
    });

    // Add an item to the list
    $('select.add_' + id + '_link').on('change', function(e) {
        e.preventDefault();
        if ($('select.add_' + id + '_link option:selected').val() != '') {
            url = Routing.generate('victoire_list_widget_show');
            data = $('select.add_' + id + '_link').parents('form').serialize();
            ajaxUpdateListItems(url, data, function (response) {
                $('ul#' + id + '-list').html(response);
                sortWidgetListItems(id + '-list');
                $('select.add_' + id + '_link option:selected').remove();
            });
        }
    });
    // refresh list items when we change selected fields
    $('div#' + id + ' div#appventus_victoirecmsbundle_widgetlisttype_fields_control_group select').each(function(e){
        $(this).on('change', function(e){
            e.preventDefault();
            $('select.add_' + id + '_link').prop('disabled', true);

            url = Routing.generate('victoire_list_widget_show');
            data = $('select.add_' + id + '_link').parents('form').serialize();
            ajaxUpdateListItems(url, data, function (response) {
            $('ul#' + id + '-list').html(response);
            sortWidgetListItems(id + '-list');
            });

            $('select.add_' + id + '_link').prop('disabled', false);

        });
    });
}

function initStaticListWidgetForm(id){

    var collectionHolder = $('ul.items');

    // ajoute un lien « add a item »
    var $addItemLink = $('<a href="#" class="add_item_link">Ajouter un item</a>');
    var $newLinkLi = $('<li></li>').append($addItemLink);

    // ajoute l'ancre « ajouter un item » et li à la balise ul
    collectionHolder.append($newLinkLi);

    $addItemLink.on('click', function(e) {
      // empêche le lien de créer un « # » dans l'URL
      e.preventDefault();

      // ajoute un nouveau formulaire item
      addItemForm(collectionHolder, $newLinkLi);
    });
}

function addItemForm(collectionHolder, $newLinkLi) {
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

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
