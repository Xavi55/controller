<?php
//FILE LOCATION
//~kxg2/CS490/processExam.php

//i ran this first...        fs sa . <UCID> rlidwk
//then this...      fs setacl . http write

//COLLECTING VARS
$username = $_POST['username'];			//username
$examName = $_POST['examName'];			//examName

//what middlend needs to send to backend
$backendArray = array(
	'username'=>$username,
	'examName'=>$examName




);

//===========================CURL===================================

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_URL,'afsaccess2.njit.edu/~ja398/CS490/rv/reviewExamRequest.php');  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $backendArray);
  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	
	//$responseDB = json_decode(curl_exec($curl));

	$questions=json_decode(curl_exec($curl),true);
	curl_close($curl);	

/*
foreach($questions as $in)
	{
		var_dump($in);
		echo '<br><br><br>';	
	}
*/
/*
$test=0;
if ($test)
	echo "yes";
else
	echo "no";

echo $questions['flag'];
echo "<br><br>";
*/
	if($questions['flag']=='true')//$questions['flag']=='False' )// false or true
	{

	    echo json_encode($questions);
	}
	else
	{

	    //GRADING START
	    foreach($questions as &$q)//PASS BY POINTER//REFRENCE
	    {

		$in=explode(':',$q['input']);
		$out=explode(':',$q['output']);

		$count=count($out);
		$functionName=$q['functionName'];
		$notes='';
		$copy=$q['answer'];
		$grade=$maxGrade=$q['pointWorth'];//['maxGrade'];
		
		$inputs=array();
		for($i=0;$i<$count;$i++)
		{
			$inputs[]=$in[$i];
		}
		
		$header = strtok($copy,':');
		$tok=strtok($header, '(' );
		$pos1=strpos($header,'(');
		$stuP=substr($header,$pos1+1,-1);
		$sPara=explode(',',$stuP); //get student input parameters
		$fname=explode(' ',$tok);  //get function name from student

		//CHECK CODE------------------------------------- 

		$file = "exec.py";
		$handle = fopen($file, 'w');// or die ('Cannot open file: '.$file);
		fwrite($handle, $copy);//write student answer to exec.py
		fclose($handle);

  		$check = exec("python check.py");//does it run?
  		if($check!=1)
  		{
			$check = exec("python check.py >|txt.txt");//write python traceback to file
			$mesg=shell_exec('tail -3 txt.txt');//retrieve python traceback

			$faulty=explode('^',$mesg);
			$copy=str_replace(trim($faulty[0])," 123",$copy); //attempts to fixe erroneous code
			$notes=$notes . " ; Your code has a '$faulty[1]'  error/issue originating from '$faulty[0]'. Deduction: -.5 ; ";
    			$grade=$grade-.5;
		}

		//CHECK PARAMS-----------------------------------
		
		if(strcmp($fname[1],$functionName)!=0)
		{
			$notes=$notes . "You made a mistake in the function header should have function name '$functionName', you provided: '$fname[1]' instead. Deduction: -1 ;"; 
			$grade=$grade-1;
  		}

		if(strcmp($sInput[0],$param[0])!=0)
  		{
			$notes=$notes . "You made a mistake within the function header parameters. You provided ' $sInput[0] ' instead of '$param[0]'. Deduction: -1 ; ";
			$grade=$grade-1;
  		}

		//FINAL GRADING ROUND
		for($i=0;$i<$count;$i++)
		{
			$file = "exec.py";
  			$handle = fopen($file, 'w');// or die ('Cannot open file: '.$file);
  
			fwrite($handle, $copy . "\nprint($fname[1]($inputs[$i]))");//add function call to end
			fclose($handle);

			$res=exec("python ./exec.py 2>&1");
  
  			if($res===$out[$i])
  			{
				$notes=$notes."Correct Input: $inputs[$i] resulted in $res which is equal to case number: $i ; ";
  			}
  			else
  			{
				$dec=0;
				$dec=( (1/$count) * $maxGrade);
				$notes=$notes."You have failed case $i. Your answer provided ' $res ' instead of $out[$i]. Deduction: -".$dec .";";
				$grade=$grade - $dec;
  			}
		}//final grading for

		if ($grade<0)
		{
  			$grade=0;
		}
		$notes=$notes."Final grade is: $grade";

		//write changes to json/array references
		$q['autoNotes']=$notes;
		$q['grade']=$grade;
	    }//( end )foreach questions as...


		echo json_encode($questions);
	}//( end )outer else


/*
					echo"<br><br>CHANGES<br><br>";
					var_dump($questions);
*/
?>
