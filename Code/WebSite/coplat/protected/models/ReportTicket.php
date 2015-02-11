<?php

/**
 * This is the model class for table "report_ticket".
 *
 * The followings are the available columns in table 'report_ticket':
 * @property string $ticketID
 * @property string $creatorID
 * @property string $creatorName
 * @property integer $creatorDisabled
 * @property string $creatorEmail
 * @property string $ticketStatus
 * @property string $ticketCreatedDate
 * @property string $ticketSubject
 * @property string $ticketDescription
 * @property string $ticketAssignUserID
 * @property string $assignedUserName
 * @property integer $assignedUserDisabled
 * @property string $assignedUserEmail
 * @property string $ticketDomainID
 * @property string $ticketDomainName
 * @property string $ticketSubDomticketainID
 * @property string $ticketSubDomainName
 * @property integer $ticketPriority
 * @property string $ticketPriorityDescription
 * @property string $ticketAssignedDate
 * @property string $ticketClosedDate
 * @property integer $ticketIsEscalated
 */
class ReportTicket extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReportTicket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report_ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creatorID, creatorEmail, ticketStatus, ticketCreatedDate, ticketSubject, ticketDescription, ticketDomainID, ticketDomainName, ticketPriority, ticketPriorityDescription', 'required'),
			array('creatorDisabled, assignedUserDisabled, ticketPriority, ticketIsEscalated', 'numerical', 'integerOnly'=>true),
			array('ticketID, creatorID, ticketAssignUserID, ticketDomainID, ticketSubDomticketainID', 'length', 'max'=>11),
			array('creatorName, assignedUserName', 'length', 'max'=>192),
			array('creatorEmail, assignedUserEmail', 'length', 'max'=>255),
			array('ticketStatus, ticketSubject, ticketDomainName, ticketSubDomainName, ticketPriorityDescription', 'length', 'max'=>45),
			array('ticketDescription', 'length', 'max'=>500),
			array('ticketAssignedDate, ticketClosedDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ticketID, creatorID, creatorName, creatorDisabled, creatorEmail, ticketStatus, ticketCreatedDate, ticketSubject, ticketDescription, ticketAssignUserID, assignedUserName, assignedUserDisabled, assignedUserEmail, ticketDomainID, ticketDomainName, ticketSubDomticketainID, ticketSubDomainName, ticketPriority, ticketPriorityDescription, ticketAssignedDate, ticketClosedDate, ticketIsEscalated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ticketID' => 'Ticket',
			'creatorID' => 'Creator',
			'creatorName' => 'Creator Name',
			'creatorDisabled' => 'Creator Disabled',
			'creatorEmail' => 'Creator Email',
			'ticketStatus' => 'Ticket Status',
			'ticketCreatedDate' => 'Ticket Created Date',
			'ticketSubject' => 'Ticket Subject',
			'ticketDescription' => 'Ticket Description',
			'ticketAssignUserID' => 'Ticket Assign User',
			'assignedUserName' => 'Assigned User Name',
			'assignedUserDisabled' => 'Assigned User Disabled',
			'assignedUserEmail' => 'Assigned User Email',
			'ticketDomainID' => 'Ticket Domain',
			'ticketDomainName' => 'Ticket Domain Name',
			'ticketSubDomticketainID' => 'Ticket Sub Domticketain',
			'ticketSubDomainName' => 'Ticket Sub Domain Name',
			'ticketPriority' => 'Ticket Priority',
			'ticketPriorityDescription' => 'Ticket Priority Description',
			'ticketAssignedDate' => 'Ticket Assigned Date',
			'ticketClosedDate' => 'Ticket Closed Date',
			'ticketIsEscalated' => 'Ticket Is Escalated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        $criteria->compare('ticketID',$this->ticketID,true);
        //$criteria->compare('creatorID',$this->creatorID,true);
        //$criteria->compare('creatorName',$this->creatorName,true);
        //$criteria->compare('creatorDisabled',$this->creatorDisabled);
        //$criteria->compare('creatorEmail',$this->creatorEmail,true);
        //$criteria->compare('ticketStatus',$this->ticketStatus,true);
        //$criteria->compare('ticketCratedDate',$this->ticketCratedDate,true);
        //$criteria->compare('ticketSubject',$this->ticketSubject,true);
        //$criteria->compare('ticketDescription',$this->ticketDescription,true);
        //$criteria->compare('ticketAssignUserID',$this->ticketAssignUserID,true);
        //$criteria->compare('assignedUserName',$this->assignedUserName,true);
        //$criteria->compare('assignedUserDisabled',$this->assignedUserDisabled);
        //$criteria->compare('assignedUserEmail',$this->assignedUserEmail,true);
        //$criteria->compare('ticketDomainID',$this->ticketDomainID,true);
        //$criteria->compare('ticketDomainName',$this->ticketDomainName,true);
        //$criteria->compare('ticketSubDomticketainID',$this->ticketSubDomticketainID,true);
        //$criteria->compare('ticketSubDomainName',$this->ticketSubDomainName,true);
        //$criteria->compare('ticketPriority',$this->ticketPriority);
        //$criteria->compare('ticketPriorityDescription',$this->ticketPriorityDescription,true);
        //$criteria->compare('ticketAssignedDate',$this->ticketAssignedDate,true);
        //$criteria->compare('ticketClosedDate',$this->ticketClosedDate,true);
        //$criteria->compare('ticketIsEscalated',$this->ticketIsEscalated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}