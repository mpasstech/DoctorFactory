    <style>
        .tracker_row th, .tracker_row td{
            padding: 3px 1px !important;
        }
        .tracker_row{
            background: #4aaf27;
        }
        .tracker_row .refresh_tracker_btn{
            padding: 1px 7px;
            font-size: 0.7rem;
            border: 1px solid green;
            outline: none;
        }
    </style>
    <table id='tracker_tab_data' class="row_box" style="height:160px;width: 100%;text-align: center;padding: 2px;">
        <?php if( !empty($self)){  ?>
            <tr style="background: linear-gradient(#4aaf27, #83b970cf); color: #fff;">
                <th>Current Token</th>
                <th style="border-right: 2px solid #5aab5a;">Time</th>
                <th>Your Token</th>
                <th>Time</th>
            </tr>
            <tr style="background: #f5fff2;">
                <td style="font-size:2.2rem;color: green;">
                    <b><?php echo  $current['token_number']; ?></b>
                </td>
                <td style="font-size:1.5rem; border-right: 2px solid #5aab5a;">
                    <?php echo  $current['time']; ?>
                </td>

                <td style="font-size:2.2rem;color: green;">
                    <b><?php echo  $self['token_number']; ?></b>
                </td>
                <td style="font-size:1.5rem;">
                    <?php echo  $self['time']; ?>
                </td>


            </tr>
            <tr style="background: #f5fff2;">
            <td colspan="4" style="text-align: center; padding: 5px 1px !important; font-size: 1.2rem; ">
                <button type='button' class="xs btn btn-default refresh_tracker_btn">Reload</button>
            </td>

        </tr>
        <?php }else{ ?>
            <tr style="background: #f5fff2;">
                <td>
                    There is no token booked for today.
                    <span style="margin-top:5px;font-size:0.8rem; display: block;width: 100%;text-align: center;">To see your token list please click "My Token" option</span>
                    <script type='text/javascript'>setTimeout(function(){ $("#tracker_tab_data").fadeOut(300);},8000);</script>
                </td>
            </tr>
        <?php } ?>
    </table>

