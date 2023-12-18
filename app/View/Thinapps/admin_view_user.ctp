<!--/RIGHT-BOX-->
<div class="right-box">
      <!--/HEADING-->
<div class="top-box">
    <div class="tp-line">
      <p><?php echo $users['Thinapp']['name']; ?>'s View</p>
    </div>
</div>
      
<div class="form-box">
    <table class="tablesorter" border="0" cellpadding="0">
    <tbody>
        <tr valign="top">
        <td>
				
	<table class="tablesorter" cellpadding="0">
            <tbody>				
                    <tr>
                            <td><strong>App Name :</strong</td>
                            <td><?php echo $users['Thinapp']['name']; ?> </td>
                    </tr>

                    <tr>
                            <td><strong>App Key :</strong> </td>
                            <td><?php echo $users['Thinapp']['app_id']; ?> </td>
                    </tr>

                    <tr>
                            <td><strong>UserName : </strong></td>
                            <td><?php echo $users['Thinapp']['user_id']; ?> </td>
                    </tr>

                    
            </tbody>			
	    </table>				
					
        </td>
        </tr>
    </tbody>
    </table>
</div>
</div>
