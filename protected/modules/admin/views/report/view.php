<?php
$title_name = $model->buyer_name;
$this->breadcrumbs=array(
	$this->pluralTitle => array('index'),
	'View ' . $this->singleTitle . ' : ' . $title_name,
);

$this->menu = array(
    array('label'=> $this->pluralTitle, 'url'=>array('reportSale'), 'icon' => $this->iconList),	
    // array('label'=> 'Update '. $this->singleTitle, 'url'=>array('update', 'id'=>$model->id)),
	// array('label' => 'Create ' . $this->singleTitle, 'url' => array('create')),
);   

?>
<h1>View <?php echo $this->singleTitle . ' : ' . $title_name; ?></h1>

<?php
//for notify message
$this->renderNotifyMessage(); 
//for list action button
echo $this->renderControlNav();
?><div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> View <?php echo $this->singleTitle?></h3>
	</div>
	<div class="panel-body">
	<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array( 
        
				'no_id',

				'buyer_id',

				'buyer_name',

				'total_price',

				'total_makup_price',

				'total_credit',

				'total_prolit',

				'total_shiping_fee',

				'total_gst',

				'payment_method',

				'billing_address_id',
				array(
                        'name' => 'billing_address_data',
                        'type' => 'html',
                    ),

				'shipping_address_id',
				array(
                        'name' => 'shipping_address_data',
                        'type' => 'html',
                    ),
				array(
                        'name' => 'note',
                        'type' => 'html',
                    ),

				'file_po',

				'session_id',
				array(
                        'name' => 'created_date',
                        'type' => 'date',
                    ),
		),
	)); ?>
	<div class="well">
		<?php //echo CHtml::htmlButton('<span class="' . $this->iconBack . '"></span> Back', array('class' => 'btn btn-default', 'onclick' => 'javascript: location.href=\''.  $this->baseControllerIndexUrl() . '\'')); ?>	</div>
	</div>
</div>
