<?php

/*$url  = 'https://en.wikipedia.org/wiki/Main_Page';
$html = file_get_contents($url);                      // Obtains the HTML code of the site.
preg_match_all('/<a href="(.*?)"/', $html, $matches); // Inserts all the found links into an array.
print_r($matches);


function writelog($str)

{

  @unlink("log.txt");

  $open=fopen("log.txt","a" );

  fwrite($open,$str);

  fclose($open);

}*/


//---------------------example-------------------
$html = file_get_contents('https://en.wikipedia.org/wiki/Main_Page');
preg_match('/<title>(.*)<\/title/i', $html, $title);
$title_out = $title[1];


// This class allows to obtain the information from every web page that it visits.
class WebCrawler{
    
    private $main_url;
    private $current_url;
    private $txt_file;
    private $depth;
    
    // Constructor.
    public function __construct($main_url, $txt_file, $depth){
        $this->main_url = $main_url;
        $this->current_url = $main_url;
        $this->txt_file = $txt_file;
        $this->depth = $depth;
    }
    
    // Writes the page contents into a txt file.
    // Parameters: the contents that have to be written into the txt file.
    public function writeFile($contents){        
        $openFile = fopen($this->txt_file, "a");
        fwrite($openFile, $contents . PHP_EOL);
        fclose($openFile);
    }
        
    // Gets the page contents and saves them into the txt file.
    // PONER STATIC PARA RECURSIVIDAD
    public function getContents(){
        $html = file_get_contents($this->current_url); 
        $doc = new DOMDocument; 
        @$doc->loadHTML($html);         
        
        // Gets the contents from the div tags.
        $contents = $doc->getElementsByTagName('div'); 

        // Gets the value of each DOMDocumentNode and joins it to $pageContents.
        foreach($contents as $c){
            $this->writeFile($c->nodeValue);
        }        
    }
    
    // Gets the links from the current page and saves them into an array.
    public function getLinks(){
        $html = file_get_contents($this->current_url); 
        preg_match_all('/<a href="(.*?)"/', $html, $matches); // Inserts all the found links into an array.
    }
}

$crawler = new WebCrawler("https://en.wikipedia.org/wiki/Main_Page", "PagesContents.txt", 100);

$string = $crawler->getContents();

echo $string;

