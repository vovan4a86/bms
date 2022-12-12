var image = null;
function imageAttache(elem, e){
    $.each(e.target.files, function(key, file)
    {
        if(file['size'] > max_file_size){
            alert('Слишком большой размер файла. Максимальный размер 2Мб');
        } else {
            image = file;
            renderImage(file, function (imgSrc) {
                var item = '<img class="img-polaroid" src="' + imgSrc + '" height="100" data-image="' + imgSrc + '" onclick="return popupImage($(this).data(\'image\'))">';
                $('#article-image-block').html(item);
            });
        }
    });
    $(elem).val('');
}

function categorySave(form, e) {
    e.preventDefault();
    var url = $(form).attr('action');
    var data = new FormData(form);
    if (image) {
        data.append('image', image);
    };

    sendFiles(url, data, function(json){
        if (typeof json.errors !== 'undefined') {
            applyFormValidate(form, json.errors);
            var errMsg = [];
            for (var key in json.errors) { errMsg.push(json.errors[key]);  }
            $(form).find('[type=submit]').after(autoHideMsg('red', urldecode(errMsg.join(' '))));
        }
        if (typeof json.redirect !== 'undefined') document.location.href = urldecode(json.redirect);
        if (typeof json.msg !== 'undefined') $(form).find('[type=submit]').after(autoHideMsg('green', urldecode(json.msg)));
        newsImage = null;
    });

    return false;
}

function init_tree(div){
    var get_url = div.data('url');
    var edit_url = div.data('edit-url');
    var del_url = div.data('del-url');
    var default_url = div.data('default-url');
    var order_url = div.data('order-url');
    var click_url = div.data('click-url');


    div.jstree({
        "core": {
            "animation": 0,
            "check_callback": true,
            'force_text': false,
            "themes": {"stripes": true},
            'data': {
                'url': function (node) {
                    return node.id === '#' ? get_url : get_url + '/' + node.id;
                }
            },
        },
        "plugins": ["contextmenu", "dnd", "state", "types"],
        "contextmenu": {
            "items": function ($node) {
                var tree = $("#tree").jstree(true);
                return {
                    "Create": {
                        "icon": "fa fa-plus text-blue",
                        "label": "Создать страницу",
                        "action": function (obj) {
                            // $node = tree.create_node($node);
                            document.location.href = edit_url + '?select_category=' + $node.id
                        }
                    },
                    "Edit": {
                        "icon": "fa fa-pencil text-yellow",
                        "label": "Редактировать страницу",
                        "action": function (obj) {
                            // tree.delete_node($node);
                            document.location.href = edit_url + '/' + $node.id
                        }
                    },
                    "Remove": {
                        "icon": "fa fa-trash text-red",
                        "label": "Удалить страницу",
                        "action": function (obj) {
                            if (confirm("Действительно удалить страницу?")) {
                                var url = del_url + '/' + $node.id;
                                sendAjax(url, {}, function () {
                                    document.location.href = default_url;
                                })
                            }
                            // tree.delete_node($node);
                        }
                    }
                };
            }
        }
    }).bind("move_node.jstree", function (e, data) {
        treeInst = $(this).jstree(true);
        parent =  treeInst.get_node( data.parent );
        var d = {
            'id':   data.node.id,
            'parent': (data.parent == '#')? 0: data.parent,
            'sorted': parent.children
        };
        sendAjax(order_url, d);
    }).on("activate_node.jstree", function(e,data){
        if(data.event.button == 0){
            window.location.href = click_url + '?select_category=' + data.node.id;
        }
    });
}

$(document).ready(function () {
    if($('#category-tree').length){
        console.log('init');
        init_tree($('#category-tree'));
    }
});