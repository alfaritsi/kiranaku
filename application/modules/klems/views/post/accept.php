<?php
//digunakan untuk menerima post dari url http://10.0.9.37:8080/kiranaku/klems/post/upload
//file ini di simpan disetiap server pabrik 
if(isset($_POST['key']) && $_POST['key'] == base64_encode("kmgroup")){
   $uploaddir     = realpath('./') . '/'. $_POST['path']. '/';
   if (!file_exists($uploaddir)) {
       mkdir($uploaddir, 0777, true);
   }
   
   if(isset($_POST['newname']) && $_POST['newname'] != NULL){
      $uploadfile = $uploaddir . $_POST['newname'];
   }else{
      $uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);
   }
   if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
      return true;
   } else {
       return false;
   }
}else{
   echo "Please check your authentication.";
}
?>