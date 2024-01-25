<? include "../../config/core.php";

   if (!$user_id) header('location: /user/');

   // 
   $id = $_GET['id'];

   $lesson_d = fun::lesson($id);
   $course_d = fun::cours($lesson_d['cours_id']);

   if ($course_d['view'] == 0) header('location: lesson/?id='.$id);
   else header('location: lesson/view1.php?id='.$id);
