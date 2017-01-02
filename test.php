
<?php   
echo "huhu";
// Turn off all error reporting
error_reporting(0);
echo "hi";
function curl_get_contents($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function getjobcontent($id){
	$content=curl_get_contents("https://api.greenhouse.io/v1/boards/livecareer/jobs/".$id);
	$data=json_decode($content,true);
	return ($data['content']);
}

function getAlldata(){
$content=curl_get_contents("https://api.greenhouse.io/v1/boards/livecareer/embed/offices");
$data=json_decode($content,true);
//print_r($data['offices']);
//$offices=$data->offices;
$office=new \stdClass;
$department=new \stdClass;
$job=new \stdClass;
global $allOffices;
$departments=array();
$jobs=array();
foreach ($data['offices'] as $office1){
		//print_r($office1['id']);
		$office->id=$office1['id'];
	 	$office->name=$office1['name'];
	 	$office->njobs=0;
	 	$office->departments=$departments;	
		foreach ($office1['departments'] as $department1){
			//print_r($department1);
			$department->id=$department1['id'];
			$department->name=$department1['name'];
	 		$department->njobs=0;
	 		$department->jobs=$jobs;
	 		foreach ($department1['jobs'] as $job1){
	 			$office->njobs=$office->njobs+1;
	 			$job->id=$job1['id'];
			    $job->title=$job1['title'];
			    $job->location=$job1['location']['name'];
			    //print_r() ;
			   // $job->content=getjobcontent($job->id);
			    $department->jobs[]=$job;
			    $department->njobs=$department->njobs+1;;
			    unset($job);
	 		} 		
	 		$office->departments[]=$department;
	 		unset($department);
		}
		$allOffices[]=$office;
		unset($office);
	}
	return $allOffices;
}
getAlldata();
//print_r(array_filter($allOffices, 'departments'));
function openings_by_office($id=0){
	$totaljobs=0;
	global $allOffices;
	foreach($allOffices as $office){
		if($office->id==$id){
		  $totaljobs=$office->njobs;
		  break;
		}else{
			if($office->njobs!='0')
				$totaljobs=$totaljobs+$office->njobs;
		}
	}
	return $totaljobs; 
}
function total_openigs(){
	$totaljobs=0;
	global $allOffices;
	foreach($allOffices as $office){
		
				$totaljobs=$totaljobs+$office->njobs;
		
	}
	return $totaljobs;
}
function getAlljobs(){
	global $allOffices ;
	foreach($allOffices as $office){
		//if($office->id==$officeId){
			foreach ($office->departments as $department){
				//if($department->id==$departmentId){
					foreach ($department->jobs as $job){
						//print_r($job);
						echo $job->location.'</br>';
						echo $job->title.'</br>';
					}
				//}
			}
		//}
	}
}
function jobs_by_office_department($officeId,$departmentId){
	global $allOffices ;
	foreach($allOffices as $office){
		if($office->id==$officeId){
			foreach ($office->departments as $department){
				if($department->id==$departmentId){
				foreach ($department->jobs as $job){
					//print_r($job);
					echo $job->location.'</br>';
					echo $job->title.'</br>';
				}
			}
			}
		}
	}
}
function jobs_by_department($departmentId){
	global $allOffices ;
		foreach($allOffices as $office){
				foreach ($office->departments as $department){
					if($department->id==$departmentId){
						foreach ($department->jobs as $job){
							//print_r($job);
							echo $job->location.'</br>';
							echo $job->title.'</br>';
						}
					}
				}
		}
		  
	
}
function jobs_by_office($officeId){
  global $allOffices ;
  
 foreach($allOffices as $office){
		if($office->id==$officeId){
			foreach ($office->departments as $department){
				foreach ($department->jobs as $job){
					//print_r($job);
					echo $job->location.'</br>';
					echo $job->title.'</br>';
				}
			}
		}
 }
}
print_r('Total Opening->'.gettotalopenig('3229'));
print_r('Total Opening->'.gettotalopenig('3228'));
print_r('Total Opening->'.gettotalopenigAll());
//getAllOfficejobs(3229);
//getAllDepartmentjobs(5978);
//getAlljobs();
getjobs(3229,5978);
//echo '<div>'.getjobcontent(185290).'</div>';
//echo html_entity_decode(getjobcontent(185290));
?>
 
