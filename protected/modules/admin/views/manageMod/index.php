<style type="text/css">
	.hidden {
	    display: none;
	}
</style>
<?php
$this->breadcrumbs=array(
	$this->pluralTitle,
);
$this->menu=array(
	array('label'=>'Create ' . $this->singleTitle, 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('users-grid', {
                url : $(this).attr('action'),
		data: $(this).serialize()
	});
	return false;
});

$('#clearsearch').click(function(){
	var id='search-form';
	var inputSelector='#'+id+' input, '+'#'+id+' select';
	$(inputSelector).each( function(i,o) {
		 $(o).val('');
	});
	var data=$.param($(inputSelector));
	$.fn.yiiGridView.update('users-grid', {data: data});
	return false;
});

$('.deleteall-button').click(function(){
        var atLeastOneIsChecked = $('input[name=\"users-grid_c0[]\"]:checked').length > 0;
        if (!atLeastOneIsChecked)
        {
                alert('Please select at least one record to delete');
        }
        else if (window.confirm('Are you sure you want to delete the selected records?'))
        {
                document.getElementById('users-grid-bulk').action='" . Yii::app()->createAbsoluteUrl('admin/' . Yii::app()->controller->id  . '/deleteall') . "';
                document.getElementById('users-grid-bulk').submit();
        }
});

");

Yii::app()->clientScript->registerScript('ajaxupdate', "
    $('#users-grid a.ajaxupdate').live('click', function() {
        $.fn.yiiGridView.update('users-grid', {
            type: 'POST',
            url: $(this).attr('href'),
            success: function() {
                $.fn.yiiGridView.update('users-grid');
            }
        });
        return false;
    });
");
?>
<h1><?php echo $this->pluralTitle; ?></h1>
<?php echo CHtml::link(Yii::t('translation','Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class='search-form' style='display:none'>
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?></div>

<?php echo $this->renderControlNav();?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><span class="<?php echo $this->iconList; ?>"></span> Listing</h3>
	</div>
	<div class="panel-body">
		<?php 
			$allowAction = in_array("delete", $this->listActionsCanAccess)?'CCheckBoxColumn':'';
			$columnArray = array();
			if (in_array("Delete", $this->listActionsCanAccess))
			{
				
				$columnArray[] = array(
										'header' => '&nbsp;',
                                        'value'=>'$data->id',
                                        'class'=> "CCheckBoxColumn",
                                        // 'selectableRows' => 2,
                                        // 'visible'=>'false',
                                        // 'htmlOptions'=>array('style'=>'display: none; '),
                                        // 'htmlOptions'=>array( 'disabled'=>'disabled'),
                                        // 'disable'=>' ( $data->checkExistOrder() > 0 ) ? false:true ',
                                        // 'cssClassExpression'=>'( $data->checkExistOrder() > 0 ) ? "hidden" : "" ',
                                        // 'value'=>'( $data->checkExistOrder() ) ? "" : "" ',
                                        // 'htmlOptions' => array('style' => ' ( $data->checkExistOrder() > 0 ) ? "disable":"" ')
                                        // 'disabled'=>' ( $data->checkExistOrder() > 0 ) ? true : false  ',
                                        // 'disabled'=>'Cause::getRowDisable($data->donCount, $data->limit)',

                                );
			}
			$columnArray = array_merge($columnArray, array(
				array(
                                        'header' => 'S/N',
                                        'type' => 'raw',
                                        'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                                        'headerHtmlOptions' => array('width' => '30px','style' => 'text-align:center;'),
                                        'htmlOptions' => array('style' => 'text-align:center;')
                                ),
                                'username',
                                'full_name',
                                'email',
                                array(
                                    'header' => 'Contact Number',
                                    'value' => '$data->phone'
                                ),
                                
                                array(
                                    'name'=>'status',
                                    'type'=>'status',
                                    'value'=>'array("id"=>$data->id,"status"=>$data->status)',
                                    'htmlOptions' => array('style' => 'text-align:center;')
                                   ),
                                array(
                                    'name' => 'created_date',
                                    'type' => 'date',
                                    'htmlOptions' => array('style' => 'text-align:center;')
                                ),
                                
                                array(
                                                'header' => 'Actions',
                                                'class'=>'CButtonColumn',
                                                'template'=> '{view}{update}{delete}',
                                                // 'buttons' => array(
                                                		// 'delete' => array( 'visible'=>' ( $data->checkExistOrder() > 0 ) ? false:true ' ),
                                                		// 'delete' => array( 'visible'=>'true' ),
                                                		// 'update' => array('visible' => 'strpos("' . $actions . '", "update") !== false'),
                                                		// 'view' => array('visible' => 'strpos("' . $actions . '", "view") !== false')
                                                		// ), 
                                        ),
			));
			$form=$this->beginWidget('CActiveForm', array(
			'id'=>'users-grid-bulk',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data')));

			$this->renderNotifyMessage(); 
			$this->renderDeleteAllButton(); 
			
			$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'users-grid',
				'dataProvider'=>$model->searchMod(),
				'pager'=>array(
							'header'         => '',
							'prevPageLabel'  => 'Prev',
							'firstPageLabel' => 'First',
							'lastPageLabel'  => 'Last',
							'nextPageLabel'  => 'Next',
						),
				'selectableRows'=>2,
				'columns'=>$columnArray,
		)); 
		$this->endWidget();
		?>
