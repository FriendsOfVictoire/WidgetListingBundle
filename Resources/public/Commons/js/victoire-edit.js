
function initListWidgetForm(id)
{
    // Disable entity select when we submit the form
    $vic('form#' + id ) .on('submit', function (e){
        $vic('select.add_' + id + '_link').prop('disabled', true);
    });

    // Add an item to the list
    $vic('select.add_' + id + '_link').on('change', function (e) 
    {
        e.preventDefault();
        if ($vic('select.add_' + id + '_link option:selected').val() != '') {
            url = Routing.generate('victoire_widgetlisting_widgetlisting_show');
            data = $vic('select.add_' + id + '_link').parents('form').serialize();
            ajaxUpdateListItems(url, data, function (response) {
                $vic('ul#' + id + '-listing').html(response);
                sortWidgetListItems(id + '-listing');
                $vic('select.add_' + id + '_link option:selected').remove();
            });
        }
    });
    // refresh list items when we change selected fields
    $vic('div#' + id + ' div#appventus_victoirecorebundle_widgetlistingtype_fields_control_group select').each(function (e)
    {
        $vic(this).on('change', function (e)
        {
            e.preventDefault();
            $vic('select.add_' + id + '_link').prop('disabled', true);

            url = Routing.generate('victoire_widgetlisting_widgetlisting_show');
            data = $vic('select.add_' + id + '_link').parents('form').serialize();
            ajaxUpdateListItems(url, data, function (response) {
            $vic('ul#' + id + '-listing').html(response);
            sortWidgetListItems(id + '-listing');
            });

            $vic('select.add_' + id + '_link').prop('disabled', false);

        });
    });
}

function initStaticListWidgetForm(id)
{
    var collectionHolder = $vic('ul.items');

    // ajoute un lien « add a item »
    var $addItemLink = $vic('<a href="#" class="add_item_link">Ajouter un item</a>');
    var $newLinkLi = $vic('<li></li>').append($addItemLink);

    // ajoute l'ancre « ajouter un item » et li à la balise ul
    collectionHolder.append($newLinkLi);

    $addItemLink.on('click', function (e) 
    {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire item
        addItemForm(collectionHolder, $newLinkLi);
    });
}

function addItemForm(collectionHolder, $newLinkLi)
{
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $vic('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

function ajaxUpdateListItems(url, data, successCallback)
{
    $vic.ajax(
    {
        url: url,
        data: data,
        context: document.body,
        type: "POST",
        success: function (response) 
        {
            successCallback(response);
        },
        error: function (response) 
        {
            alert("Il semble s'êre produit une erreur");
        }
    });
}

$vic(document).on('click', '.remove-widget-listing-item', function (e) 
{
    e.preventDefault();
    id = $vic(this).parent().data('id');
    entityName = $vic(this).parent().data('entity-name');
    name = $vic(this).parent().data('entity');
    option = '<option value="' + id + '">' + entityName + '</option>';
    $vic('select.add_' + name + '_link').append(option);

    $vic(this).parent('li').remove();
});

function sortWidgetListItems(list_id) 
{
    count = $vic('ul#' + list_id).children('li').size();
    $vic('ul#' + list_id).each(function (){
        pos = 0;
        $vic(this).children().each(function ()
        {
            $vic(this).children('input.position-field').val(++pos);
        });
        $vic(this).sortable({
            revert: true,
            items: "li",
            update: function (event, ui) 
            {
                pos = 0;
                $vic(this).children().each(function (){
                    $vic(this).children('input.position-field').val(++pos);
                });
            },
            create: function (event, ui)
            {
                pos = 0;
                $vic(this).children().each(function ()
                {
                    $vic(this).children('input.position-field').val(++pos);
                });
            }
        });
    });
}
