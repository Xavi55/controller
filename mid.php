<?php
$array = [];
foreach ($_POST as $key => $value)
{
	switch($key)
	{
		case 'header':
			$array['header'] =$value;
			break;
		case 'username':
			$array['username'] =$value;
			break;
		case 'password':
			$array['password'] =$value;
			break;
		case 'examName':
			$array['examName'] =$value;
			break;
		case 'functionName':
			$array['functionName'] =$value;
			break;
		case 'topic':
			$array['topic'] =$value;
			break;
		case 'difficulty':
			$array['difficulty'] =$value;
			break;
		case 'input':
			$array['input'] =$value;
			break;
		case 'output':
			$array['output'] =$value;
			break;
		case 'questionList':
			$array['questionList'] =$value;
			break;
		case 'question':
			$array['question'] =$value;
			break;	
		case 'parameters':
			$array['parameters'] =$value;
			break;
		case 'maxGrade':
			$array['maxGrade'] =$value;
			break;
		case 'grade':
			$array['grade'] =$value;
			break;
		case 'teacherNotes':
			$array['teacherNotes'] =$value;
			break;
		case 'id':
			$array['id'] =$value;
			break;
		case 'status':
			$array['status'] =$value;
			break;
		case 'pointWorth':
			$array['pointWorth'] =$value;
			break;
		case 'pointList':
			$array['pointList'] =$value;
			break;
		case 'autoNotes':
			$array['autoNotes'] =$value;
      			break;
   		case 'keyword':
     			$array['keyword']=$value;
     			break;
		case 'answer':
			$array['answer']=$value;
			break;
		default:
			break;
	}
}
$header=$_POST['header'];
$url='';
$flag = false;
switch($array['header'])
{
	//------------------------------------------------------------------------
	case 'login':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/login.php';
		break;
	//------------------------------------------------------------------------
	case 'questionBankRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/questionBankRequest.php';
		break;
	//------------------------------------------------------------------------	
	case 'addQuestionRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/addQuestionRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'createExam':
		$url ='afsaccess2.njit.edu/~ja398/CS490/rv/createExam.php';
		break;
	//------------------------------------------------------------------------
	case "teacherExamListRequest":
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/teacherExamListRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'studentExamListRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/studentExamListRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'takeExamRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/takeExamRequest.php';
		break;
	//------------------------------------------------------------------------	
	case 'examStudentList':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/studentExamListRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'reviewExamRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/reviewExamRequest.php';
		break;
	//------------------------------------------------------------------------	
	case 'examUpdateRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/examUpdateRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'examReleaseRequest':
		$url = 'afsaccess2.njit.edu/~ja398/CS490/rv/examReleaseRequest.php';
		break;
    //------------------------------------------------------------------------
	case 'teacherExamScoreRequest':
		$url='afsaccess2.njit.edu/~ja398/CS490/rv/teacherExamScoreRequest.php';
		break;
	//------------------------------------------------------------------------
	case 'questionBankSortRequest':
		$url='afsaccess2.njit.edu/~ja398/CS490/rv/searchQuestionBank.php';
		break;
	//=========================================================================

	case 'submitExamRequest':
		$url='afsaccess2.njit.edu/~ja398/CS490/rv/submitExamRequest.php';
		break;


	case 'examUpdateRequest':
		$url='afsaccess2.njit.edu/~ja398/CS490/rv/examUpdateRequest.php';
		break;

	default:
		$flag = true;
		break;
}


if ($flag != true)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_URL, $url);  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $array);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

	$response = curl_exec($curl);	
	curl_close($curl);

	echo $response;
}
 
else 
{
	$array['url']="failed";
	echo 'header is ' .$array['header'];
}
?>
