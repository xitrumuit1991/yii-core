<?php

class QuangCao extends _BaseModel 
{
	public $maxImageFileSize = 5242880; //5MB

	public $allowImageType = 'jpg,gif,png';
	public $uploadImageFolder = 'upload/quangcao'; //remember remove ending slash
	public $defineImageSize = array(
			'image' => array(
							array('alias' => QUANG_CAO_DANG_TIN, 'size' => QUANG_CAO_DANG_TIN),
							array('alias' => QUANG_CAO_TIN_CHI_TIET, 'size' => QUANG_CAO_TIN_CHI_TIET),
						), 
				);	
	public function tableName()
	{
		return '{{_static_banner}}';
	}

	public function rules()
	{
		return array(
			// array('image,status', 'required'),
			array('status, order_display', 'numerical', 'integerOnly'=>true),
			array('image, title, name_banner', 'length', 'max'=>255),
		 	array('image,status', 'required', 'on' => 'create, update'), 
		 
			array('image', 'file', 'on' => 'create,update',
				'allowEmpty' => true,
				'types' => $this->allowImageType,
				'wrongType' => 'Only ' . $this->allowImageType . ' are allowed.',
				'maxSize' => $this->maxImageFileSize, // 3MB
				'tooLarge' => 'The file was larger than' . ($this->maxImageFileSize/1024)/1024 . 'MB. Please upload a smaller file.',
			), 
			array('name_banner,link, id, image, title, status, content, created_date, order_display', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('translation','ID'),
			'image' => Yii::t('translation','Image'),
			'title' => Yii::t('translation','Title'),
			'status' => Yii::t('translation','Status'),
			'content' => Yii::t('translation','Content'),
			'created_date' => Yii::t('translation','Created Date'),
			'order_display' => Yii::t('translation','Order Display'),
			'name_banner'=>Yii::t('translation','Name Banner'),
			'link'=>Yii::t('translation','Link'),
		);
	}

	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('order_display',$this->order_display);
					
		 
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=> Yii::app()->params['defaultPageSize'],
            ),
		));
	}
	public function activate()
    {
        $this->status = 1;
        $this->update();
    }

    public function deactivate()
    {
        $this->status = 0;
        $this->update();
    }
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function showRecommendedSize()
	{
		$id = $this->id;
		if($id==1 || $id==2)
		{
			$size = QUANG_CAO_DANG_TIN; //350x400
		}else if($id==3 || $id==4)
		{
			$size = QUANG_CAO_TIN_CHI_TIET;
		}
		
		$size = explode('x', $size);
		return 'Recommended Size: '
				.$size[0].' px x '.$size[1].' px (width x height). Allow: *.jpg, *.png, *.gif - Maximum file size : '
				.(($this->maxImageFileSize/1024)/1024).' Mb';
	}


	public function showImageUpdate()
	{
		$id = $this->id;
		if($id==1 || $id==2)
		{
			$size = QUANG_CAO_DANG_TIN; //350x400
		}else if($id==3 || $id==4)
		{
			$size = QUANG_CAO_TIN_CHI_TIET;
		}
		if(!empty($size))
			return '<img src="'.$this->getImageUrl('image', $size ).'" />';
	}
	
}
