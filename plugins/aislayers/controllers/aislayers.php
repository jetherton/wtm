<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controller for the AIS Layers plugin
 */

class aislayers_Controller extends Template_Controller {

	/**
	 * Disable automatic rendering
	 * @var bool
	 */
	public $auto_render = FALSE;

	/**
	 * Template for this controller
	 * @var string
	 */
	public $template = '';


	
	public function get_ship_json(){
	
                $url = 'http://www.marinetraffic.com/ais/exportraw.aspx?id=1234567890&protocol=json&timespan=10&msgtype=extended';
                //$url = 'http://www.marinetraffic.com/ais/exportraw.aspx?id=1234567890&protocol=json&timespan=10';
                                
                $fileTarget = fopen('php://memory', 'w');
                $headerBuff = fopen('php://memory', 'w');
                
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
                curl_setopt($ch, CURLOPT_FILE, $fileTarget);
                curl_setopt($ch, CURLOPT_WRITEHEADER, $headerBuff);
                
                curl_setopt($ch,CURLOPT_HTTPHEADER,array (
                        "GET /ais/exportraw.aspx?id=1234567890&protocol=json&timespan=10&msgtype=extended HTTP/1.1",
                        "Host: www.marinetraffic.com",
                        "Connection: keep-alive",
                        "Cache-Control: max-age=0",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                        "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22",
                        "Accept-Encoding: gzip,deflate,sdch",
                        "Accept-Language: en-US,en;q=0.8",
                        "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3",
                        "Cookie: ASP.NET_SessionId=ad3kekfkekncv20yl0orm2zq; __gads=ID=ccce8e73c2254ee9:T=1367992557:S=ALNI_MZCn7adLMclx9cLNzgxyqZUdeC1-A; __utma=170153020.1382368416.1368057344.1368057344.1368314507.2; __utmb=170153020.5.10.1368314507; __utmc=170153020; __utmz=170153020.1368314507.2.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)",

                ));
                

                

                $data = curl_exec($ch);               
                curl_close($ch);
                
                rewind($headerBuff);
                $headers= stream_get_contents($headerBuff);
                                
                
                
                rewind($fileTarget);
                $data = stream_get_contents($fileTarget);
                
                //figure out if they zipped it
                if(strpos($headers, 'Content-Encoding: gzip')!== false){
                        $data = $this->decode_gzip( array('Content-Encoding'=>'gzip'), $data);     
                }
                
                $data = json_decode($data);
                
                
                
                header('Content-type: application/json; charset=utf-8');
                
                
                
                echo '{"type":"FeatureCollection","features":[';
                $i = 0;
                if($data != null){
                        foreach($data as $ship){
                                if(!isset($ship[6])){
                                        $ship[6] = 'unknown';
                                }
                                $i++;
                                if($i > 1){echo ',';}
                                echo '{"type":"Feature","properties":{';
                                echo '"id":"'.$i.'",';
                                echo '"name":'.json_encode("Speed: ".$ship[2]. "<br/>Course: ".$ship[3]."<br/>Name: ".$ship[6]).',';                        
                                echo '"color":"00cc00",';
                                echo '"icon":"",';
                                echo '"thumb":"",';
                                echo '"timestamp":1333544040,';
                                echo '"count":1,';
                                echo '"class":"stdClass"},';
                                
                                echo '"geometry":{"type":"Point","coordinates":["'.$ship[2].'","'.$ship[1].'"]}}';
                        }
                }
                
                echo ']}';

	}//end get_ship_json
	
	
	/**
	Use this guy to decode the incoming stuff
	not sure if we need this or not
	**/
	function decode_gzip($h,$d,$rn="\r\n"){ 
                if (isset($h['Transfer-Encoding'])){ 
                        $lrn = strlen($rn); 
                        $str = ''; 
                        $ofs=0; 
                        do{ 
                                $p = strpos($d,$rn,$ofs); 
                                $len = hexdec(substr($d,$ofs,$p-$ofs)); 
                                $str .= substr($d,$p+$lrn,$len); 
                                $ofs = $p+$lrn*2+$len; 
                        }
                        while ($d[$ofs]!=='0'); 
                        $d=$str; 
                } 
                if (isset($h['Content-Encoding'])) $d = gzinflate(substr($d,10)); 
                return $d;
        }
	
	
}
