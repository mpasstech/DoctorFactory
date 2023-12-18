function flash(title,message,type,position){
    $.alert(message, {
        autoClose: true,
        closeTime: 3000,
        withTime: false,
        type: type,
        position: [position, [-0.42, 0]],
        title: title,
        icon: false ,
        close: true,
        speed: 'normal',
        isOnly: true,
        minTop: 10,
        animation: false,
        animShow: 'fadeIn',
        animHide: 'fadeOut',
        onShow: function () {
        },
        onClose: function () {
        }
    });
}

function is_value_exist(array,serach){
    for(var count =0; count < array.length; count++){
        if(array[count] == serach){
            return true;
        }
    }
    return false;
}

function create_prescription_data(final_template_array,category_id,category_name,request_type){


    if(category_name) {


        var step_headings = [];
        var tag_array = [];
        var final_object = [];
        var append_array = [];
        if (final_template_array === false) {
            var counter = 0;
            $("#prescription_body .category_box_" + category_id + " .data_raw").each(function (index, value) {
                var key = ($(this).data('key')) ? $(this).data('key') : $(this).data('key').key;
                final_object[counter] = JSON.parse(key);
                append_array.push(JSON.parse(key));
                counter++;
            });
        } else {
            var counter = 0;
            $("#prescription_body .category_box_" + category_id + " .data_raw").each(function (index, value) {
                var key = ($(this).data('key')) ? $(this).data('key') : $(this).data('key').key;
                final_object[counter] = JSON.parse(key);
                append_array.push(JSON.parse(key));
                counter++;
            });

            $.each(final_template_array, function (first_index, final) {
                final_object[counter] = (final);
                append_array.push(final);
                counter++;
            });
        }
        $(final_object).each(function (first_index, final) {
            $(final).each(function (index, main_value) {
                $(main_value).each(function (index, steps) {
                    if (!is_value_exist(step_headings, steps.step_title)) {
                        step_headings.push(steps.step_title);
                    }
                });
            });
        });
        var counter = 0;
        $(final_object).each(function (index_1, main_array) {
            var tag_object = {};
            $(main_array).each(function (index_2, template) {
                $(template).each(function (index_3, steps) {
                    var tmp = [];
                    $(steps.selected_tag).each(function (index_4, tags) {
                        if (is_value_exist(step_headings, steps.step_title)) {
                            tmp.push(tags.tag_title);
                        }
                    });
                    tag_object[steps.step_title] = tmp.join(',');
                });
            });
            tag_array[index_1] = (tag_object);
        });
        var container_length = $("#prescription_body .category_box_" + category_id).length;
        var container_object = $("#prescription_body .category_box_" + category_id);
        var string = "";
        string += "<div  data-i='" + category_id + "' class='cat_box category_box_" + category_id + "'>";
        string += "<h4 class='category_title_" + category_id + "'>" + category_name + "</h4>";
        string += "<table style='width: 100%;' class='category_" + category_id + "' data-cn='" + category_name + "' data-ci='" + category_id + "'>";

        if (category_id == medicine_master_category_id) {
            $(step_headings).each(function (index, heading) {
                string += "<th>" + heading + "</th>";
            });
        }
        var _json_string = [];
        $(tag_array).each(function (index, tag_object) {

            _json_string.push(JSON.stringify(append_array[index]));
            var id = "data_raw_" + category_id + "_" + index;

            if (category_id == medicine_master_category_id ) {
                string += "<tr id =" + id + " class='data_raw'  onclick='change_color(this,true);'>";
                $(step_headings).each(function (index, heading) {
                    if (tag_object[heading]) {
                        string += "<td>" + tag_object[heading] + "</td>";
                    } else {
                        string += "<td> - </td>";
                    }

                });
                string += "</tr>";
            } else {
                string += "<tr id =" + id + " class='data_raw'  onclick='change_color(this,true);'>";
                var temp_data = [];
                $(step_headings).each(function (index, heading) {
                    if (tag_object[heading]) {
                        temp_data.push(tag_object[heading]);
                    }
                });
                string += "<td>" + temp_data.join(":") + "</td></tr>";
            }

        });
        string += "</table>";
        string += "</div>";
        if (container_length > 0) {
            $(container_object).replaceWith(string);
        } else {
            $("#prescription_body").append(string);
        }

        $(".category_" + category_id + " .data_raw").each(function (index, value) {
            var id = "data_raw_" + category_id + "_" + index;
            $("#" + id).data('key', _json_string[index]);
            $("#" + id).attr('data-key', $("#" + id).data('key'));
        });
        setTemplateArray("");
        $('#prescription_body .cat_box .data_raw').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: 'Delete this tag?',
            popout: true,
            singleton: true,
            container: 'body',
            onConfirm: function () {
                var category_name = $(this).closest('table').attr('data-cn');
                var category_id = $(this).closest('table').attr('data-ci');
                $(this).remove();
                if ($("#prescription_body .category_" + category_id + " .data_raw").length == 0) {
                    $(".category_box_" + category_id).remove();
                } else {
                    create_prescription_data(false, category_id, category_name);
                }
                $(this).confirmation('hide');

            },
            onCancel: function () {
                $(this).confirmation('hide');
                change_color(this, false);
            }
        });

    }


}


function change_color(obj,show){
    $("#prescription_body .cat_box tr td").css('color','black');
    if(show===true){
        $(obj).find('td').css('color','red');
    }
}



var template_array;
function getTemplateArray(){
    return template_array;
}
function setTemplateArray(array) {
    template_array = array;

}

function updateTemplateCount(){
    $("#total_template").html(template_array.length);
}

(function($) {
    $.extend($.fn, {
        makeCssInline: function() {
            this.each(function(idx, el) {
                var style = el.style;
                var properties = [];
                for(var property in style) {
                    if($(this).css(property)) {
                        properties.push(property + ':' + $(this).css(property));
                    }
                }
                this.style.cssText = properties.join(';');
                $(this).children().makeCssInline();
            });
        }
    });
}($));
