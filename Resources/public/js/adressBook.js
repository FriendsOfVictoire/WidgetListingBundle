

    function adressBookReplaceStringProto(proto, value, str) {
        return proto.replace(new RegExp(str, 'g'), value);
    }

    function adressBookDisplayList (list, widgetId) {
        var acronymProto = $('#' + widgetId + '-acronym-prototype').html();
        var pageProto = $('#' + widgetId + '-page-prototype').html();
        var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        for (var i = 0; i < letters.length; i++) {
            if(list[letters[i]].length > 0 ){
                var newAcronym = adressBookReplaceStringProto(acronymProto, letters[i] , "__acronym_initial__");
                $('#page-list-' + widgetId).append(newAcronym);
                for (var j = 0; j < list[letters[i]].length; j++) {
                    var newPage = adressBookReplaceStringProto(pageProto, list[[letters[i]]][j]['name'], "__page_name__");
                    newPage = adressBookReplaceStringProto(newPage, list[[letters[i]]][j]['url'], "__page_url__");
                    $('#' + widgetId + letters[i]).append(newPage);
                };
            }

        };
    }

    function adressBookOrderList(list) {
        listSort = list.sort();
        var alphabeticEquivalents = {'A' : 'A', 'a' : 'A', 'B' : 'B', 'b' : 'B', 'C' : 'C', 'c' : 'C', 'D' : 'D', 'd' : 'D', 'E' : 'E', 'e' : 'E', 'F' : 'F', 'f' : 'F', 'G' : 'G', 'g' : 'G', 'H' : 'H', 'h' : 'H', 'I' : 'I', 'i' : 'I', 'J' : 'J', 'j' : 'J', 'K' : 'K', 'k' : 'K', 'L' : 'L', 'l' : 'L', 'M' : 'M', 'm' : 'M', 'N' : 'N', 'n' : 'N', 'O' : 'O', 'o' : 'O', 'P' : 'P', 'p' : 'P', 'Q' : 'Q', 'q' : 'Q', 'R' : 'R', 'r' : 'R', 'S' : 'S', 's' : 'S', 'T' : 'T', 't' : 'T', 'U' : 'U', 'u' : 'U', 'V' : 'V', 'v' : 'V', 'W' : 'W', 'w' : 'W', 'X' : 'X', 'x' : 'X', 'Y' : 'Y', 'y' : 'Y', 'Z' : 'Z', 'z' : 'Z', 'á' : 'A', 'Á' : 'A', 'à' : 'A', 'À' : 'A', 'ă' : 'A', 'Ă' : 'A', 'â' : 'A', 'Â' : 'A', 'å' : 'A', 'Å' : 'A', 'ã' : 'A', 'Ã' : 'A', 'ą' : 'A', 'Ą' : 'A', 'ā' : 'A', 'Ā' : 'A', 'ä' : 'A', 'Ä' : 'A', 'æ' : 'A', 'Æ' : 'A', 'ḃ' : 'B', 'Ḃ' : 'B', 'ć' : 'C', 'Ć' : 'C', 'ĉ' : 'C', 'Ĉ' : 'C', 'č' : 'C', 'Č' : 'C', 'ċ' : 'C', 'Ċ' : 'C', 'ç' : 'C', 'Ç' : 'C', 'ď' : 'D', 'Ď' : 'D', 'ḋ' : 'D', 'Ḋ' : 'D', 'đ' : 'D', 'Đ' : 'D', 'ð' : 'D', 'Ð' : 'D', 'é' : 'E', 'É' : 'E', 'è' : 'E', 'È' : 'E', 'ĕ' : 'E', 'Ĕ' : 'E', 'ê' : 'E', 'Ê' : 'E', 'ě' : 'E', 'Ě' : 'E', 'ë' : 'E', 'Ë' : 'E', 'ė' : 'E', 'Ė' : 'E', 'ę' : 'E', 'Ę' : 'E', 'ē' : 'E', 'Ē' : 'E', 'ḟ' : 'F', 'Ḟ' : 'F', 'ƒ' : 'F', 'Ƒ' : 'F', 'ğ' : 'G', 'Ğ' : 'G', 'ĝ' : 'G', 'Ĝ' : 'G', 'ġ' : 'G', 'Ġ' : 'G', 'ģ' : 'G', 'Ģ' : 'G', 'ĥ' : 'H', 'Ĥ' : 'H', 'ħ' : 'H', 'Ħ' : 'H', 'í' : 'I', 'Í' : 'I', 'ì' : 'I', 'Ì' : 'I', 'î' : 'I', 'Î' : 'I', 'ï' : 'I', 'Ï' : 'I', 'ĩ' : 'I', 'Ĩ' : 'I', 'į' : 'I', 'Į' : 'I', 'ī' : 'I', 'Ī' : 'I', 'ĵ' : 'J', 'Ĵ' : 'J', 'ķ' : 'K', 'Ķ' : 'K', 'ĺ' : 'L', 'Ĺ' : 'L', 'ľ' : 'L', 'Ľ' : 'L', 'ļ' : 'L', 'Ļ' : 'L', 'ł' : 'L', 'Ł' : 'L', 'ṁ' : 'M', 'Ṁ' : 'M', 'ń' : 'N', 'Ń' : 'N', 'ň' : 'N', 'Ň' : 'N', 'ñ' : 'N', 'Ñ' : 'N', 'ņ' : 'N', 'Ņ' : 'N', 'ó' : 'O', 'Ó' : 'O', 'ò' : 'O', 'Ò' : 'O', 'ô' : 'O', 'Ô' : 'O', 'ő' : 'O', 'Ő' : 'O', 'õ' : 'O', 'Õ' : 'O', 'ø' : 'O', 'Ø' : 'O', 'ō' : 'O', 'Ō' : 'O', 'ơ' : 'O', 'Ơ' : 'O', 'ö' : 'O', 'Ö' : 'O', 'ṗ' : 'P', 'Ṗ' : 'P', 'ŕ' : 'R', 'Ŕ' : 'R', 'ř' : 'R', 'Ř' : 'R', 'ŗ' : 'R', 'Ŗ' : 'R', 'ś' : 'S', 'Ś' : 'S', 'ŝ' : 'S', 'Ŝ' : 'S', 'š' : 'S', 'Š' : 'S', 'ṡ' : 'S', 'Ṡ' : 'S', 'ş' : 'S', 'Ş' : 'S', 'ș' : 'S', 'Ș' : 'S', 'ß' : 'S', 'ť' : 'T', 'Ť' : 'T', 'ṫ' : 'T', 'Ṫ' : 'T', 'ţ' : 'T', 'Ţ' : 'T', 'ț' : 'T', 'Ț' : 'T', 'ŧ' : 'T', 'Ŧ' : 'T', 'ú' : 'U', 'Ú' : 'U', 'ù' : 'U', 'Ù' : 'U', 'ŭ' : 'U', 'Ŭ' : 'U', 'û' : 'U', 'Û' : 'U', 'ů' : 'U', 'Ů' : 'U', 'ű' : 'U', 'Ű' : 'U', 'ũ' : 'U', 'Ũ' : 'U', 'ų' : 'U', 'Ų' : 'U', 'ū' : 'U', 'Ū' : 'U', 'ư' : 'U', 'Ư' : 'U', 'ü' : 'U', 'Ü' : 'U', 'ẃ' : 'W', 'Ẃ' : 'W', 'ẁ' : 'W', 'Ẁ' : 'W', 'ŵ' : 'W', 'Ŵ' : 'W', 'ẅ' : 'W', 'Ẅ' : 'W', 'x' : 'X', 'X' : 'X', 'ý' : 'Y', 'Ý' : 'Y', 'ỳ' : 'Y', 'Ỳ' : 'Y', 'ŷ' : 'Y', 'Ŷ' : 'Y', 'ÿ' : 'Y', 'Ÿ' : 'Y', 'ź' : 'Z', 'Ź' : 'Z', 'ž' : 'Z', 'Ž' : 'Z', 'ż' : 'Z', 'Ż' : 'Z', 'þ' : 'th', 'Þ' : 'T', 'µ' : 'U', 'а' : 'A', 'А' : 'A', 'б' : 'B', 'Б' : 'B', 'в' : 'v', 'В' : 'v', 'г' : 'G', 'Г' : 'G', 'д' : 'D', 'Д' : 'D', 'е' : 'E', 'Е' : 'E', 'ё' : 'E', 'Ё' : 'E', 'ж' : 'Z', 'Ж' : 'z', 'з' : 'Z', 'З' : 'Z', 'и' : 'I', 'И' : 'I', 'й' : 'J', 'Й' : 'J', 'к' : 'K', 'К' : 'K', 'л' : 'L', 'Л' : 'L', 'м' : 'M', 'М' : 'M', 'н' : 'N', 'Н' : 'N', 'о' : 'O', 'О' : 'O', 'п' : 'P', 'П' : 'P', 'р' : 'R', 'Р' : 'R', 'с' : 'S', 'С' : 'S', 'т' : 'T', 'Т' : 'T', 'у' : 'U', 'У' : 'U', 'ф' : 'F', 'Ф' : 'F', 'х' : 'H', 'Х' : 'H', 'ц' : 'C', 'Ц' : 'C', 'ч' : 'ch', 'Ч' : 'ch', 'ш' : 'sh', 'Ш' : 'sh', 'щ' : 'sch', 'Щ' : 'S', 'ъ' : '', 'Ъ' : '', 'ы' : 'Y', 'Ы' : 'Y', 'ь' : '', 'Ь' : '', 'э' : 'E', 'Э' : 'E', 'ю' : 'ju', 'Ю' : 'J', 'я' : 'J', 'Я' : 'J'};

        var orderedList = {'A': [], 'B': [], 'C': [], 'D': [], 'E': [], 'F': [], 'G': [], 'H': [], 'I': [], 'J': [], 'K': [], 'L': [], 'M': [], 'N': [], 'O': [], 'P': [], 'Q': [], 'R': [], 'S': [], 'T': [], 'U': [], 'V': [], 'W': [], 'X': [], 'Y': [], 'Z': []};

        for (var i = 0; i < listSort.length; i++) {
            var initial = listSort[i]['name'].charAt(0);
            var alphabeticEquivalent = alphabeticEquivalents[initial];
            orderedList[alphabeticEquivalent].push(listSort[i]);
        }
        return orderedList;
    }