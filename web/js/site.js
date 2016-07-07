$(document).ready(function(){
    $('body').on('click', '.row__title.mini', function(e){
        e.preventDefault();
        $(this).removeClass('mini');
    });

    $('.scroll').jscroll({
        debug: true,
        autoTrigger: true,
        autoTriggerUntil: false,
        loadingHtml: '<img src="/images/loading.gif" alt="Loading" /> Загрузка...',
        padding: 20,
        nextSelector: 'a.jscroll-next:last',
        callback: function(){
            console.log('loaded');
        }
    });

    var select_posts = [];
    var Post = function(element){
        return {
            id: element.data('id'),
            owner_id: element.data('owner_id'),
            from_id: element.data('from_id'),
            text: element.find('.row__title').text().trim(),
            date: element.data('date'),
            signer: element.data('signer'),
            attachments: $.map(element.find('.attachment'), function (attachment) {
                return $(attachment).data('attachment');
            })
        }
    };
    var select_post_nav = $('.select_post_nav');
    var select_post_nav_text = $('.select_post_nav a');

    var onchange_select_post = function(e){
        var elem = e.target ? $(e.target).parents('.row_item') : e;
        var id = elem.data('id');

        if (elem.data('selected')) {
            select_posts = select_posts.filter(function(post){
                return post.id !== id;
            });
            elem.data('selected', false);
        } else {
            select_posts.push({
                id: id,
                elem: elem,
                post: new Post(elem)
            });
            elem.data('selected', true);
        }

        select_post_nav_text.text("Выбранно "+select_posts.length+" постов, опубликовать все");
        select_posts.length > 0 ? select_post_nav.show() : select_post_nav.hide();
    };
    $('body').on('change', '.row_checkbox', onchange_select_post);

    $('.post_modal').modal('hide');

    var shown_posts = [];
    var set_post_modal = function(item){
        post = item.post;
        $('.post_modal .post_modal_text').val(post.text + '\n\n @id' + post.signer);
        $('.post_modal .post_modal_attachments').val(post.attachments.join('\n'));
        $('.post_modal .post_modal_date').text(' ' + (new Date(post.date * 1000)).toLocaleDateString() + ' ' + (new Date(post.date * 1000)).toLocaleTimeString() + ' ');
    };
    var show_post_modal = function(item){
        item = item || shown_posts.splice(0, 1)[0];
        if (item){
            set_post_modal(item);
            $('.post_modal').data('id', item.id).modal('show');

            delete_select_post(item.id);
        }
    };
    var delete_select_post = function(id){
        var post = select_posts.filter(function(item){
            return item.id === id;
        })[0];
        post && post.elem.find('.row_checkbox').prop("checked", false) && onchange_select_post(post.elem);
    };

    $('.post_modal').on('hidden.bs.modal', function(){
        show_post_modal();
    });

    select_post_nav.click(function(){
        shown_posts = select_posts.slice().sort(function(a, b){
            return a.post.date < b.post.date;
        });
        show_post_modal();
    });

    $('body').on('click', '.row_btn', function(e){
        var elem = $(e.target).parents('.row_item');
        var id = elem.data('id');
        show_post_modal({
            id: id,
            elem: elem,
            post: new Post(elem)
        });
    });

    $('.post_modal').on('click', '.post_modal_close', function(e){
        var id = $(e.target).parents('.modal').data('id');

        delete_select_post(id);

        $('.post_modal').modal('hide');
    });

    $('.post_modal').on('click', '.post_modal_save', function(e){
        var elem = $(e.target).parents('.modal');
        var id   = elem.data('id');

        var post = select_posts.filter(function(item){
            return item.id === id;
        })[0];

        var elem_post;
        var data = {};

        if(post){
            post.elem.find('.row_checkbox').prop("checked", true).prop("disabled", true);
            onchange_select_post(post.elem);
            elem_post = post.elem;
            post = post.post;
        }else{
            elem_post = $('.row_item[data-id='+id+']');
            post = new Post(elem_post);
        }

        var publish_date = '';

        if ($('.publish_date_check').prop("checked")){
            var times = $(".timepicker-select").combodate("getValue").split(':');
            var date = $(".datepicker-select").datepicker("getDate");
            if (date && times) {
                date.setHours(times[0] ? times[0] : 0);
                date.setMinutes(times[1] ? times[1] : 0);
            }

            publish_date = date && date.getTime() - (new Date()).getTime() > 1000 * 60 ? date.getTime() / 1000 : '';
        }

        var from_group = $('.from_group_check').prop("checked") ? 1 : 0;
        var signed_check = $('.signed_check').prop("checked") ? 1 : 0;

        var owner_id = $('.owner_id_select').val();

        var message = $('.post_modal_text').val();
        var attachments = $('.post_modal_attachments').val().split('\n').join(',');

        VK.api('wall.post', {
            'message': message,
            'attachments': attachments,
            'publish_date': publish_date,
            'from_group': from_group,
            'signed': signed_check,
            'owner_id': -1 * owner_id
        }, function(responce){

            console.log('responce', responce);

            if(responce.error){
                //elem_post.addClass('delete');
            }else{
                elem_post.addClass('save');
                $('.post_modal').modal('hide');
            }
        });
    });

    $('.datepicker-select').datepicker({
        language: 'ru-RU'
    });

    $('.timepicker-select').combodate({
        firstItem: 'name', //show 'hour' and 'minute' string at first item of dropdown
        minuteStep: 1
    });

    $('.publish_date_check').click(function(){
        $('.publish_date_block').toggle();
    });

    $.getJSON("/site/groups-admin", function(data){
        var owner_id_select = $('.owner_id_select');

        $.each(data.response.items, function(key, value) {
            owner_id_select.append($("<option></option>").attr("value",value.id).text(value.name));
        });
    });
});