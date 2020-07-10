<?php
include("include/header.php");
$chat_id = $_GET["chat_id"];
function bacaHTML($url){
     // inisialisasi CURL
     $data = curl_init();
     // setting CURL
     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($data, CURLOPT_URL, $url);
     curl_setopt($data, CURLOPT_CONNECTTIMEOUT, 8);//berapa lama ngecek recti up or down 
     curl_setopt($data, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
 
     //menjalankan CURL untuk membaca isi file
     $hasil = curl_exec($data);
     //var_dump($hasil);
     curl_close($data);
     return $hasil;
}
?>
<?php 
     //include("http://10.67.98.98/satellite/ajax/maxdate.php");
     //echo $max_date;
?>

<style type="text/css">
    .box-body{
        font-size:5em;
    }
</style>

<div id="container-test" style="background: white;">
     <div class="box-body resume" 
     style="color: pink;
     background: black;
     text-align: center;
     font-size: 100px;">
     <!--<iframe src="http://10.67.98.98/satellite/pages/mom/resume-branch.php" 
     style="width: 100%" height="1024"> 
     </iframe>-->
     <?php echo $_GET["TEXT"]?>
     </div>
</div> 

<?php 
//$chat_id = "439239139";
?>

<script type="text/javascript">
 
//var walksend = setTimeout(sendTele,3000);     

sti();
function sti(){
    //setTimeout(saveToImage("#container-test","BBA_report"),1000);
    //setTimeout(saveToImage("#container-test-1","BBA_report_1"),3000);
    //setTimeout(saveToImage("#container-test-2","BBA_report_2"),5000);
    saveToImage("#container-test","BBA_report");
    //saveToImage("#container-test","BBA report")
}

function saveToImage(divtable,fname){
    //get the div content
    div_content = document.querySelector(divtable);
    //make it as html5 canvas
    html2canvas(div_content).then(function(canvas) {
        //change the canvas to jpeg image
        data = canvas.toDataURL('image/jpeg');
        //then call a super hero php to save the image
        save_img(data,fname);
   });
}

//to save the canvas image
function save_img(imagemine,fname){
    //ajax method.
    $.post('machine/saveimage.php', {imagemine: imagemine,filename:fname},
    function(res){
        //if the file saved properly, trigger a popup to the user.
        if(res != ''){
            //alert(res);
            //yes = confirm('File Disimpan Pada Folder ict/ci/public/capture, klik ok untuk melihat !');
                //location.href = '<?php //echo base_url()?>'+'/public/capture/'+res+'.jpg';
                // Fixes dual-screen position                         Most browsers      Firefox
                // Fixes dual-screen position                         Most browsers      Firefox
                var w = 900;
                var h = 500;
                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
                var left = ((width / 2) - (w / 2)) + dualScreenLeft;
                var top = ((height / 2) - (h / 2)) + dualScreenTop;
                //var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
                window.open('machine/'+res+'.jpg','targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width='+w+',height='+h+',top='+top+',left='+left);
                //window.open('<?php //echo base_url()?>/public/capture/Resume Branch Per 2017-02-09.xls.jpg','targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
        }
        else{
            alert('something wrong');
        }
    }).done(function() {
        sendTele(fname);
    });
}

function sendTele(img){
  $.ajax({
  type:"POST",
  data:{chatid:'<?php echo $chat_id ?>',img:img},
  url:"machine/sendtele.php",
  success:function(data){
  }
});
}

</script>
