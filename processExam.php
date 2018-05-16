<?php
//FILE LOCATION
//~kxg2/CS490/processExam.php

//i ran this first...        fs sa . <UCID> rlidwk
//then this...      fs setacl . http write

//error_reporting(E_ALL);
//ini_set('display_errors',1);

//middleend will be recieving each question individually, and the backend will be storing 
//each question answer individually
//What middleend obtains

//COLLECTING VARS
$username = $_POST['username'];			//username
$examName = $_POST['examName'];			//examName

$id=$_POST['id'];
$functionName = $_POST['functionName'];		//required name of function
$topic = $_POST['topic'];			//question topic
$parameters=$_POST['parameters'];
$inputRaw = str_replace(' ', '',$_POST['input']);			//raw input string seperated by commas and colons
$outputRaw = str_replace(' ', '',$_POST['output']);			//raw case string seperated by commas
$answer = str_replace('"','',$_POST['answer']);			//student coding answer
$maxGrade=$_POST['maxGrade'];
$difficulty=$_POST['difficulty'];
   
//==============================================================
/*
echo "Your inputs are the following: <br>";
foreach($_POST as $k) 
{
	echo $k;
	echo '<br>';
}
*/


$notes="";
$grade=$maxGrade;	//assume grade is out of 10 points, I haven't put in the actual field for putting in a grade yet when creating the question.
$param=explode(',',$parameters);

//PREPARE INPUTS
$inputs=array();
$in=explode(":",$inputRaw); //turns into array, which divides up the inputs. input[0] corresponds to output[0] and so on.
$out=explode(",",$outputRaw); //turns into array, which divides into sample outputs.

//switch($topic)
//{
 // case 'Conditionals':
/*
sample input output and cases
if casesRaw = "inputA,inputB" then, cases => [0]=='inputA',  [1]=='inputB'
if inputRaw = "1,2:3,4:5,6" then, input => [0]=='1,2',  [1]=='3,4',   [2]=='5,6', || use 
explode(",",input[0]) to obtain an array for output 0 which is [0]=='1', and [1]=='2'
if outputRaw = "3:7:11" then, output => [0]=='3',[1]=='7',[2]=='11'
input[0] corresponds to output[0]. In other words if the function is inserted with 
input[0], then the output should be output[0]
*/


        for($i=0;$i<count($in);$i++)
	{
		//$buffs=explode(',',$in[$i]);
		//array_push($inputs,$buffs[0],$buffs[1]);
		//$inputs[]="'$buffs[0]',$buffs[1],$buffs[2]";

		$inputs[]=$in[$i];
	}


/*
	break;

  case 'Loops':
	$in = explode(':',$inputRaw);
	$out = explode(',',$outputRaw);

        for($i=0;$i<count($out);$i++)
	{
		//$buffs=explode(',',$in[$i]);
		//array_push($inputs,$buffs[0],$buffs[1]);
		//$inputs[]="'$buffs[0]',$buffs[1],$buffs[2]";


		$inputs[]=$in[$i];
	}
	break;

  case 'Lists':
	$in=explode(":",$inputRaw); //turns into array, which divides up the inputs. input[0] corresponds to output[0] and so on.
	$out=explode(",",$outputRaw); //turns into array, which divides into sample outputs.

//make array of params
        for($i=0;$i<count($out);$i++)
	{
		$buffs=explode('?',$in[$i]);
		//array_push($inputs,$buffs[0],$buffs[1]);
		$inputs[]="$buffs[0],$buffs[1]";
	}
	break;	
}
*/


$count=count($out); # of inputs/outputs given
$copy=$answer;

//---------------------

//echo '<br><br>NOW GRADING.....<br><br>';
//echo ';NOW GRADING.....;';

$header=strtok($answer,':');	//extract function header
$tok=strtok($header, '(' );

$pos1=strpos($header,'(');
//$pos2=strpos($header,')',$pos1+1);

$stuP=substr($header,$pos1+1,-1);
$sInput=explode(',',$stuP); //get student input parameters
//var_dump($sInput);


$fname=preg_split("/[\s]/",$tok);

//echo $fname[1];
//PRELIM TESTS -----------------------------------------
//echo '<br>';

//CHECK CODE------------------------------------- 
// shell_exec("echo $copy >>|exec.py \n2>&1");
 //echo "exec.py has ";
 //echo shell_exec("cat exec.py");


  $file = "exec.py";
  $handle = fopen($file, 'w');// or die ('Cannot open file: '.$file);
  fwrite($handle, $copy);
  fclose($handle);

  $check = exec("python check.py");
  if($check!=1)
  {	
    //$file=readfile('test.txt');
    $check = exec("python check.py >|txt.txt");
    $mesg=shell_exec('tail -3 txt.txt');

    $faulty=explode('^',$mesg);    
    $copy=str_replace(trim($faulty[0])," 123",$copy); //attempts to fixe erroneous code

//echo "$mesg<br>$faulty[0] $faulty[1]";

    $notes=$notes . " ; Your code has a '$faulty[1]'  error/issue originating from '$faulty[0]'. Deduction: -.5 ; ";
    $grade=$grade-.5;

//var_dump($copy);
	//echo '<br>';
//var_dump($faulty[0]);

   // echo ' ; ';
  }

//CHECK PARAMS-----------------------------------
  //if(strstr($tok,$functionName)==FALSE)
  if(strcmp($fname[1],$functionName)!=0)
  {
	//$tok=strtok($answer,':');
	$notes=$notes . "You made a mistake in the function header should have function 
name '$functionName', you provided: '$fname[1]' instead. Deduction: -1 ;"; 
	$grade=$grade-1;
  }

  //if(strstr($tok,$param[0])==FALSE)
  if(strcmp($sInput[0],$param[0])!=0)
  {
	$notes=$notes . "You made a mistake within the function header parameters. You 
provided ' $sInput[0] ' instead of '$param[0]'. Deduction: -1 ; ";
	$grade=$grade-1;
  }

//echo ' ; ';

//GRADING
for($i=0;$i<$count;$i++)
{
  //$inners=explode(',',$in[$i]);	

  $file = "exec.py";
  $handle = fopen($file, 'w');// or die ('Cannot open file: '.$file);
//  fwrite($handle, $copy."\nprint($fname[1]('$inners[0]',$inners[1],$inners[2]))");
  //fwrite($handle, $copy."\nprint($fname[1]($inners[0],$inners[1]))");
  
  fwrite($handle, $copy . "\nprint($fname[1]($inputs[$i]))");//newest addon
  fclose($handle);

  $res=exec("python ./exec.py 2>&1");
  
  if($res===$out[$i])
  {
	//echo "Correct Input: $inputs[$i] resulted in $res which is equal to case number: $i ; ";
	$notes=$notes."Correct Input: $inputs[$i] resulted in $res which is equal to case number: $i ; ";
  }
  else
  {
	$dec=0;
	$dec=( (1/$count) * $maxGrade);
	$notes=$notes."You have failed case $i. Your answer provided ' $res ' 
instead of $out[$i]. Deduction: -".$dec .";";
	$grade=$grade - $dec;
  }
}

if ($grade<0)
{
  $grade=0;
}

$notes=$notes."Final grade is: $grade";


echo $notes;




//what middlend needs to send to backend
$backendArray = array(
	'username'=>$username,
	'examName'=>$examName,
	'answer'=>$answer,
	'id'=>$id,
	'autoNotes'=>$notes,
	'grade'=>$grade,
	'maxGrade'=>$maxGrade,
	'difficulty'=>$difficulty,
	'topic'=>$topic
);


//Comment out when testing with frontend
//===========================CURL===================================
/*
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_URL,'afsaccess2.njit.edu/~ja398/CS490/rc/storeExamRequest.php');  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $backendArray);
  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	
	$responseDB = json_decode(curl_exec($curl));
	curl_close($curl);
	echo json_encode($responseDB);
?>
*/
