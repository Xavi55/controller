<?php
//~kxg2/CS490/kxg2/processExam.php

//simulated front
function proc($link,$d)
{
        $send = curl_init();
        curl_setopt($send, CURLOPT_URL, $link);
        curl_setopt($send,CURLOPT_POST,1);
        curl_setopt($send,CURLOPT_POSTFIELDS,$d);
        curl_setopt($send,CURLOPT_RETURNTRANSFER,1);
        $RET=curl_exec($send);
        curl_close($send);
        return $RET;
}
        $src='https://web.njit.edu/~kxg2/CS490/kxg2/processExam.php';
	//$src='afsaccess2.njit.edu/~ja398/CS490/rc/storeExamRequest.php';

//front needs to format the student answers as such... don't need to worry about escape chars
//php nowdoc

/*
$doc=<<<'lol'
def operation(a, b):
  if a[0]==b[0]:
    return True
  else:
    end1=len(a)-1
    end2=len(b)-1
    if a[end1]==b[end2]:
        return True
  return False
lol;
*/

/*
$doc=<<<'lol'
def operation(a, b):
  if int(a)+int(b)==10 or a==10 or b==10:
    return True
  return False
lol;
*/

/*
$doc="def operation(op, a, b):
  if op =='+':
    return a+b
  elif op=='-':
    return a-b
  elif op =='*':
    return a*b
  elif op =='/':
    return a/b";
*/

/*
$doc="def operation(op, a, b,c):
  if op =='+':
    return a+b+c
  elif op=='-':
    return a-b-c
  elif op =='*':
    return a*b*c
  elif op =='/':
    return a/b/c";
*/

$doc="def diff21(n):
  if n <= 21:
    return 21 - n
  else:
    return (n - 21) * 2";


        $fData=array(
                'header'=>'storeExamRequest',
                'username'=>'kev123',
                'id'=>7,
                'examName'=>'MidTerm',
                'functionName'=>'diff21',
                'parameters'=>'n',
                'topic'=>'Conditionals',
		'answer'=>$doc,
                'maxGrade'=>30,   

		'grade'=>10,
		'difficulty'=>'easy',
		'atuoNotes'=>'bleach',

		'input'=>'1:10',
		'output'=>'20,11'

//		'input'=>'9,10:9,9:9,1',		
//		'output'=>'True,False,True'



//		'input'=>'[1,2,3]?[7,3]:[1,2,3]?[7,3,2]:[1,2,3]?[1,3]',
//		'output'=>'True,False,True'

//                'input'=>"  '+',1,2:'-',4,2:'*',2,8:'/',8,4 ",
  //              'output'=>'3,2,16,2'


//              'input'=>"  '+',1,2,2:'-',4,2,2:'*',2,8,2:'/',8,4,2 ",
  //            'output'=>'5,0,32,1'
		);
//send
        echo proc($src,$fData);
?>
