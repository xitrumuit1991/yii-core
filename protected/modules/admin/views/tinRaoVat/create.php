<?php
$this->breadcrumbs=array(
	$this->pluralTitle => array('indexInactive'),
	'Create ' . $this->singleTitle,
);

$this->menu = array(		
        // array('label'=> $this->pluralTitle , 'url'=>array('indexInactive'), 'icon' => $this->iconList),
);

?>

<h1>Create <?php echo $this->singleTitle; ?></h1>

<?php
//for notify message
$this->renderNotifyMessage(); 
//for list action button
echo $this->renderControlNav();
?><?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
