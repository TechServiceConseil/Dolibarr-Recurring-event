<?php

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/cactioncomm.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/task.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/modules/project/task/mod_task_simple.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';


// File: htdocs/custom/mymodule/myaction.php

// Retrieve object ID
$object_id = GETPOST('id','int');
$object_type = GETPOST('element','alpha');

//Adaptation of object property and redirection link
switch($object_type){
  case "action":
  	$object = new ActionComm($db);
  	$object_date_start_property = "datep";
	$object_date_end_property = "datef";
  	$link = "/comm/action/card.php";
  	$name_element_source_extrafield = "options_fk_actioncomm";
  	break;
  case "project_task":
  	$object = new Task($db);
  	$object_date_start_property = "date_start";
	$object_date_end_property = "date_end";
  	$link = "/projet/tasks/task.php";
  	$name_element_source_extrafield = "options_fk_task_recurrence";
  	break;
  case "project":
  	$object = new Project($db);
  	break;
}

//Get the object
$object->fetch($object_id);

//Check field
$error = 0;
if($object->array_options["options_recurrenceunit"] == "0" || $object->array_options["options_recurrenceunit"] == null){
	$error++;
  setEventMessages("Le champs 'nombre d'unité' doit être saisie ",null,"errors");
}
if($object->{$object_date_start_property} == null){
	$error++;
  setEventMessages("Le champs 'date de début' doit être saisie ",null,"errors");
}
if($object->{$object_date_end_property} == null && $object_type == "action"){
	$error++;
  setEventMessages("Le champs 'date de fin' doit être saisie ",null,"errors");
}
if($object->array_options["options_recurrenceend"] == null){
	$error++;
  setEventMessages("Le champs 'date de fin de récuurence' doit être saisie ",null,"errors");
}
if($error >0){
  //header("Location: ".$link."?id=" . $object->id);
  exit;
}


//Make date objet
$date_start = (new DateTime)->setTimestamp($object->{$object_date_start_property});
if($object->{$object_date_end_property} != null){
  $date_end = (new DateTime)->setTimestamp($object->{$object_date_end_property});
}
$date_end_rec = (new DateTime)->setTimestamp($object->array_options["options_recurrenceend"]);
//echo $date_start->format('U = Y-m-d H:i:s') . "\n";
//echo $date_end->format('U = Y-m-d H:i:s') . "\n";
//echo $date_end_rec->format('U = Y-m-d H:i:s') . "\n";


//Gestion de l'unité // TO DO add it like dictionary time unit
switch ($object->array_options["options_recurrenceunit"]) {
    case "1":
        $recunit = "year";
        break;
    case "2":
        $recunit = "month";
        break;
    case "3":
        $recunit = "week";
        break;
  	case "4":
        $recunit = "day";
        break;
}

//make first calculation
$date_start = $date_start->modify("+".$object->array_options["options_recurrenceunitnumber"]." ".$recunit);
if($date_end != null){
  $date_end = $date_end->modify("+".$object->array_options["options_recurrenceunitnumber"]." ".$recunit);
}


//echo $date_start->format('U = Y-m-d H:i:s') . "\n";
//echo $date_end->format('U = Y-m-d H:i:s') . "\n";
//echo $date_end_rec->format('U = Y-m-d H:i:s') . "\n";

//Variable to handle error
$error = 0;
$atleastone = false;
//Counter to handle infinite loop // TO DO make a parameter in module conf page
$i =0;

//Start to create récurrence
while($date_start<$date_end_rec){
  	$i++;
  	//Handle max creation
  	if($i>200){
	  setEventMessages("Maximum d'événement créer atteint",null,"errors");
	  break;
	;}
  	//Maybe createFromClone is better for task
  	//$newobj_id = $object->createFromClone($user,$object->id,$object->fk_project,$object->fk_task_parent);
  	$newobj = clone $object;
	$newobj->{$object_date_start_property} = intval($date_start->format('U'));
  	if($date_end != null){
  		$newobj->{$object_date_end_property} = intval($date_end->format('U'));
  	}
    
  	$newobj->array_options[$name_element_source_extrafield] = $object->id;
  	$newobj->array_options["recurrencebool"] = 0;
  //if($object->element == "project" || $object->element == "project_task"){
  //	  $mod_task = new mod_task_simple;
  //  $newref = $mod_task->getNextValue($object->thirdparty,$newobj)."\n";
  //  $newobj->ref = $newref;
  //}

  	//record in db
  	if($object_type == "project_task"){
		$res = $newobj->createFromClone($user,$object_id,$object->fk_project,$object->fk_task_parent, false, true, false, false, false, false); 
  	}else{
		$res = $newobj->create($user);
  	}
   	
  	if($res==-1){
	  $error++;
	  $message = "L'élément ".$i.", du ".$date_start->format('Y-m-d H:i:s');
	  if($date_end != null){
		$message .= " au ".$date_end->format('Y-m-d H:i:s');
	  }
	  $message .= " n'a pas été créer.";
	  setEventMessages("errortest",null,"errors");
	}else{
	  if($object_type == "project_task"){
	  	$newtask = new Task($db);
		$newtask->fetch($res);
		$newtask->{$object_date_start_property} = intval($date_start->format('U'));
		if($date_end != null){
		  $newtask->{$object_date_end_property} = intval($date_end->format('U'));
		}
		$newtask->array_options[$name_element_source_extrafield] = $object->id;
  		$newtask->array_options["recurrencebool"] = 0;
		$newtask->update($user);
	  }
	  $atleastone = true;
	  $message = "L'élément ".$i.", du ".$date_start->format('Y-m-d H:i:s');
	  if($date_end != null){
	  	$message .= " au ".$date_end->format('Y-m-d H:i:s');
	  }
	  $message .= " a été créer.";
	  setEventMessages($message,null,"mesgs");
	}
  
  	//Modify date for next recurrence
    $date_start = $date_start->modify("+".$object->array_options["options_recurrenceunitnumber"]." ".$recunit);
  	if($date_end != null){
  	 	$date_end = $date_end->modify("+".$object->array_options["options_recurrenceunitnumber"]." ".$recunit);
  	}
 
  	//echo $date_start->format('U = Y-m-d H:i:s') . "\n";
  	//echo $date_end->format('U = Y-m-d H:i:s') . "\n";
}

//Mark the event as reccurent
if($atleastone){
	$object->array_options["options_recurrencebool"] = "1";
  	$object->update($user);
}else{
  	setEventMessages("Aucun élément créé, vérifiez vos paramètres de récurrence ",null,"errors");
}


// Redirect to object view page
$url = $_SERVER['HTTP_REFERER'];
header("Location: $url");


exit;
