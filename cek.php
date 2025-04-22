<?php
//jika user belum login

if(isset($_SESSION['loh'])){

}else{
    header('location:login.php');
}


?>