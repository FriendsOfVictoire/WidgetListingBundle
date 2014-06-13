
function addItemStaticForm()
{
    var collectionHolder = $vic('#picker-static ul.items');
    
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $vic('<li></li>').append(newForm);
    collectionHolder.append($newFormLi);
}

function addItemEntityForm(formId)
{
    var collectionHolder = $vic('#picker-' + formId + '-entity ul');
    
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $vic('<li></li>').append(newForm);
    collectionHolder.append($newFormLi);
}

/*
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
/*
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
}*/
