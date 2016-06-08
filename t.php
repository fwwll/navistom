
<?

define(LIBS, __DIR__ .'/libraries/');
require_once(LIBS . 'AcImage/AcImage.php');

class Resize {
	public $path_save;
	public $path;
	public $img;
	public $flag='/*.jpg';
	public $images=[];
	public $size=['h'=>142,'w'=>195];
	public $db;
	public $delimeter =50;
	
	public function __construct($path,$path_save,$size=0){
		/* if($size){
			$this->size=$size;
		} */
		$this->path=$path;
		$this->path_save=$path_save;
		$this->dbconnect();
		$this->load();
		
	}
	
	public function load(){
		
		$path =$this->path . $this->flag;
		$this->images =glob($path);
		///var_dump(count($this->images));
		$this->init();
	}
	
	
	public function init(){
		
	
		$stop=0;
		$i =($this->getCount())? $this->getCount(): 0 ;
		for($i; $i< count($this->images); $i++ ){
			$stop++;
			$img = AcImage::createImage($this->images[$i]);
			/* $img->cropCenter('4pr', '3pr');
			$img->thumbnail(
							$this->size['w'],
							$this->size['h']
						   ); */
			$img->resizeByWidth( $this->size['w']);
            if(!is_file($this->path_save . basename($this->images[$i]))){			
			$img->saveAsJPG($this->path_save . basename($this->images[$i]));
			}
            if($stop== $this->delimeter ){
				$this->setCount(($i+1));
				break;
			} 			
		}
		
		$this->display($i);
		
		
	}
	
	
	public function dbconnect(){
		$db = new PDO('sqlite:img.sqlite3');
							
		$db->exec("CREATE TABLE IF NOT EXISTS images (
                    id INTEGER PRIMARY KEY, 
                    counts INTEGER 
                    )");

             		 
	 $this->db=$db;				
	}

	public function setCount( $co ){
		 $res= $this->db->query("select *from images  WHERE id=1") ;
		  
		  if(!$res->fetchColumn()){
			 $this->db->query("insert into images(counts)values($co) "); 
		    }else{
			 $this->db->query("update images set counts=$co WHERE id=1") ;	
			}
	}
   
    public function getCount(){
		$result= $this->db->query("SELECT * FROM images");
		$row=$result->fetchObject();
		return $row->counts;
	}   
	
	
	public function display($i){
	 $co= count($this->images) -$i;
	// $co.='&ti='.rand(1,5000);
	 ?>
	  <h3>осталось: <?=$co ?></h3>
     <form method='get' action='t.php?=<?=$co?>'>
	 <input type='hidden' name='count' value='<?=$co ?>'/>
	 <input type='submit' value='send' id='step'/>
	 </form>
	 <script>
	   var cou=<?=$co ?>;
	  if(cou>0){
	  var st=  document.getElementById('step');
	      st.click();
	  }	  
	 </script>
	<?
	}
	
	
}


$size=['h'=>142,'w'=>195];

$path=__DIR__ .'/uploads/images/offers/full';
$path_save =__DIR__ .'/uploads/images/offers/test/';
$r= new Resize($path,$path_save,$size);






?>









<?
 
/* $handle = fopen(__DIR__ ."/templates/complete/all_new.min.js", "rt");

while (!feof($handle))
{
	$mytext = fgets($handle, 999);
	echo $mytext."<br />";
} */
//echo'</script>';
/* require_once __DIR__.'/simple_html_dom.php';

$html= file_get_html('http://zooble.com.ua/archive.php') ;
foreach($html->find('div[class=journal]') as $element) { //выборка всех тегов img на странице
       echo $element . '<br>'; // построчный вывод содержания всех найденных атрибутов src
}
 */

?>

<?
//echo $_SERVER['DOCUMENT_ROOT']; 
//set_time_limit(0);
 /* $limit=100; 
 $item =file_get_contents($_SERVER['DOCUMENT_ROOT'].'/item.txt');
 
 $dir_files= $_SERVER['DOCUMENT_ROOT'].'/uploads/images/';
 
 $open_dir=$dir_files.'/offers/full/*.jpg';
 $save_dir=$dir_files.'/offers/142x195/';

$array_img= glob($open_dir);
$count= count($array_img);
$arr_test=  glob($save_dir.'/*.jpg'); 

echo'<br/>---------------------img count----------'.count($array_img).'----------<br/>';
echo'<br/>---------------------save count----------'.count($arr_test).'----------<br/>';
die;
 if( $count > ($limit  +$item)){
	 
 }else{
	 $limit= $count -$item;
 }
 
 echo  count($array_img);
 echo'<br/>';
 echo $item;
$img=array_slice($array_img,$item,$limit);


 foreach ( new imagick( $img )  as $image )
 {  //$image->cropCenter('4pr', '3pr');
	$image->thumbnailImage(142, 195, true, true);
    $image->writeImage($save_dir . basename( $image->getImageFilename()) );
    $image->removeImage();
 } 
 
 
$item=$limit+$item;
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/item.txt', (string)$item);
if($item < $count){
  echo'<script>location.href="t.php?item2='.$item.'&ran='.(rand(0,1000000)).'" </script>';
}else{
  echo'<br/><h1><center>END</center></h1>';
} */

//echo $_SERVER['DOCUMENT_ROOT'];
/* echo'<pre/>';
 //var_dump(glob("*.*"));
function  render_css(){
  return '<style>' .join('',file('/home/testnav1/navistom.com/www/templates/complete/all.min.css')) .'</style>';
} */
/* echo  date('Y-m-d',time());
echo'<br/>';
echo  date('Y-m-d',(time()+((24*60*60)*4)  ));
 


<script>
function fulltime ()
{
var time=new Date();
var newYear=new Date("december,31,2016,00:00:00");
var totalRemains=(newYear.getTime()-time.getTime());
if (totalRemains>1){
	
var RemainsSec = (parseInt(totalRemains/1000));//сколько всего осталось секунд

var RemainsFullDays=(parseInt(RemainsSec/(24*60*60)));//осталось дней

var secInLastDay=RemainsSec-RemainsFullDays*24*3600; //осталось секунд в неполном дне

var RemainsFullHours=(parseInt(secInLastDay/3600));//осталось часов в неполном дне
if (RemainsFullHours<10){RemainsFullHours="0"+RemainsFullHours};
var secInLastHour=secInLastDay-RemainsFullHours*3600;//осталось секунд в неполном часе
var RemainsMinutes=(parseInt(secInLastHour/60));//осталось минут в неполном часе
if (RemainsMinutes<10){RemainsMinutes="0"+RemainsMinutes};
var lastSec=secInLastHour-RemainsMinutes*60;//осталось секунд
if (lastSec<10){lastSec="0"+lastSec};
document.getElementById("RemainsFullDays").innerHTML=RemainsFullDays+"дн. ";
document.getElementById("RemainsFullHours").innerHTML=RemainsFullHours+"час. ";
document.getElementById("RemainsMinutes").innerHTML=RemainsMinutes+"мин. ";
document.getElementById("lastSec").innerHTML=lastSec+"сек. ";
setTimeout('fulltime()',10)
}
else{
document.getElementById("clock").innerHTML="Ура, погуляем!!!";
}
}
</script>
<span id="clock">До Нового Года осталось:
<b><span id="RemainsFullDays"></span></b>
<b><span id="RemainsFullHours"></span></b>
<b><span id="RemainsMinutes"></span></b>
<b><span id="lastSec"></span></b>
</span>
<script type="text/javascript">fulltime();</script>



<?

//echo phpinfo();
/* 
 // Кому отправляем
$to = "volodarik@ex.ua";
 
// Тема
$subject = "e-mail тест";
 
// Сообщение
$message = "Это тестовое сообщение.\n
А ты сегодня улыбался?\n
Конец сообщения.";
 
// Перенос строк
//$message = wordwrap($message, 70);
 
// Отправка почты
// Возвращает TRUE, если письмо отправлено (вернее, было успешно передано программе, которая отправляет почту, например, exim)
if ( mail($to, $subject, $message) )
{
    echo("Почта отправлена ... вроде бы");
}
else
{
    echo("Почта не отправлена");
}   */