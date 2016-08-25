

    function addressBookReplaceStringProto(proto, value, str) {
        return proto.replace(new RegExp(str, 'g'), value);
    }

    function addressBookDisplayList (list, widgetId) {
        var acronymProto = $('#' + widgetId + '-acronym-prototype').html();
        var pageProto = $('#' + widgetId + '-page-prototype').html();
        var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        for (var i = 0; i < letters.length; i++) {
            if(list[letters[i]].length > 0 ){
                var newAcronym = addressBookReplaceStringProto(acronymProto, letters[i] , "__acronym_initial__");
                $('#page-list-' + widgetId).append(newAcronym);
                for (var j = 0; j < list[letters[i]].length; j++) {
                    var newPage = addressBookReplaceStringProto(pageProto, list[[letters[i]]][j]['name'], "__page_name__");
                    newPage = addressBookReplaceStringProto(newPage, list[[letters[i]]][j]['url'], "__page_url__");
                    $('#' + widgetId + letters[i]).append(newPage);
                };
            }

        };
    }

    function addressBookOrderList(list, sortProperty) {
        listSort = list.sort(function(a, b) {
            return a[sortProperty].localeCompare(b[sortProperty]);
        });

        var orderedList = {'A': [], 'B': [], 'C': [], 'D': [], 'E': [], 'F': [], 'G': [], 'H': [], 'I': [], 'J': [], 'K': [], 'L': [], 'M': [], 'N': [], 'O': [], 'P': [], 'Q': [], 'R': [], 'S': [], 'T': [], 'U': [], 'V': [], 'W': [], 'X': [], 'Y': [], 'Z': []};

        for (var i = 0; i < listSort.length; i++) {
            var initial = listSort[i]['name'].charAt(0).toUpperCase();
            orderedList[initial].push(listSort[i]);
        }
        return orderedList;
    }
