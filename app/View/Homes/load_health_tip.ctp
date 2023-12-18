<?php
if(!empty($list_data)){
    foreach ($list_data as $data) { ?>
        <li>
            <table style="width: 100%;background: #fff; min-height: 180px; box-shadow:0px 3px 7px #d4d4d4;border-radius:3px;">
                <tr>
                    <td colspan="3" style="padding: 6px 4px;background: #5a60ff;color:#fff;">
                        <label class="title"><?php echo $data['title']; ?></label>
                    </td>

                </tr>

                <tr>
                    <td colspan="1" style="width: 40%;">

                        <img onload="loadImag(this);" class="img-responsive load_img"  src="<?php echo $this->AppAdmin->beforeLodeImage(); ?>" data-src="<?php echo $data['image']; ?>"  />

                    </td>
                    <td  valign="top" colspan="2" class="description" style="width: 60%;">

                        <?php
                            $url = Router::url('/app_admin/view_cms_doc_dashboard/',true).base64_encode($data['id']);
                            $url = "<a href='".$url."' target = '_blank'>... Read More</a>";
                        ?>
                        <?php echo mb_strimwidth(strip_tags($data['description']), 0, 300, $url); ?>
                    </td>
                </tr>

                <tr><td colspan="3" style="padding: 0px;">
                     <span class="datespan" >
                            <?php
                            echo date('d M, Y H:i',strtotime($data['created']));

                            ?>
                        </span>
                    </td></tr>
                <tr class="bootom_tr">
                    <td><i class="fa fa-share" aria-hidden="true"></i> <?php echo $data['share_count']; ?></td>
                    <td><i class="fa fa-thumbs-up" aria-hidden="true"></i> <?php echo $data['like_count']; ?></td>
                    <td><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $data['view_count']; ?></td>
                </tr>

            </table>






        </li>
    <?PHP } }else{ ?>
    <label style="padding:10px;text-align: center;width: 100%; display: block;">No medical record found</label>
    <script>$("#load_more_tip").hide();</script>
<?php } ?>
<script>
    function loadImag(obj){
        var img_src = $(obj).attr('data-src');
        $(obj).attr('src',img_src);
        $(obj).removeClass('load_img');
    }
</script>
