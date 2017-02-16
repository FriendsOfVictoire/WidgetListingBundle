
//the index for the list begins at 1000 to avoid conflicts, we will never have 1000 items in the manual lists
var addItemStaticFormIndex = 1000;
var addItemEntityFormIndex = 1000;

function addItemStaticForm(liAttributes, quantum)
{
    var collectionHolder = $vic('#picker-static-' + quantum + ' ul.vic-items');

    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, addItemStaticFormIndex);

    //incremente index for the list
    addItemStaticFormIndex = addItemStaticFormIndex + 1;

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $vic('<li ' + liAttributes + '></li>').append(newForm);
    collectionHolder.append($newFormLi);
}

function addItemEntityForm(formId)
{
    var collectionHolder = $vic('#picker-' + formId + '-entity ul.vic-items');

    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, addItemEntityFormIndex);

    //incremente index for the list
    addItemEntityFormIndex = addItemEntityFormIndex + 1;

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un item"
    var $newFormLi = $vic('<li></li>').append(newForm);
    collectionHolder.append($newFormLi);
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
            alert("Il semble s'être produit une erreur");
        }
    });
}

function sortWidgetListItems(list_id)
{
    $vic('ul#' + list_id).each(function (){
        pos = 0;
        $vic(this).children().each(function ()
        {
            $vic(this).children('[data-type="position"]').val(++pos);
        });
        $vic(this).sortable({
            items: "li",
            placeholder: "vic-ui-state-highlight",
            forcePlaceholderSize: true,
            update: function (event, ui)
            {
                pos = 0;
                $vic(this).children().each(function (){
                    $vic('[data-type="position"]', this).val(++pos);
                });
            },
            create: function (event, ui)
            {
                pos = 0;
                $vic(this).children().each(function ()
                {
                    $vic('[data-type="position"]', this).val(++pos);
                });
            }
        });
    });
}
