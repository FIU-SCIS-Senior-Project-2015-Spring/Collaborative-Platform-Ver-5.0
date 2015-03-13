<?php
class DimensionType
{
    const Date =1;
    const Year =2;
    const MonthOfTheYear = 3;  
    const TicketAssignedMentor = 4;
    
    
    public static function getDimensions()
    {
     return  array(DimensionType::Date  =>DimensionType::getDescriptionByDateDimension(DimensionType::Date),
                   DimensionType::MonthOfTheYear  =>DimensionType::getDescriptionByDateDimension(DimensionType::MonthOfTheYear),
                   DimensionType::Year  =>DimensionType::getDescriptionByDateDimension(DimensionType::Year));
    }
    
    public static function getDescriptionByDateDimension($dimensionType)
     {
       switch ($dimensionType)
       {
           case DimensionType::Date:
                   $dimensionDesc = "day";   
               break;            
           case DimensionType::MonthOfTheYear:
                   $dimensionDesc = "month"; 
               break;           
           case DimensionType::Year:
                  $dimensionDesc = "year"; 
               break;
           default:
               throw new CException("Invalid dimension");
       }
       return $dimensionDesc;
     }
     
     public static function getDateFormatByDimension($dimensionType)
     {
         switch ($dimensionType)
       {
           case DimensionType::Date:
                   $dimensionFormat = "dd MMM yyyy";   
               break;            
           case DimensionType::MonthOfTheYear:
                   $dimensionFormat = "MMM yyyy"; 
               break;           
           case DimensionType::Year:
                  $dimensionFormat = "yyyy"; 
               break;
           default:
               throw new CException("Invalid dimension");
       }
       return $dimensionFormat;
     }
    
           
    
}


class ReportType
{
    const None = 0;
    const TicketsCreated =1;
    const TicketsClosed =2;
        
    public static function getReportTypeDescription($reportType)
    {
       $res = "";
       switch ($reportType)
       {
           case ReportType::TicketsCreated:
                   $res = "Tickets created";   
               break;            
           case ReportType::TicketsClosed:
                   $res = "Tickets closed"; 
               break;           
           default:
               throw new CException("Invalid report type");
       }       
       return $res;
    }
    
    public static function getReportTypes()
    {
             
        
        return  array( ReportType::None => " ",
                       ReportType::TicketsCreated  =>ReportType::getReportTypeDescription(ReportType::TicketsCreated),
                       ReportType::TicketsClosed =>ReportType::getReportTypeDescription( ReportType::TicketsClosed));
        
    }
}

class UtilizationDashboardFilter extends CFormModel
{
    
    public $reportTypeId;
    public $dim2ID;
    public $fromDate;
    public $toDate;
    public $agregatedDomainID;
    public $exclusiveDomainID;
    public $subdomainID;
    public $assigned_mentor_id;
    

    public function rules()
    {
        return array(
            array('reportTypeId, dim2ID', 'required'),
            array('reportTypeId, dim2ID, agregatedDomainID, exclusiveDomainID, subdomainID, assigned_mentor_id', 'numerical', 'integerOnly'=>true),
            array('fromDate, toDate', 'date')                     
        );
    }
    
    public function attributeLabels()
    {
		return array(
			'fromDate' => 'From',
			'toDate' => 'To',
                        'agregatedDomainID' => 'Domain (Aggregated)',
                        'exclusiveDomainID'=>'Domain (Exclusive)',
                        'subdomainID' => 'Sub Domain',			
                        'reportTypeId' => 'Report Type',
                        'dim2ID' => 'By',
                        'assigned_mentor_id' => 'Assigned Mentor');
    }
    
    public function retrieveCreateTicketsOvertimeDashboardData()
    {
      //retrieve tha data
      $ticketsCreatedData =  $this->retrieveCreateTicketsOvertimeData();
      $dateFormated = "";
      foreach ($ticketsCreatedData as $data)
      {
          $countPart =  ArrayUtils::getValueOrDefault($data, "EventCount",0);
          $currentReadingYear =  ArrayUtils::getValueOrDefault($data, "Year",1);
          $currentReadingMonth = ArrayUtils::getValueOrDefault($data, "Month", 1);
          $currentReadingDay =   ArrayUtils::getValueOrDefault($data, "Day" ,1); 
          $dateFormated = $dateFormated.sprintf('[new Date(%s, %s, %s), %s],',
                                               $currentReadingYear,
                                               $currentReadingMonth - 1,
                                               $currentReadingDay,
                                               $countPart);
       }
       
       //format the data
       $chartFormatedData = "[".$dateFormated."]" ;// "[[new Date(2015, 2, 1),1],[new Date(2015, 3, 1),1]]";// json_encode($monthData);
       return  $chartFormatedData;   
       
    /*  $fromDate = new DateTime($this->newTicketsFromDate);
      $toDate = new DateTime($this->newTicketsToDate);
       
       $dateInterval;
       switch ($this->newTicketsCurrentDimension)
       {
           case DimensionType::Date:
              $dateInterval =  new DateInterval("P1D");
               break;            
           case DimensionType::MonthOfTheYear:
              $dateInterval =  new DateInterval("P1M");
              DateUtils::resetDateToFirstDayOfTheMonth($fromDate); 
              break;           
           case DimensionType::Year:
               $dateInterval =  new DateInterval("P1Y");
               DateUtils::resetDateToFirstDayOfTheYear($fromDate); 
               break;
           default:
               throw new CException("Invalid dimension");
       }   
       
       $dateFormated = "";
       $currentIndex = 0;
       
       if (count($newEventData)> $currentIndex)
       {
            $currentReading = $newEventData[0]; 
       }           
     
       while ($fromDate <= $toDate)
       { 
          $countPart = 0; 
          DateUtils::getDateParts($fromDate,  $year,$month, $day);
          
          if (isset($currentReading))
          {
            $currentReadingYear =  ArrayUtils::getValueOrDefault($currentReading, "Year",1);
            $currentReadingMonth = ArrayUtils::getValueOrDefault($currentReading, "Month", 1);
            $currentReadingDay =   ArrayUtils::getValueOrDefault($currentReading, "Day" ,1); 
            
            if ($year == $currentReadingYear && $month == $currentReadingMonth && $day == $currentReadingDay)
            {
               $countPart =  ArrayUtils::getValueOrDefault($currentReading, "EventCount",0);
               $currentIndex++;
               if (count($newEventData)> $currentIndex)
               {
                   $currentReading = $newEventData[$currentIndex]; 
               }else
               {
                   $currentReading = NULL;                   
               }               
            }
            
          }       
                   
          $dateFormated = $dateFormated.sprintf('[new Date(%s, %s, %s), %s],',$year,$month - 1,$day,$countPart);
         
           
          $fromDate->add($dateInterval);
       }
        $newEvents = "[".$dateFormated."]" ;// "[[new Date(2015, 2, 1),1],[new Date(2015, 3, 1),1]]";// json_encode($monthData);

        return $newEvents;*/
    }
    
    public function retrieveClosedTicketsOvertimeDashboardData()
    {
      //retrieve tha data
      $ticketsClosedData =  $this->retrieveClosedTicketsOvertimeData();
      $dateFormated = "";
      foreach ($ticketsClosedData as $data)
      {
          $countPart =  ArrayUtils::getValueOrDefault($data, "EventCount",0);
          $currentReadingYear =  ArrayUtils::getValueOrDefault($data, "Year",1);
          $currentReadingMonth = ArrayUtils::getValueOrDefault($data, "Month", 1);
          $currentReadingDay =   ArrayUtils::getValueOrDefault($data, "Day" ,1); 
          $dateFormated = $dateFormated.sprintf('[new Date(%s, %s, %s), %s],',
                                               $currentReadingYear,
                                               $currentReadingMonth - 1,
                                               $currentReadingDay,
                                               $countPart);
       }
       
       //format the data
       $chartFormatedData = "[".$dateFormated."]" ;// "[[new Date(2015, 2, 1),1],[new Date(2015, 3, 1),1]]";// json_encode($monthData);
       return  $chartFormatedData; 
        
    }
     
    public function retrieveCreatedByAssignedMentorDashboardData()
    {
        
        //retrieve tha data
      $ticketsCreatedData =  $this->retrieveCreatedTicketsByAssignedMentorData();
      $dateFormated = "";
      foreach ($ticketsCreatedData as $data)
      {
          $countPart =  ArrayUtils::getValueOrDefault($data, "EventCount",0);
          $mentorName =   ArrayUtils::getValueOrDefault($data, "MentorName",0);
          $dateFormated = $dateFormated.sprintf("['%s', %s],",
                                               $mentorName,
                                               $countPart);
       }
       
       //format the data
       $chartFormatedData = "[".$dateFormated."]" ;// "[[new Date(2015, 2, 1),1],[new Date(2015, 3, 1),1]]";// json_encode($monthData);
       return  $chartFormatedData; 
        
    }
    
    public function retrieveClosedByAssignedMentorDashboardData()
    {
          //retrieve tha data
      $ticketsCreatedData =  $this->retrieveClosedTicketsByAssignedMentorData();
      $dateFormated = "";
      foreach ($ticketsCreatedData as $data)
      {
          $countPart =  ArrayUtils::getValueOrDefault($data, "EventCount",0);
          $mentorName =   ArrayUtils::getValueOrDefault($data, "MentorName",0);
          $dateFormated = $dateFormated.sprintf("['%s', %s],",
                                               $mentorName,
                                               $countPart);
       }
       
       //format the data
       $chartFormatedData = "[".$dateFormated."]" ;// "[[new Date(2015, 2, 1),1],[new Date(2015, 3, 1),1]]";// json_encode($monthData);
       return  $chartFormatedData;         
    }
    
    private function retrieveCreateTicketsOvertimeData()
    {
      $command =  Yii::app()->db->createCommand();
                  
      switch ($this->dim2ID)
      {
         case DimensionType::Date:
               $command->select(array("COUNT(1) AS EventCount, DAY(event_recorded_date) AS Day, MONTH(event_recorded_date) AS Month, YEAR(event_recorded_date)AS Year"));  
               $command->group('DATE(ticket_events.event_recorded_date)');
           
               break;            
           case DimensionType::MonthOfTheYear:
               $command->select(array("COUNT(1) AS EventCount, 1 AS Day, MONTH(event_recorded_date) AS Month, YEAR(event_recorded_date) AS Year")); 
               $command->group('YEAR(ticket_events.event_recorded_date), MONTH(ticket_events.event_recorded_date)');
             
               break;           
           case DimensionType::Year:
               $command->select(array("COUNT(1) AS EventCount, 1 AS Day, 1 AS Month ,YEAR(event_recorded_date) AS Year")); 
               $command->group('YEAR(ticket_events.event_recorded_date)');
           
     
               break;
           default:
               throw new CException("Invalid dimension");
       }
       $command->from("ticket_events");
       $command->join('ticket', 'ticket.id = ticket_events.ticket_id');
       $command->where("ticket_events.event_type_id = ".EventType::Event_New);
       
       
       if ($this->fromDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date >= '".DateUtils::getSQLDateStringFromDateStr($this->fromDate)."'");
       }
       
       if ($this->toDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date <= '".DateUtils::getSQLDateStringFromDateStr($this->toDate)."'");
       }
    
       if (isset($this->exclusiveDomainID) && $this->exclusiveDomainID >0)
       {
            $command->andWhere("ticket.subdomain_id IS NULL AND ticket.domain_id = ".$this->exclusiveDomainID);
       }
       
       if (isset($this->agregatedDomainID) && $this->agregatedDomainID >0)
       {
            $command->andWhere("ticket.domain_id = ".$this->agregatedDomainID);
       }
       
       if (isset($this->subdomainID) && $this->subdomainID >0)
       {
            $command->andWhere("ticket.subdomain_id = ".$this->subdomainID);
       }
       
       if (isset($this->assigned_mentor_id) && $this->assigned_mentor_id >0)
       {
           $command->andWhere("ticket.assign_user_id = ".$this->assigned_mentor_id);
       }
       
       
      // $fsdf =  $command->text;
       
       return $command->queryAll(); 
    }
    
    private function retrieveClosedTicketsOvertimeData()
    {
      $command =  Yii::app()->db->createCommand();
                  
      switch ($this->dim2ID)
       {
         case DimensionType::Date:
               $command->select(array("COUNT(1) AS EventCount, DAY(event_recorded_date) AS Day, MONTH(event_recorded_date) AS Month, YEAR(event_recorded_date)AS Year"));  
               $command->group('DATE(ticket_events.event_recorded_date)');
           
               break;            
           case DimensionType::MonthOfTheYear:
               $command->select(array("COUNT(1) AS EventCount, 1 AS Day ,MONTH(event_recorded_date) AS Month, YEAR(event_recorded_date) AS Year")); 
               $command->group('YEAR(ticket_events.event_recorded_date), MONTH(ticket_events.event_recorded_date)');
             
               break;           
           case DimensionType::Year:
               $command->select(array("COUNT(1) AS EventCount, 1 AS Day, 1 AS Month, YEAR(event_recorded_date) AS Year")); 
               $command->group('YEAR(ticket_events.event_recorded_date)');
           
     
               break;
           default:
               throw new CException("Invalid dimension");
       }
       $command->from("ticket_events");
       $command->join('ticket', 'ticket.id = ticket_events.ticket_id');
       //status changed and closed
       $command->where("ticket_events.event_type_id = ".EventType::Event_Status_Changed);
       $command->where("ticket_events.new_value = 'Close'");
       
       
       if ($this->fromDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date >= '".DateUtils::getSQLDateStringFromDateStr($this->fromDate)."'");
       }
       
       if ($this->toDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date <= '".DateUtils::getSQLDateStringFromDateStr($this->toDate)."'");
       }
    
       if (isset($this->exclusiveDomainID) && $this->exclusiveDomainID >0)
       {
            $command->andWhere("ticket.subdomain_id IS NULL AND ticket.domain_id = ".$this->exclusiveDomainID);
       }
       
       if (isset($this->agregatedDomainID) && $this->agregatedDomainID >0)
       {
            $command->andWhere("ticket.domain_id = ".$this->agregatedDomainID);
       }
       
       if (isset($this->subdomainID) && $this->subdomainID >0)
       {
            $command->andWhere("ticket.subdomain_id = ".$this->subdomainID);
       }
       
       if (isset($this->assigned_mentor_id) && $this->assigned_mentor_id >0)
       {
           $command->andWhere("ticket.assign_user_id = ".$this->assigned_mentor_id);
       }
      // echo $command->text;
       
       return $command->queryAll(); 
    
    }
   
    private function retrieveCreatedTicketsByAssignedMentorData()
    {
      $command =  Yii::app()->db->createCommand();
                  
      $command->select(array("COUNT(1) AS EventCount, CONCAT_WS(' ',
                `user`.`fname`,
                `user`.`mname`,
                `user`.`lname`) AS MentorName")); 
      $command->group('ticket.assign_user_id');
           
      
       $command->from("ticket_events");
       $command->join('ticket', 'ticket.id = ticket_events.ticket_id');
       $command->join('user', 'user.id = ticket.assign_user_id');
       $command->where("ticket_events.event_type_id = ".EventType::Event_New);
       
       
       if ($this->fromDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date >= '".DateUtils::getSQLDateStringFromDateStr($this->fromDate)."'");
       }
       
       if ($this->toDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date <= '".DateUtils::getSQLDateStringFromDateStr($this->toDate)."'");
       }
    
       if (isset($this->exclusiveDomainID) && $this->exclusiveDomainID >0)
       {
            $command->andWhere("ticket.subdomain_id IS NULL AND ticket.domain_id = ".$this->exclusiveDomainID);
       }
       
       if (isset($this->agregatedDomainID) && $this->agregatedDomainID >0)
       {
            $command->andWhere("ticket.domain_id = ".$this->agregatedDomainID);
       }
       
       if (isset($this->subdomainID) && $this->subdomainID >0)
       {
            $command->andWhere("ticket.subdomain_id = ".$this->subdomainID);
       }
       
       if (isset($this->assigned_mentor_id) && $this->assigned_mentor_id >0)
       {
           $command->andWhere("ticket.assign_user_id = ".$this->assigned_mentor_id);
       }
       
       
      // $fsdf =  $command->text;
       
       return $command->queryAll(); 
        
    }
    
    private function  retrieveClosedTicketsByAssignedMentorData()
    {
        $command =  Yii::app()->db->createCommand();
                  
      $command->select(array("COUNT(1) AS EventCount, CONCAT_WS(' ',
                `user`.`fname`,
                `user`.`mname`,
                `user`.`lname`) AS MentorName")); 
      $command->group('ticket.assign_user_id');
           
      
       $command->from("ticket_events");
       $command->join('ticket', 'ticket.id = ticket_events.ticket_id');
       $command->join('user', 'user.id = ticket.assign_user_id');
       $command->where("ticket_events.event_type_id = ".EventType::Event_Status_Changed);
       $command->where("ticket_events.new_value = 'Close'");
       
       
       if ($this->fromDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date >= '".DateUtils::getSQLDateStringFromDateStr($this->fromDate)."'");
       }
       
       if ($this->toDate != "")
       {
           $command->andWhere("ticket_events.event_recorded_date <= '".DateUtils::getSQLDateStringFromDateStr($this->toDate)."'");
       }
    
       if (isset($this->exclusiveDomainID) && $this->exclusiveDomainID >0)
       {
            $command->andWhere("ticket.subdomain_id IS NULL AND ticket.domain_id = ".$this->exclusiveDomainID);
       }
       
       if (isset($this->agregatedDomainID) && $this->agregatedDomainID >0)
       {
            $command->andWhere("ticket.domain_id = ".$this->agregatedDomainID);
       }
       
       if (isset($this->subdomainID) && $this->subdomainID >0)
       {
            $command->andWhere("ticket.subdomain_id = ".$this->subdomainID);
       }
       
       if (isset($this->assigned_mentor_id) && $this->assigned_mentor_id >0)
       {
           $command->andWhere("ticket.assign_user_id = ".$this->assigned_mentor_id);
       }
       
       
      // $fsdf =  $command->text;
       
       return $command->queryAll(); 
        
    }


    /*  public static function initializeFilters()
    {
            $date = new DateTime();
		    date_sub($date, new DateInterval("P1Y"));
            
            //Initialize the filter model
            $ultilizationFilter = new UtilizationDashboardFilter();
            $ultilizationFilter->newTicketsCurrentDimension = DimensionType::MonthOfTheYear;
            $ultilizationFilter->newTicketsToDate = date('m/d/Y');// date("m/d/y");
	    $ultilizationFilter->newTicketsFromDate =  $date->format('m/d/Y');
            
            $ultilizationFilter->closedTicketsCurrentDimension = DimensionType::MonthOfTheYear;
            $ultilizationFilter->closedTicketsToDate = date('m/d/Y');// date("m/d/y");
	    $ultilizationFilter->closedTicketsFromDate =  $date->format('m/d/Y');
            
            
            
            return $ultilizationFilter;
        }*/

     
     
}

