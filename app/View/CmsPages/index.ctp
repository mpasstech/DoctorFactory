<script language="javascript">
    $(document).ready(function () {
        $('#select_all').on('click', function () {
            if (this.checked) {
                $('.checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $('.checkbox').on('click', function () {
            if ($('.checkbox:checked').length === $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.action-delete').click(function () {
            var n = $(".checkbox:checked").length;
            if (n == 0)
            {
                alert("Please select atleast one for delete.")
                event.preventDefault();
            }
            else {
                if (confirm('Are you sure, want to delete selected ?'))
                    $('#mainform').submit();
                    event.preventDefault();
            }
        });
    });

    function userCat(g_id)
    {

        var group_id = g_id.value;
        var url1 ="<?php echo SITE_URL."admin/pagemanager/view/"?>"+group_id;
       // window.location=url1;

    }
</script>
<?php
$channelId = '';

if (!empty($channel)) {
    $channelId = $channel['channels']['id'];
}


?>

<style>
    #fileajaxloader{ display:none;}
    #fileajaxloader img {bottom: 18px;
                         height: 18px;
                         margin-left: 100px;
                         position: relative;
                         width: 18px;}
    #addajaxloader{display:none;}
    #addajaxloader img{ bottom: 18px;
                        height: 18px;
                        left: 160px;
                        position: relative;
                        width: 18px;}

</style>

<div class="right-box">



    <div id="table-block">



        <div class="Subscribers-discretion">
            <!--/heading-->

<div class="select-all-box">
    <!-- CHACK BOX & DELETE TEXT -->
<div class="chack-block">
            <div class="all-Select-button">
                <p style="margin:0px;">
                    <input value="" type="checkbox" id="select_all">&nbsp; Select All&nbsp;|&nbsp;
                    <i class="fa fa-trash-o" style="color:#337AB7;"></i><a href="" class="action-delete"> Delete</a>

                </p>
            </div>
            <!--/search box-->
            <div class="src-box">
                <input type="text" class="  search-query form-control" placeholder="Search" id="skeyword" />
                    <span class="input-group-btn" style="float:right;">
                        <button class="btn btn-danger src-icon" type="button" onclick="searchaddsubscribe(<?php echo $channelId; ?>)" >
                           <img src="../img/search.png" alt="" width="20" />
                        </button>
                    </span>
            </div><div class="Current-Subscribers">
                                  <h2 id="data_approve">Pages(<?php
                              if (!empty($count)) {
                                  echo $count;
                              } else {
                                  echo '0';
                              }
                              ?>)
                                  </h2>
                              </div>
                              <input type="hidden" name="tsub" id="tsub" value="<?php
                              if (!empty($count)) {
                                  echo $count;
                              } else {
                                  echo '0';
                              }
                              ?>"  />


                              <!--/all-Select-button-->
        </div>

        <div class="msd-discretion">
            <div class="table-box1">

                <table class="table table-striped"><!--/table-->

                    <tbody>
<?php echo $this->Form->create('pagemanager',array('url'=>array('controller'=>'pagemanager','action' => 'admin_delete'),'id'=>'mainform', 'admin'=>true)); ?>
                        <tr class="HEader" >
                            <td>&nbsp;</td>
                            <td style="width:35%;">Name</td>
                            <td>description</td>
                            <td>Media</td>
                            <td>Created</td>
                            <td>Action</td>
                        </tr>

                        <?php
                        if (!empty($channel_data)) {
							$i=0;

                           foreach($channel_data as $channel)
                           {
								?>
                                <tr id="record_<?php echo $i; ?>">
                                    <td><input value="" type="checkbox" class="checkbox" name="deleteAll[]"></td>
                                    <td id="name_<?php echo $i; ?>"> <?php echo $channel['CmsPage']['title']; ?> </td>
                                    <td id="mobile_<?php echo $i; ?>"> <?php echo substr($channel['CmsPage']['description'],0,50); ?> </td>
                                    <td> <?php if(!empty($channel['CmsPage']['image'])){ ?>

										<img width ="100" height = "100" src="<?php echo $channel['CmsPage']['image']; ?>">

									<?php 	}

									$datetime = $channel['CmsPage']['created'];
									$date = date("Y-m-d",strtotime($datetime));
									 ?></td>


                                    <td>
                                        <?php echo $channel ['CmsPage']['created'];?>


                                    </td>
                                    <td>

             <a href="javascript:;" onclick="editPage('<?php echo $channel['CmsPage']['id']; ?>', '<?php echo $i; ?>');"> <i class="fa fa-check-square"></i> Edit </a>
                                    </td>

                                </tr>
                    <?php $i++; }
                } ?>
<?php echo $this->Html->link('', array('controller'=>'pagemanager','action'=>'admin_delete/'.$channel['CmsPage']['id'],'admin'=>true),
                        array('class'=>'fa fa-trash-o','onclick'=>'return confirm("Do you really want to delete this page")','title'=>'Delete')); ?>
                    </tbody>
                </table><!--/table-end-->

               <!--  <div class="paging">
                  <p>
                  <?php
                  echo $this->Paginator->counter(array(
                  'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                  ));
                  ?>
                </p><p>
                  <?php
                    echo $this->Paginator->prev('< ' . __('previous'), array('type'=>'button','class'=>'btn btn-primary'), null, array('type'=>'button','class' => 'prev disabled btn btn-primary'));
                    echo $this->Paginator->numbers(array('separator' => '','class'=>'btn btn-primary'));
                    echo $this->Paginator->next(__('next') . ' >', array('type'=>'button','class'=>'btn btn-primary'), null, array('type'=>'button','class' => 'next disabled btn btn-primary'));
                  ?>
                </p>
                  </div>  --><div class="back-btn" style="float: right;
                                                                                    width: 70px;
                                                                                    text-align: center;
                                                                                    position: absolute;
                                                                                    height: 28px;
                                                                                    border-radius: 4px;
                                                                                    margin-left: 0px;
                                                                                    margin-top: 10px;
                                                                                    background: #03a9f4;">
                                         <?php echo $this->Html->link('Back', array('controller' => 'users', 'action' => 'dashboard')); ?>
                                     </div>

            </div>
        </div>

    </div>
</div><!--/RIGHT-BOX-END-->



<script>

	$('.check').click(function()
	{
	  alert("hello");
	});

    function searchaddsubscribe(channelId,page){

        var keyword = $("#skeyword").val();
        if(keyword){
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);
        $.ajax({
            'url': '<?php echo SITE_URL . "channels/addsubscribe"; ?>',
            'data': {'channelId': channelId, 'limit': page,keyword:keyword},
            'type': 'post',
            'dataType': 'html',
            success: function (response) {
                //alert(response);
                //$(".right-box").html(response);
                //if(response=="Channel deleted successfully")
                //{
                $(".right-box").html(response);
                //}
            }
        });
        }
        else{
        alert("Please Enter Keyword");
        }
    }

    function addsubscribe(channelId, page,keyword) {
        var loading_htm = '<div class="load_content"><?php echo $this->Html->image('loader.gif'); ?><div>';
        $(".right-box").html(loading_htm);
        $.ajax({
            'url': '<?php echo SITE_URL . "channels/addsubscribe"; ?>',
            'data': {'channelId': channelId, 'limit': page,keyword:keyword},
            'type': 'post',
            'dataType': 'html',
            success: function (response) {
                //alert(response);
                //$(".right-box").html(response);
                //if(response=="Channel deleted successfully")
                //{
                $(".right-box").html(response);
                //}
            }
        });
    }

    function updatesubscribe(subscribeId) {

        var is_admin = $("#is_admin").val();
        if(is_admin){

        $.ajax({
            'url': '<?php echo SITE_URL . "channels/update_subscribe"; ?>',
            'data': {'subscriber_id': subscribeId,'is_admin': is_admin},
            'type': 'post',
            'dataType': 'html',
            success: function (response) {
                    location.reload(true);
                 }

            });
            }
            else{
            alert("Please Select Checkbox");
            $('#is_admin').focus();
            return false;
            }
        }


    function subscribe(channelId)
    {
        var username = $("#username").val();
        var mobile = $("#mobile").val();
        if (username == '')
        {
            alert('Please fill User Name');
            return false;
        }
        if (mobile == '')
        {
            alert('Please fill mobile number');
            return false;
        }

        $.ajax({
            'url': '<?php echo SITE_URL . "subscribers/add"; ?>',
            'data': {'channel_id': channelId, 'name': username, 'mobile': mobile},
            'type': 'post',
            'dataType': 'json',
            success: function (response) {
                if (response.msg == 'Subscriber added sucessfully')
                {  //alert(response.toSource());
                    alert('Subscriber added sucessfully');
                    var spanId = $("#tsub").val();
                    totalnumber = parseInt(spanId) + 1
                    //alert(spanId);
                    var username = $("#username").val();
                    var subid = response.id
                    $html = '<tr id="record_' + spanId + '"><td><input type="checkbox" value=""></td><td id="name_' + spanId + '">' + username + '</td><td id="mobile_' + spanId + '">+91' + mobile + '</td><td><input type="checkbox" value=""></td><td><a onclick="editsubscribe(' + subid + ',' + spanId + ')" href="javascript:;"><i class="fa fa-pencil"></i> Edit </a> &nbsp;/&nbsp; <a onclick="deletsubscriber(' + subid + ',' + spanId + ');" href="javascript:;"> <i class="fa fa-trash-o"></i> Delete</a></td><tr>';

                    $('#subscriberslist').append($html);
                    $("#totalsub").html('Current Subscribers(' + totalnumber + ')');
                }
                else
                {
                    alert(response.msg);

                }
                $("#username").val('');
                $("#mobile").val('');

                location.reload(true);
            }

        });

    }

    function deletsubscriber(subscribeId, recordid) {

        var r = confirm("Are you sure?");
        if (r == true) {
            $.ajax({
                'url': '<?php echo SITE_URL . "subscribers/subscriber_delete"; ?>',
                'data': {'subscriber_id': subscribeId},
                'type': 'post',
                'dataType': 'html',
                success: function (response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj.message == "Subscriber deleted successfully")
                    {
                        alert(obj.message);
                        currentsub = parseInt($("#tsub").val()) - 1;
                        $("#totalsub").html('Current Subscribers(' + currentsub + ')');
                        $("#record_" + recordid).hide();
                    }
                    else {
                        alert(obj.message)
                    }

                }
            });
        } else {
            //x = "You pressed Cancel!";
        }

    }


    function editPage(page_id, recordId, userid)
    {

		$.ajax({
                'url': '<?php echo SITE_URL . "CmsPages/edit"; ?>',
                'data': {'page_id': page_id},
                'type': 'post',
                'dataType': 'html',
                success: function (response) {
                    $(".right-box").html(response);
                    CKEDITOR.replace("data[CmsPage][description]",{toolbar : 'Advanced', uiColor : '#9AB8F3'});

                }
            });
     }

    function editsubscribe(subsriberId, recordId)
    {
        //alert(recordId);
        var name = $("#name_" + recordId).html();
        var mobilenumber = $("#mobile_" + recordId).html();
        // alert(mobilenumber);return false;
        mobilenumber = mobilenumber.substring(3);
        $("#username").val(name);
        $("#mobile").val(mobilenumber);
        $('#addsub').html('Update');
        // alert($("#addsub").attr("onclick"));
        $("#subscribefunction").val($("#addsub").attr("onclick"));
        $("#addsub").attr("onclick", "editsubuser(" + subsriberId + "," + recordId + ")");
    }

    function editsubuser(subid, recordId)
    {

        var name = $("#username").val();
        var mobilenumber = $("#mobile").val();

        $.ajax({
            'url': '<?php echo SITE_URL . "subscribers/edit"; ?>',
            'data': {'subscriber_id': subid, 'name': name, 'mobilenumber': mobilenumber},
            'type': 'post',
            'dataType': 'html',
            success: function (response) {
                alert(response);
                //alert(name);
                //alert(mobilenumber);
                $("#name_" + recordId).html(name);
                $("#mobile_" + recordId).html('+91' + mobilenumber);
                $('#addsub').html('Add');
                $("#addsub").attr("onclick", $("#subscribefunction").val());
                $("#username").val('');
                $("#mobile").val('');

            }

        });
    }


    $("#upload").on('click', function () {

        $('#file_upload').trigger('click');
        $("#uploadfiles").val($('#file_upload').val());

        return false;
    });

    $("#fileupload").on('click', function () {

        var fileVal = $('#file_upload').val();

        $("#uploadfiles").val(fileVal);
        if ($("#uploadfiles").val() == "") {
            alert("Please upload file.");
            return false;
        }

        $("#fileajaxloader").show();
        //$('.isa_info').slideDown();
        $('#upload_file').attr('disabled', 'disabled');
        $("#upload_file_form").ajaxForm({
            'url': '<?php echo SITE_URL . "subscribers/upload"; ?>',
            'contentType': false,
            'processData': false,
            'data': fileVal,
            'type': 'POST',
            'dataType': 'html',
            'enctype': 'multipart-form/data',
            success: function (resp) {
                $("#fileajaxloader").hide();
                console.log(resp);
                $("#uploadfiles").val('');
                if (resp !== '') {

                    $('#upload_file').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_success').html('File Upload successfully.');
                    $('.isa_success').show();
                    $('#upload_file').val('');
                    $('#file_upload').val('');
                    setTimeout(function () {
                        $('.isa_success').slideUp();
                    }, 2000);
                } else {
                    $('#upload_file').removeAttr('disabled');
                    $('.isa_info').hide();
                    $('.isa_hide').show();
                    $('.isa_error').html('Invalid file format!');
                    $('.isa_error').show();
                    setTimeout(function () {
                        $('.isa_error').slideUp();
                    }, 2000);
                }
                location.reload(true);
            }
        }).submit();


    });
</script>
<script src="http://malsup.github.com/jquery.form.js"></script>
