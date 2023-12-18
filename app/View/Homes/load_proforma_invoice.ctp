<?php if(!empty($inv_list)){ ?>
  <table class="table table-bordered">
      <tr>
          <th>S.No</th>
          <th>Order Number</th>
          <th>Patient Name</th>
          <th>Patient Mobile</th>
          <th>Amount</th>
          <th>Payment Status</th>
          <th>Patient Response</th>
          <th>Delivery Status</th>
          <th>Date</th>
          <th>Action</th>
      </tr>

<?php foreach ($inv_list as $key =>$list ){ ?>
        <tr>
        <td><?php echo $key+1; ?></td>
        <td><?php echo ($list['tx_status']=='SUCCESS')?$list['invoice_order_id']:''; ?></td>
        <td><?php echo $list['patient_name']; ?></td>
        <td><?php echo $list['patient_mobile']; ?></td>
        <td><?php echo $list['amount']; ?></td>
        <td><?php echo ucfirst(strtolower($list['tx_status'])); ?></td>
        <td><?php echo ucfirst(strtolower($list['patient_response'])); ?></td>
        <td><?php echo ucfirst(strtolower($list['deliverd_staus'])); ?></td>
        <td><?php echo date('d/m/Y h:i A',strtotime($list['created'])); ?></td>
        <td>

            <?php if(empty($list['medical_product_order_id'])){ ?>
                <a class="btn btn-info btn-sm" target="_blank" href="<?php echo Router::url('/homes/proforma_invoice/'.base64_encode($list['id']),true); ?>"><i class="fa fa-edit"></i> Create Invoice</a>
            <?php }else{ ?>
                <a class="btn btn-success btn-sm" target="_blank" href="<?php echo Router::url('/homes/patient_proforma_invoice/'.base64_encode($list['id']).'/0/y',true); ?>"><i class="fa fa-eye"></i> View</a>
            <?php } ?>



        </td>
        </tr>
<?php } ?>

  </table>
<?php }else{ ?>
    <h5 style="text-align: center;width: 100%;">No data found</h5>
<?php } ?>