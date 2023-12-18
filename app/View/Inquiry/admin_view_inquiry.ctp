<!--/RIGHT-BOX-->
<div class="right-box">
      <!--/HEADING-->
<div class="top-box">
    <div class="tp-line">
      <p> View Inquiries </p>
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
                        <td><strong>Name :</strong> </td>
                        <td><?php echo $inquiries['Inquiry']['name']; ?> </td>
                    </tr>		
                    
                    <tr>
                        <td><strong>Email :</strong> </td>
                        <td><?php echo $inquiries['Inquiry']['email']; ?> </td>
                    </tr>
                    
                    <tr>
                        <td><strong>Message :</strong> </td>
                        <td><?php echo $inquiries['Inquiry']['message']; ?> </td>
                    </tr>
                    
                    <tr>
                        <td><strong>Created :</strong> </td>
                        <td><?php echo $inquiries['Inquiry']['created']; ?> </td>
                    </tr>
				
		</tbody>			
	    </table>				
					
        </td>
        </tr>
    </tbody>
    </table>
</div>
</div>